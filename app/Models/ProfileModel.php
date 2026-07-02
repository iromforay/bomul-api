<?php

namespace App\Models;

use App\Entity\Profile_Entity;

class ProfileModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"profile","profile_no");
        return $this;
    }

    public function sql_edit(Profile_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        ! is_null($entity->getNickName())              ?    $set[] = "`nick_name`=".              $this->db->escape($entity->getNickName())           : "";
        ! is_null($entity->getImgDir())                ?    $set[] = "`img_dir`=".                $this->db->escape($entity->getImgDir())            : "";
        ! is_null($entity->getImgName())               ?    $set[] = "`img_name`=".               $this->db->escape($entity->getImgName())           : "";

        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getProfileNo());
//echo $sql."\n";
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }


}