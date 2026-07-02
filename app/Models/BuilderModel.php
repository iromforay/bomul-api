<?php

namespace App\Models;

use App\Entity\Build_Entity;

class BuilderModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"build","build_no");
        /*$this->table="Boards";
        $this->primaryKey="boards_no";*/

        return $this;
    }

    public function sql_edit(Build_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        $entity->getType()!=""           ?    $set[] = "`type`=".           $this->db->escape($entity->getType())       : "";
        $entity->getUses()!=""           ?    $set[] = "`uses`=".           $this->db->escape($entity->getUses())       : "";
        $entity->getText()!=""           ?    $set[] = "`text`=".           $this->db->escape($entity->getText())       : "";
        $entity->getColor()!=""          ?    $set[] = "`color`=".          $this->db->escape($entity->getColor())      : "";
        $entity->getBgColor()!=""        ?    $set[] = "`bg_color`=".       $this->db->escape($entity->getBgColor())    : "";
        $entity->getImgUrl()!=""         ?    $set[] = "`img_url`=".        $this->db->escape($entity->getImgUrl())     : "";
        $entity->getFileName()!=""       ?    $set[] = "`file_name`=".      $this->db->escape($entity->getFileName())   : "";
        $entity->getLink()!=""           ?    $set[] = "`link`=".           $this->db->escape($entity->getLink())       : "";
        $entity->getLinkType()!=""       ?    $set[] = "`link_type`=".      $this->db->escape($entity->getLinkType())   : "";
        $entity->getShowCount()!=""      ?   $set[] = "`show_count`=".      $this->db->escape($entity->getShowCount())   : "";

        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getBuildNo());
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }
    //frontend
    public function findbyAll()
    {
        $sql  = "SELECT * FROM  `$this->table` LIMIT 12";
        $rows = $this->getExecQuery($sql);
        return $rows;
    }
}
/*
 * build
 *
 * build_no
 * build_title
 * type (text,image) //타입
 * img_url  //이미지 url
 * bg_color  //백그라운드 색상
 * color   //폰트색상
 * write_date
 *
 * */