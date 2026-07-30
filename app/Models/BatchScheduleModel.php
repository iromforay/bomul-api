<?php

namespace App\Models;

/*
 * 범용 배치 진행상황/모니터링용 - bomul(중앙) DB의 batch_schedule을 다룬다.
 * HelpModel과 동일한 관례: 어느 DB가 현재 선택돼있든 상관없이 항상 bomul.테이블로 풀네임 지정.
 *
 * analytics_daily(일별 통계 집계) 전용이 아니라, 앞으로 늘어날 다른 배치 종류(로그수집,
 * 이미지처리 등)도 같은 테이블/로직을 재사용하도록 job_type 컬럼으로 구분한다.
 *
 * 흐름: batch.php(마더)가 매분 각 job의 dispatcher(예: analy.php)를 부르고, 그 dispatcher가
 *   1) 오늘자 run_date+job_type으로 시딩된 행이 없으면 대상 fid 전체를 pending으로 seed()
 *   2) countInFlight()로 "보낸 뒤 아직 콜백 안 온" 개수 확인
 *   3) (MAX_CONCURRENT - inFlight)만큼 claimNextBatch()로 pending을 sent로 마킹해서 가져옴
 *   4) 각 fid에 fire-and-forget curl
 * 각 job의 워커(Cron\Analytics 등)가 처리를 끝내면 콜백을 호출하고, 그 컨트롤러가 markDone()으로
 * 상태를 done/failed로 갱신한다.
 */
class BatchScheduleModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs, "bomul.batch_schedule", "id");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    public function existsForDate($jobType, $runDate)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ".$this->table."
                WHERE job_type = ".$this->db->escape($jobType)." AND run_date = ".$this->db->escape($runDate);
        $rows = $this->getExecQuery($sql);
        return (int)($rows[0]['cnt'] ?? 0) > 0;
    }

    // 대상 fid 전체를 pending으로 일괄 시딩 (오늘 실행 최초 1회만 호출됨 - existsForDate()로 가드)
    public function seed($jobType, $runDate, array $fids)
    {
        if (empty($fids)) {
            return;
        }
        $escType = $this->db->escape($jobType);
        $escDate = $this->db->escape($runDate);
        $values = [];
        foreach ($fids as $fid) {
            $values[] = "(".$escType.", ".$escDate.", ".$this->db->escape($fid).", 'pending', NOW())";
        }
        $sql = "
            INSERT INTO ".$this->table." (job_type, run_date, fid, status, created_at)
            VALUES " . implode(",\n", $values);
        $this->safeExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[batch schedule seed] job_type=".$jobType." run_date=".$runDate." count=".count($fids));
        }
    }

    // 보낸(sent) 뒤 아직 콜백(done/failed) 안 온 개수 - 이만큼은 "이미 처리중"이니 다음 배치에서 제외
    public function countInFlight($jobType, $runDate)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ".$this->table."
                WHERE job_type = ".$this->db->escape($jobType)."
                  AND run_date = ".$this->db->escape($runDate)." AND status = 'sent'";
        $rows = $this->getExecQuery($sql);
        return (int)($rows[0]['cnt'] ?? 0);
    }

    // pending 중 capacity개를 뽑아 sent로 마킹하고, 그 fid 목록을 반환.
    // cron이 1분에 1개 프로세스만 도는 전제라 SELECT 후 UPDATE 사이 경합은 사실상 없음.
    public function claimNextBatch($jobType, $runDate, $capacity)
    {
        if ($capacity <= 0) {
            return [];
        }
        $escType = $this->db->escape($jobType);
        $escDate = $this->db->escape($runDate);

        $sql = "SELECT fid FROM ".$this->table."
                WHERE job_type = $escType AND run_date = $escDate AND status = 'pending'
                LIMIT " . (int)$capacity;
        $rows = $this->getExecQuery($sql);
        $fids = array_column($rows, 'fid');

        if (!empty($fids)) {
            $escFids = array_map(fn($f) => $this->db->escape($f), $fids);
            $sql = "UPDATE ".$this->table."
                    SET status = 'sent', sent_at = NOW()
                    WHERE job_type = $escType AND run_date = $escDate AND fid IN (".implode(",", $escFids).")";
            $this->safeExecQuery($sql);
        }

        if (isset($this->log)) {
            $this->log->info("[batch schedule claim] job_type=".$jobType." run_date=".$runDate." capacity=".$capacity." claimed=".implode(',', $fids));
        }
        return $fids;
    }

    // 각 job 워커의 완료 콜백이 호출
    public function markDone($jobType, $runDate, $fid, $ok)
    {
        $status = $ok ? 'done' : 'failed';
        $sql = "UPDATE ".$this->table."
                SET status = ".$this->db->escape($status).", callback_at = NOW()
                WHERE job_type = ".$this->db->escape($jobType)."
                  AND run_date = ".$this->db->escape($runDate)." AND fid = ".$this->db->escape($fid);
        $result = $this->safeExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[batch schedule markDone] job_type=".$jobType." run_date=".$runDate." fid=".$fid." status=".$status." result=".var_export($result, true));
        }
        return ($this->getDbError()['code'] ?? 0) == 0;
    }

    // 오늘자 실행이 전부 끝났는지(더 이상 pending/sent가 없는지) - dispatcher가 조용히 넘어갈지 판단용
    public function hasRemainingWork($jobType, $runDate)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ".$this->table."
                WHERE job_type = ".$this->db->escape($jobType)."
                  AND run_date = ".$this->db->escape($runDate)." AND status IN ('pending','sent')";
        $rows = $this->getExecQuery($sql);
        return (int)($rows[0]['cnt'] ?? 0) > 0;
    }
}
