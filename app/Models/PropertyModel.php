<?php

namespace App\Models;

use App\Entity\Property_Entity;
use App\Models\CI_Model;

class PropertyModel extends CI_Model
{
    private $code;
    private $log;
    public function __construct($dbs)
    {
        parent::__construct($dbs,"property","property_no");

        return $this;
    }
    public function getLog($log)
    {
        $this->log = $log;
        return $this;
    }
    public function register(Property_Entity $entity)
    {
        $value = $entity->toSqlSet($this,$this->primaryKey ) ; // $this->db);

        $sql = "
            INSERT INTO ".$this->table."
            SET
            $value
            ";
        $last_id = $this->safeExecQuery($sql); // 성공 시 property_no(insertID), 실패 시 0

        $this->log->info("[save] result= last_id : ".$last_id." dbError=".var_export($this->lastError, true)." affectedRows=".var_export($this->lastAffectedRows, true));
        return $last_id;

    }

    // toSqlSet()은 빈 값인 필드를 SET절에서 제외해서 NULL로 명시적으로 비울 수 없으므로,
    // main_image/main_path는 entity 경로를 안 거치고 이 함수 하나로 설정/삭제 둘 다 처리.
    // $main_image_name/$main_image_path를 null로 주면 컬럼을 NULL로 비움.
    public function setMainImage($property_no, $main_image_name, $main_image_path)
    {
        $name = ($main_image_name === null || $main_image_name === '') ? 'NULL' : $this->db->escape($main_image_name);
        $path = ($main_image_path === null || $main_image_path === '') ? 'NULL' : $this->db->escape($main_image_path);

        $sql = "UPDATE ".$this->table." SET main_image = $name, main_path = $path WHERE ".$this->primaryKey." = ".(int)$property_no;
        $success = $this->safeExecQuery($sql);

        $this->log->info("[setMainImage] name=".var_export($main_image_name, true)." path=".var_export($main_image_path, true)." result=".var_export($success, true)." dbError=".var_export($this->getDbError(), true)." affectedRows=".var_export($this->getAffectedRows(), true));

        return $success ? true : false;
    }

    public function modify(Property_Entity $entity)
    {

        $value = $entity->toSqlSet($this,$this->primaryKey);

        $sql = "
            UPDATE ".$this->table." SET
            $value
            WHERE ".$this->primaryKey." = ".$entity->property_no;
        $success = $this->safeExecQuery($sql);

        $this->log->info("[modify] result=".var_export($success, true)." dbError=".var_export($this->getDbError(), true)." affectedRows=".var_export($this->getAffectedRows(), true));

        return $success ? true : false;
    }

    // CI_Model의 제네릭 get_list/get_count는 $where를 무시하므로 오버라이드.
    // $where는 문자열 또는 조건 문자열 배열(AND로 결합) 둘 다 허용.
    public function get_list($start=0,$limit=0,$where="")
    {
        $where_sql = $this->build_where($where);
        $_limit = ($limit>0) ? " LIMIT $start,$limit " : "";
        $sql = "SELECT * FROM `$this->table` $where_sql ORDER BY `$this->primaryKey` DESC $_limit";
        return $this->getExecQuery($sql);
    }

    public function get_count($where="")
    {
        $where_sql = $this->build_where($where);
        $sql = "SELECT count(*) as 'cnt' FROM `$this->table` $where_sql";
        $rows = $this->getExecQuery($sql);
        return $rows[0]['cnt'] ?? 0;
    }

    // NoteModel::status_total_count()와 동일한 패턴. is_deleted=0은 build_where()가 항상 붙여줌.
    public function status_total_count($where="")
    {
        $where_sql = $this->build_where($where);
        $sql = "SELECT `status`, count(*) as 'total_count' FROM `$this->table` $where_sql GROUP BY `status`";
        return $this->getExecQuery($sql);
    }

    // 위와 동일한 패턴 - 노출여부(노출/숨김)별 전체 매물수.
    public function display_yn_total_count($where="")
    {
        $where_sql = $this->build_where($where);
        $sql = "SELECT `display_yn`, count(*) as 'total_count' FROM `$this->table` $where_sql GROUP BY `display_yn`";
        return $this->getExecQuery($sql);
    }

