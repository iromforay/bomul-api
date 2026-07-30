<?php

namespace App\Models;

use App\Entity\SiteAnalyticsDailyStats_Entity;

class SiteAnalyticsDailyStatsModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs, "site_analytics_daily_stats", "stat_date");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    // 1행/일. 같은 날짜로 재실행해도 안전하도록 INSERT ... ON DUPLICATE KEY UPDATE로 멱등 처리.
    // (배치 실패 후 수동 재실행 시 중복행/에러 없이 그 날짜 값만 덮어씀)
    public function upsert(SiteAnalyticsDailyStats_Entity $entity)
    {
        $value = $entity->toSqlSet($this);

        $sql = "
            INSERT INTO ".$this->table."
            SET
            $value
            ON DUPLICATE KEY UPDATE
            $value
            ";
        $result = $this->safeExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[daily_stats upsert] result=".var_export($result, true)." dbError=".var_export($this->getDbError(), true));
        }
        return ($this->getDbError()['code'] ?? 0) == 0;
    }

    // 대시보드 KPI용 - 기간(포함) 내 합계. 이번달/전월 비교에 그대로 재사용.
    public function getTotals($startDate, $endDate)
    {
        $sql = "
            SELECT
                COALESCE(SUM(visitor_count), 0) AS visitor_count,
                COALESCE(SUM(pageview_count), 0) AS pageview_count,
                COALESCE(SUM(new_visitor_count), 0) AS new_visitor_count,
                COALESCE(SUM(returning_visitor_count), 0) AS returning_visitor_count,
                COALESCE(SUM(naver_referral_count), 0) AS naver_referral_count,
                COALESCE(SUM(direct_referral_count), 0) AS direct_referral_count,
                COALESCE(SUM(other_referral_count), 0) AS other_referral_count,
                COALESCE(SUM(phone_click_count), 0) AS phone_click_count
            FROM ".$this->table."
            WHERE stat_date BETWEEN ".$this->db->escape($startDate)." AND ".$this->db->escape($endDate)."
            ";
        $rows = $this->getExecQuery($sql);
        return $rows[0] ?? [];
    }

    // 대시보드 방문자 추이 차트용 - 기간 내 일별 행 그대로.
    public function getDailyRange($startDate, $endDate)
    {
        $sql = "
            SELECT stat_date, visitor_count
            FROM ".$this->table."
            WHERE stat_date BETWEEN ".$this->db->escape($startDate)." AND ".$this->db->escape($endDate)."
            ORDER BY stat_date ASC
            ";
        return $this->getExecQuery($sql);
    }
}
