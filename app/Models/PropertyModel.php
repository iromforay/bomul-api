<?php

namespace App\Models;

use App\Entity\Property_Entity;
use App\Models\CI_Model;

class PropertyModel extends CI_Model
{
    private $code;
    public function __construct($dbs)
    {
        parent::__construct($dbs,"property","property_no");

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
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
        // $this->db->query($sql, $entity->toRawArray());
    }

    public function modify(Property_Entity $entity)
    {

        $value = $entity->toSqlSet($this,$this->primaryKey);

        $sql = "
            UPDATE ".$this->table." SET
            $value            
            WHERE ".$this->primaryKey." = ".$entity->property_no;
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
        exit;
    }

    public function get_direction($direction="")
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

        return $direction=="" ? $_direction : $_direction[$direction]??"";
    }
    public function get_heating_fuel($heating_fuel="")
    {
        $_heating_fuel = array(
            "1"=>"도시가스",
            "2"=>"전기",
            "3"=>"LPG",
            "4"=>"기름",
            "9"=>"기타"
        );
        return $heating_fuel=="" ? $_heating_fuel : $_heating_fuel[$heating_fuel]??"";
    }
    public function get_heating_type($heating_type="")
    {
        $_heating_type = array(
            "1"=>"개별난방",
            "2"=>"중앙난방",
            "3"=>"지역난방"
        );
        return $heating_type=="" ? $_heating_type : $_heating_type[$heating_type]??"";
    }
    public function get_status($status="")
    {
        $_status = array(
            "1"=>"거래가능",
            "2"=>"거래중",
            "3"=>"거래완료",
            "4"=>"거래보류",
            "5"=>"비공개"
        );
        return $status=="" ? $_status : $_status[$status]??"";
    }
    public function get_display_yn($display_yn="")
    {
        $_display_yn = array(
            "1"=>"노출",
            "2"=>"숨김"

        );
        return $display_yn=="" ? $_display_yn : $_display_yn[$display_yn]??"";
    }

}