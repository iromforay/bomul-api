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
}
