<?php

namespace App\Models;

/*
 * 새벽 배치 스케줄링/모니터링용 - bomul(중앙) DB의 analytics_batch_schedule을 다룬다.
 * HelpModel과 동일한 관례: 어느 DB가 현재 선택돼있든 상관없이 항상 bomul.테이블로 풀네임 지정.
 *
 * 흐름: batch.php(마더)가 매분 analy.php를 부르고, analy.php가
 *   1) 오늘자 run_date로 시딩된 행이 없으면 활성 fid 전체를 pending으로 seed()
 *   2) countInFlight()로 "보낸 뒤 아직 콜백 안 온" 개수 확인
 *   3) (MAX_CONCURRENT - inFlight)만큼 claimNextBatch()로 pending을 sent로 마킹해서 가져옴
 *   4) 각 fid에 fire-and-forget curl
 * Cron\Analytics가 집계를 끝내면 api.bomul.co.kr/callback/complete를 호출하고,
 * 그 컨트롤러가 markDone()으로 상태를 done/failed로 갱신한다.
 */
class AnalyticsBatchScheduleModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs, "bomul.analytics_batch_schedule", "id");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    public function existsForDate($runDate)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ".$this->table." WHERE run_date = ".$this->db->escape($runDate);
        $rows = $this->getExecQuery($sql);
        return (int)($rows[0]['cnt'] ?? 0) > 0;
    }

    // 활성 fid 전체를 pending으로 일괄 시딩 (오늘 실행 최초 1회만 호출됨 - existsForDate()로 가드)
    public function seed($runDate, array $fids)
    {
        if (empty($fids)) {
            return;
        }
        $escDate = $this->db->escape($runDate);
        $values = [];
        foreach ($fids as $fid) {
            $values[] = "(".$escDate.", ".$this->db->escape($fid).", 'pending', NOW())";
        }
        $sql = "
            INSERT INTO ".$this->table." (run_date, fid, status, created_at)
            VALUES " . implode(",\n", $values);
        $this->safeExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[batch schedule seed] run_date=".$runDate." count=".count($fids));
        }
    }

    // 보낸(sent) 뒤 아직 콜백(done/failed) 안 온 개수 - 이만큼은 "이미 처리중"이니 다음 배치에서 제외
    public function countInFlight($runDate)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ".$this->table."
                WHERE run_date = ".$this->db->escape($runDate)." AND status = 'sent'";
        $rows = $this->getExecQuery($sql);
        return (int)($rows[0]['cnt'] ?? 0);
    }

    // pending 중 capacity개를 뽑아 sent로 마킹하고, 그 fid 목록을 반환.
    // cron이 1분에 1개 프로세스만 도는 전제라 SELECT 후 UPDATE 사이 경합은 사실상 없음.
    public function claimNextBatch($runDate, $capacity)
    {
        if ($capacity <= 0) {
            return [];
        }
        $escDate = $this->db->escape($runDate);

        $sql = "SELECT fid FROM ".$this->table."
                WHERE run_date = $escDate AND status = 'pending'
                LIMIT " . (int)$capacity;
        $rows = $this->getExecQuery($sql);
        $fids = array_column($rows, 'fid');

        if (!empty($fids)) {
            $escFids = array_map(fn($f) => $this->db->escape($f), $fids);
            $sql = "UPDATE ".$this->table."
                    SET status = 'sent', sent_at = NOW()
                    WHERE run_date = $escDate AND fid IN (".implode(",", $escFids).")";
            $this->safeExecQuery($sql);
        }

        if (isset($this->log)) {
            $this->log->info("[batch schedule claim] run_date=".$runDate." capacity=".$capacity." claimed=".implode(',', $fids));
        }
        return $fids;
    }

    // Cron\Analytics의 콜백(api.bomul.co.kr/callback/complete)이 호출
    public function markDone($runDate, $fid, $ok)
    {
        $status = $ok ? 'done' : 'failed';
        $sql = "UPDATE ".$this->table."
                SET status = ".$this->db->escape($status).", callback_at = NOW()
                WHERE run_date = ".$this->db->escape($runDate)." AND fid = ".$this->db->escape($fid);
        $result = $this->safeExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[batch schedule markDone] run_date=".$runDate." fid=".$fid." status=".$status." result=".var_export($result, true));
        }
        return ($this->getDbError()['code'] ?? 0) == 0;
    }

    // 오늘자 실행이 전부 끝났는지(더 이상 pending/sent가 없는지) - batch.php가 조용히 넘어갈지 판단용
    public function hasRemainingWork($runDate)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ".$this->table."
                WHERE run_date = ".$this->db->escape($runDate)." AND status IN ('pending','sent')";
        $rows = $this->getExecQuery($sql);
        return (int)($rows[0]['cnt'] ?? 0) > 0;
    }
}
