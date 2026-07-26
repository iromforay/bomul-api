<?php

namespace App\Models;

class SiteAnalyticsVisitModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs, "site_analytics_visit", "visit_id");
        return $this;
    }

    // 방문(세션) 1회 = 1행. 이후 이 행에 대한 UPDATE는 없음 (append-only, insert-once).
    // 엔티티 없이 배열을 직접 받는다 (entity 생성은 지시받은 것만 - 진행 중 합의사항).
    public function sql_save($data)
    {
        $osql = " INSERT INTO `$this->table` SET
                        `visit_id`=%s, `occurred_at`=%s, `ip`=%s,
                        `referrer_url`=%s, `utm_source`=%s, `utm_medium`=%s, `utm_campaign`=%s,
                        `user_agent`=%s, `viewport_w`=%s, `viewport_h`=%s, `is_returning`=%s
                ";
        $sql = sprintf($osql,
            $this->db->escape($data['visit_id'] ?? null),
            $this->db->escape($data['occurred_at'] ?? null),
            $this->db->escape($data['ip'] ?? null),
            $this->db->escape($data['referrer_url'] ?? null),
            $this->db->escape($data['utm_source'] ?? null),
            $this->db->escape($data['utm_medium'] ?? null),
            $this->db->escape($data['utm_campaign'] ?? null),
            $this->db->escape($data['user_agent'] ?? null),
            $this->db->escape($data['viewport_w'] ?? null),
            $this->db->escape($data['viewport_h'] ?? null),
            $this->db->escape($data['is_returning'] ?? null)
        );

        return $this->safeExecQuery($sql);
    }
}
