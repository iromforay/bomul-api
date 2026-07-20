<?php

namespace App\Models;

use App\Entity\FunctionMenu_Entity;

class FunctionMenuModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"function_menu","function_menu_no");
        return $this;
    }

    public function sql_edit(FunctionMenu_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        ! is_null($entity->getFunctionMenuKey())      ?    $set[] = "`function_menu_key`=".       $this->db->escape($entity->getFunctionMenuKey())        : "";
        ! is_null($entity->getFunctionMenuValue())    ?    $set[] = "`function_menu_value`=".      $this->db->escape($entity->getFunctionMenuValue())     : "";


        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getFunctionMenuNo());

        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }

    public function findbyKey($key)
    {
        $sql="SELECT * FROM `$this->table` WHERE  `function_menu_key` = ".$this->db->escape($key);
        $row = $this->getExecQuery($sql);

        if(isset($row[0])) {
            $data = $row[0];
        }else $data=array("");

        return $data;
    }

    public function getdata()
    {
        $sql="SELECT * FROM `$this->table` ";
        $rows = $this->getExecQuery($sql);
        return $rows;
    }
}