    // soft delete된 매물은 목록/카운트에서 항상 제외.
    private function build_where($where)
    {
        $conditions = empty($where) ? [] : (is_array($where) ? $where : [$where]);
        $conditions[] = "`is_deleted` = 0";
        return "WHERE ".implode(" AND ", $conditions);
    }

    public function search($property_name)
    {
        $property_name =  $this->db->escapeLikeString($property_name);
        $sql = "SELECT * FROM `$this->table` WHERE (`property_name` like '%".$property_name."%' OR `building_name` like '%".$property_name."%')  AND `is_deleted` = 0";

        return $this->getExecQuery($sql);
    }
    public function findbyInid($seq_nos)
    {
        $ids = is_array($seq_nos) ? $seq_nos : explode(",", $seq_nos);
        $ids = array_map("intval", $ids);
        $ids = array_filter($ids, fn($id) => $id > 0);

        if (empty($ids)) {
            return [];
        }

        $sql = "SELECT * FROM `$this->table` WHERE `$this->primaryKey` IN (".implode(",", $ids).") AND `is_deleted` = 0";
        return $this->getExecQuery($sql);
    }
    // soft delete된 매물은 상세조회에서도 제외 (Board::Postboard_del() 패턴 참고)
    public function findbyid($seq_no)
    {
        $sql = "SELECT * FROM `$this->table` WHERE `$this->primaryKey` = ".$this->db->escape($seq_no)." AND `is_deleted` = 0";
        return $this->getExecQuery($sql);
    }

    public function sql_del(Property_Entity $entity)
    {
        $sql = "UPDATE `$this->table` SET `is_deleted`=1 WHERE `$this->primaryKey` = ".$this->db->escape($entity->property_no);
        return $this->safeExecQuery($sql);
    }

    public function viewcount($property_no)
    {
        $sql = "UPDATE `$this->table` SET view_count = view_count+1 WHERE `$this->primaryKey` = ".$this->db->escape($property_no);
        return $this->safeExecQuery($sql);
    }

    public function get_direction($direction="all")
    {
        $_direction = array(
            "E"=>"동",
            "W"=>"서",
            "S"=>"남",
            "N"=>"북",
            "SE"=>"남동",
            "SW"=>"남서",
            "NE"=>"북동",
            "NW"=>"북서"
        );

        return $direction=="all" ? $_direction : $_direction[$direction]??"";
    }
    public function get_heating_fuel($heating_fuel="all")
    {
        $_heating_fuel = array(
            "1"=>"도시가스",
            "2"=>"전기",
            "3"=>"LPG",
            "4"=>"기름",
            "9"=>"기타"
        );
        return $heating_fuel=="all" ? $_heating_fuel : $_heating_fuel[$heating_fuel]??"";
    }
    public function get_heating_type($heating_type="all")
    {
        $_heating_type = array(
            "1"=>"개별난방",
            "2"=>"중앙난방",
            "3"=>"지역난방"
        );
        return $heating_type=="all" ? $_heating_type : $_heating_type[$heating_type]??"";
    }
    public function get_status($status="all")
    {
        $_status = array(
            "1"=>"거래가능",
            "2"=>"거래중",
            "3"=>"거래완료",
            "4"=>"거래보류",
            "5"=>"비공개"
        );
        return $status=="all" ? $_status : $_status[$status]??"";
    }
    public function get_display_yn($display_yn="")
    {
        $_display_yn = array(
            "1"=>"노출",
            "2"=>"숨김"

        );
        return $display_yn=="all" ? $_display_yn : $_display_yn[$display_yn]??"";
    }

    public function get_property_type($property_type="all")
    {
        // (apt : 아파트, officetel : 오피스텔, villa : 빌라/연립, house : 단독주택, land : 토지,
        // commercial : 상가, office : 사무실, factory : 공장, warehouse : 창고, knowledge : 지식산업센터,
        // building : 건물, motel : 숙박시설, etc : 기타)
        $_property_type = array(
            "apt"=>"아파트",
            "officetel"=>"오피스텔",
            "villa"=>"빌라/연립",
            "house"=>"단독주택",
            "land"=>"토지",
            "commercial"=>"상가",
            "office"=>"사무실",
            "factory"=>"공장",
            "warehouse"=>"창고",
            "knowledge"=>"지식산업센터",
            "building"=>"건물",
            "motel"=>"생활숙박",
            "etc"=>"기타"
        );
        return $property_type=="all" ? $_property_type : $_property_type[$property_type]??"";

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
}