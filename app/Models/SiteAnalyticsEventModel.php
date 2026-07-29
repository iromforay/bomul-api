<?php

namespace App\Models;

use App\Entity\SiteAnalyticsEvent_Entity;

class SiteAnalyticsEventModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs, "site_analytics_event", "event_no");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    // pageview/dwell/click 공통. 방문(visit_id) 하나에 여러 행이 쌓이는 append-only 구조.
    // event_type별로 쓰는 컬럼만 다르고(dwell_ms는 dwell, click_action은 click 등) 나머지는 fill 안 된 채로 들어간다.
    public function register(SiteAnalyticsEvent_Entity $entity)
    {
        $value = $entity->toSqlSet($this, $this->primaryKey);

        $sql = "
            INSERT INTO ".$this->table."
            SET
            $value
            ";
        $event_no = $this->safeExecQuery($sql); // 성공 시 event_no(insertID), 실패 시 0

        if (isset($this->log)) {
            $this->log->info("[event register] result=".var_export($event_no, true)." dbError=".var_export($this->getDbError(), true)." affectedRows=".var_export($this->getAffectedRows(), true));
        }
        return $event_no;
    }

    // 새벽 배치(AnalyticsAggregateService)용 - 특정 날짜의 행동 이벤트 집계.
    public function getDailyEventStats($date)
    {
        $escDate = $this->db->escape($date);

        $sql = "
            SELECT
                SUM(CASE WHEN event_type = 'pageview' THEN 1 ELSE 0 END) AS pageview_count,
                SUM(CASE WHEN event_type = 'click' AND click_action = 'tel' THEN 1 ELSE 0 END) AS phone_click_count
            FROM ".$this->table."
            WHERE DATE(occurred_at) = $escDate
            ";
        $rows = $this->getExecQuery($sql);

        return $rows[0] ?? [];
    }
}
