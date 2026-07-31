<?php

namespace App\Models;

class PropertyAnalyticsDailyModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs, "property_analytics_daily", "");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    // 1행/매물/일 - 매물 수만큼 나오는 집계라 다른 Model들과 달리 Entity 1개씩 만들어 넣지 않고
    // site_analytics_event를 직접 GROUP BY 해서 한 번의 INSERT...SELECT로 통째로 넣는다
    // (매물 N개면 N번 INSERT하는 대신 쿼리 1번). 같은 날짜 재실행 대비 ON DUPLICATE KEY UPDATE로 멱등 처리.
    public function rebuildForDate($date)
    {
        $escDate = $this->db->escape($date);

        $sql = "
            INSERT INTO ".$this->table." (stat_date, property_no, view_count, avg_dwell_ms)
            SELECT
                $escDate,
                property_no,
                SUM(CASE WHEN event_type = 'pageview' THEN 1 ELSE 0 END),
                ROUND(AVG(CASE WHEN event_type = 'dwell' THEN dwell_ms ELSE NULL END))
            FROM site_analytics_event
            WHERE DATE(occurred_at) = $escDate
              AND property_no > 0
            GROUP BY property_no
            ON DUPLICATE KEY UPDATE
                view_count = VALUES(view_count),
                avg_dwell_ms = VALUES(avg_dwell_ms)
            ";
        $result = $this->safeExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[property_analytics_daily rebuild] date=".$date." result=".var_export($result, true)." affected=".var_export($this->getAffectedRows(), true)." dbError=".var_export($this->getDbError(), true));
        }
        return ($this->getDbError()['code'] ?? 0) == 0;
    }

    // 대시보드 "인기매물 TOP N"용 - 기간 내 조회수 합산 기준 상위 N개, property 테이블과 조인해서
    // 표시용 필드(매물명/주소)까지 한 번에 가져온다. 문의수는 여기 없음 - 고객노트 시스템과
    // 아직 연동이 없어서 이 쿼리만으로는 알 수 없다(호출부에서 inquiry_count는 null로 채움).
    public function getTopByViews($startDate, $endDate, $limit = 5)
    {
        $escStart = $this->db->escape($startDate);
        $escEnd = $this->db->escape($endDate);

        $sql = "
            SELECT
                p.property_no,
                p.property_name,
                p.addr_sido,
                p.addr_sigungu,
                p.addr_dong,
                SUM(pad.view_count) AS view_count,
                ROUND(AVG(pad.avg_dwell_ms)) AS avg_dwell_ms
            FROM ".$this->table." pad
            JOIN property p ON p.property_no = pad.property_no
            WHERE pad.stat_date BETWEEN $escStart AND $escEnd
              AND p.display_yn = 1 AND p.status != 5 AND p.is_deleted = 0
            GROUP BY pad.property_no
            ORDER BY view_count DESC
            LIMIT " . (int)$limit;

        return $this->getExecQuery($sql);
    }
}
