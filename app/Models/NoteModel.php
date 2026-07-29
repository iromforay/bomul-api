<?php

namespace App\Models;

use App\Entity\Note_Entity;

class NoteModel extends CI_Model
{
    private $log;

    public function __construct($dbs)
    {
        parent::__construct($dbs,"note","note_no");
        return $this;
    }

    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }

    // 부모 CI_Model::get_list()는 $start>0 일 때만 LIMIT을 붙여서 1페이지(start=0)에서
    // 전체 행이 다 나오는 버그가 있음(PropertyModel과 동일 이유로 오버라이드).
    public function get_list($start=0,$limit=0)
    {
        $_limit = ($limit>0) ? " LIMIT $start,$limit " : "";
        $sql = "SELECT * FROM `$this->table` ORDER BY `$this->primaryKey` DESC $_limit";
        return $this->getExecQuery($sql);
    }

    public function get_count()
    {
        $sql = "SELECT count(*) as 'cnt' FROM `$this->table`";
        $rows = $this->getExecQuery($sql);
        return $rows[0]['cnt'] ?? 0;
    }

    public function register(Note_Entity $entity)
    {
        $value = $entity->toSqlSet($this,$this->primaryKey);

        $sql = "
            INSERT INTO ".$this->table."
            SET
            $value
            ";
        $note_no = $this->safeExecQuery($sql); // 성공 시 note_no(insertID), 실패 시 0

        $this->log->info("[note register] result=".var_export($note_no, true)." dbError=".var_export($this->getDbError(), true)." affectedRows=".var_export($this->getAffectedRows(), true));
        return $note_no;
    }

    public function modify(Note_Entity $entity)
    {
        $value = $entity->toSqlSet($this,$this->primaryKey);

        $sql = "
            UPDATE ".$this->table." SET
            $value
            WHERE ".$this->primaryKey." = ".$entity->note_no;
        $success = $this->safeExecQuery($sql);

        $this->log->info("[note modify] result=".var_export($success, true)." dbError=".var_export($this->getDbError(), true)." affectedRows=".var_export($this->getAffectedRows(), true));

        return $success ? true : false;
    }

    public function get_visit_type($visit_type="all")
    {
        $_visit_type = array(
            "tenant" => "세입자",
            "landlord" => "임대인"
        );
        return $visit_type=="all" ? $_visit_type : $_visit_type[$visit_type]??"";
    }

    public function get_transaction_type($transaction_type="all")
    {
        $_transaction_type = array(
            "sale" => "매매",
            "jeonse" => "전세",
            "monthly" => "월세"
        );
        return $transaction_type=="all" ? $_transaction_type : $_transaction_type[$transaction_type]??"";
    }

    public function get_status($status="all")
    {
        $_status = array(
            "consulting" => "상담중",
            "proposed" => "매물제안",
            "contracted" => "계약완료",
            "lost" => "이탈"
        );
        return $status=="all" ? $_status : $_status[$status]??"";
    }

    public function get_inflow_type($inflow_type="all")
    {
        $_inflow_type = array(
            "visit" => "방문",
            "phone" => "전화",
            "web" => "웹신청",
            "naver" => "네이버",
            "referral" => "지인소개",
            "etc" => "기타"
        );
        return $inflow_type=="all" ? $_inflow_type : $_inflow_type[$inflow_type]??"";
    }
}
