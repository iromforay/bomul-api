<?php

namespace App\Models;

class SiteAnalyticsEventModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs, "site_analytics_event", "event_no");
        return $this;
    }

    // pageview/dwell/click 공통. 방문(visit_id) 하나에 여러 행이 쌓이는 append-only 구조.
    // event_type별로 쓰는 컬럼만 다르고(dwell_ms는 dwell, click_action은 click 등) 나머지는 NULL로 들어간다.
    public function sql_save($data)
    {
        $osql = " INSERT INTO `$this->table` SET
                        `visit_id`=%s, `event_type`=%s, `occurred_at`=%s,
                        `page_type`=%s, `property_no`=%s, `request_url`=%s,
                        `dwell_ms`=%s, `click_action`=%s, `search_filter`=%s
                ";
        $sql = sprintf($osql,
            $this->db->escape($data['visit_id'] ?? null),
            $this->db->escape($data['event_type'] ?? null),
            $this->db->escape($data['occurred_at'] ?? null),
            $this->db->escape($data['page_type'] ?? null),
            $this->db->escape($data['property_no'] ?? null),
            $this->db->escape($data['request_url'] ?? null),
            $this->db->escape($data['dwell_ms'] ?? null),
            $this->db->escape($data['click_action'] ?? null),
            $this->db->escape($data['search_filter'] ?? null)
        );

        return $this->safeExecQuery($sql);
    }
}
