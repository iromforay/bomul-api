<?php

namespace App\Models;

use App\Entity\SiteAnalyticsVisit_Entity;

class SiteAnalyticsVisitModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs, "site_analytics_visit", "visit_id");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    // 방문(세션) 1회 = 1행. 이후 이 행에 대한 UPDATE는 없음 (append-only, insert-once).
    // visit_id는 auto_increment가 아닌 서버 발급 PK라 toSqlSet()에서 제외하면 안 됨
    // (다른 register()들과 달리 primary_key 인자를 넘기지 않는 이유).
    //
    // INSERT IGNORE를 쓰는 이유: visit_id 응답 전에 FE가 페이지를 이동하면 같은 client_key로
    // "신규 방문" 요청이 두 번 들어올 수 있다(TrackService::collect() 참고). client_key에 UNIQUE
    // 인덱스가 걸려있어 두 번째 INSERT는 자동으로 무시되고, affectedRows로 그걸 구분한다
    // - 반환값 true=이번 요청으로 새로 등록됨, false=이미 존재(중복 or 실패, 호출부에서
    //   getVisitIdByClientKey()로 기존 visit_id를 다시 조회해야 함).
    public function register(SiteAnalyticsVisit_Entity $entity)
    {
        $value = $entity->toSqlSet($this);

        $sql = "
            INSERT IGNORE INTO ".$this->table."
            SET
            $value
            ";
        $this->safeExecQuery($sql);

        $inserted = $this->getAffectedRows() > 0;
        if (isset($this->log)) {
            $this->log->info("[visit register] inserted=".var_export($inserted, true)." dbError=".var_export($this->getDbError(), true));
        }
        return $inserted;
    }

    // register()가 false(=client_key 중복)를 반환했을 때만 호출 - 먼저 등록된 visit_id를 조회.
    public function getVisitIdByClientKey($client_key)
    {
        $sql = "SELECT visit_id FROM ".$this->table." WHERE client_key = ".$this->db->escape($client_key)." LIMIT 1";
        $rows = $this->getExecQuery($sql);

        if (isset($this->log)) {
            $this->log->info("[visit lookup by client_key] client_key=".var_export($client_key, true)." result=".var_export($rows, true));
        }
        return $rows[0]['visit_id'] ?? null;
    }
}
