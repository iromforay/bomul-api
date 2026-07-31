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
    public function get_list($start=0,$limit=0,$where=array())
    {
        $where_ = $this->get_where($where);
        $_limit = ($limit>0) ? " LIMIT $start,$limit " : "";
        $sql = "SELECT * FROM `$this->table` $where_ ORDER BY `$this->primaryKey` DESC $_limit";
        return $this->getExecQuery($sql);
    }

    public function get_count($where=array())
    {
        $where_ = $this->get_where($where);
        $sql = "SELECT count(*) as 'cnt' FROM `$this->table` $where_";
        $rows = $this->getExecQuery($sql);
        return $rows[0]['cnt'] ?? 0;
    }

    // 검색조건은 배열로 전달(예: "`status` = 'consulting'"). 상세검색(방문형태/거래방식/방문자명/연락처) 추가 시 이 배열에 조건만 추가하면 됨.
    // soft delete된 노트는 목록/카운트에서 항상 제외하기 위해 is_deleted=0 조건을 여기서 강제로 추가.
    public function get_where($where=array())
    {
        $conditions = is_array($where) ? $where : array();
        $conditions[] = "`is_deleted` = 0";
        return "WHERE ".implode(" AND ",$conditions);
    }

    // $where로 기간 등 조건을 걸 수 있음(예: 대시보드에서 이번달 등록건만 집계). 기본은 전체.
    public function status_total_count($where=array())
    {
        $where_ = $this->get_where($where);
        $sql = "SELECT `status`, count(*) as 'total_count' FROM `$this->table` $where_ GROUP BY `status`";
        return $this->getExecQuery($sql);
    }

    // soft delete된 노트는 상세조회에서도 제외 (Board::Postboard_del() 패턴 참고 - status/is_deleted 플래그만 세우고 실제 삭제하지 않음)
    public function findbyid($seq_no)
    {
        $sql = "SELECT * FROM `$this->table` WHERE `$this->primaryKey` = ".$this->db->escape($seq_no)." AND `is_deleted` = 0";
        return $this->getExecQuery($sql);
    }

    public function sql_del(Note_Entity $entity)
    {
        $sql = "UPDATE `$this->table` SET `is_deleted`=1 WHERE `$this->primaryKey` = ".$this->db->escape($entity->note_no);
        return $this->safeExecQuery($sql);
    }

    public function get_all_status()
    {
        return array("consulting", "proposed", "contracted", "lost");
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
