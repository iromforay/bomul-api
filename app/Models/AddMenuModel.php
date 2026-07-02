<?php

namespace App\Models;

use App\Entity\Add_menu_Entity;

class AddMenuModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"add_menu","add_menu_no");
        return $this;
    }

    public function sql_save(Add_menu_Entity $entity)
    {


        $osql = " INSERT INTO `$this->table` SET
                        `code`=%s,`key`=%s, `text`=%s,
                        `show`=%s,`img_dir`=%s,`img_name`=%s ,`write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getCode()), $this->db->escape( $entity->getKey()), $this->db->escape( $entity->getText()),
            $this->db->escape( $entity->getShow()), $this->db->escape( $entity->getImgDir()), $this->db->escape( $entity->getImgName())
        );
     //   echo $sql."\n";
        $boards_no =  $this->safeExecQuery($sql);
        return $boards_no;
    }

    public function sql_edit(Add_menu_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        ! is_null($entity->getCode())     ?    $set[] = "`code`=".      $this->db->escape($entity->getCode())       : "";
        ! is_null($entity->getKey())      ?    $set[] = "`key`=".       $this->db->escape($entity->getKey())        : "";
        ! is_null($entity->getShow())     ?    $set[] = "`show`=".      $this->db->escape($entity->getShow())       : "";
        ! is_null($entity->getText())     ?    $set[] = "`text`=".      $this->db->escape($entity->getText())       : "";
        ! is_null($entity->getImgDir())   ?    $set[] = "`img_dir`=".   $this->db->escape($entity->getImgDir())     : "";
        ! is_null($entity->getImgName())  ?    $set[] = "`img_name`=".  $this->db->escape($entity->getImgName())    : "";

        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getAddMenuNo());

        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }

    public function findbyCodeAndKey($code,$key)
    {
        $sql="SELECT * FROM `$this->table` WHERE `code`=".$this->db->escape($code)." AND `key` = ".$this->db->escape($key);
        $row = $this->getExecQuery($sql);

        if(isset($row[0])) {
            $data = $row[0];
        }else $data=array("");

        return $data;
    }

    public function findbyCodeandShow($code,$show)
    {
        $sql="SELECT * FROM `$this->table` WHERE `code`=".$this->db->escape($code)." AND `show`=".$this->db->escape($show);
        $row = $this->getExecQuery($sql);

        return $row;
    }
    public function findByCode_order_idx_asc_limitone($code)
    {

        $sql ="SELECT * FROM add_menu WHERE `show`='N' AND `code`=".$this->db->escape($code)." ORDER BY add_menu_no ASC LIMIT 1";

        $row = $this->getExecQuery($sql);
        if(isset($row[0])) $data = $row[0];
        else $data = $row;

        return $data;
    }

    public function findbyCode($code)
    {
        $sql="SELECT * FROM `$this->table` WHERE `code`=".$this->db->escape($code);
        $rows = $this->getExecQuery($sql);

        return $rows;
    }
}