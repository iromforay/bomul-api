<?php

namespace App\Models;

use App\Entity\PagerList_Entity;

class PagerListModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"pager_list","pager_no");
        /*$this->table="Boards";
        $this->primaryKey="boards_no";*/

        return $this;
    }

    public function sql_save(PagerList_Entity $entity)
    {


        $osql = " INSERT INTO `$this->table` SET
                        `title`=%s,`status` = %s,`code`=%s,
                          `style`=%s ,`reader`=%s, `write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getTitle()), $this->db->escape( $entity->getStatus()), $this->db->escape( $entity->getCode()),
            $this->db->escape( $entity->getStyle()),$this->db->escape( $entity->getReader())
        );

        $boards_no =  $this->safeExecQuery($sql);
        return $boards_no;
    }

    public function sql_edit(PagerList_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        $entity->getTitle()!=""             ?    $set[] = "`title`=".       $this->db->escape($entity->getTitle())  : "";
        $entity->getStatus()!=""            ?    $set[] = "`status`=".      $this->db->escape($entity->getStatus()) : "";
      //  $entity->getCode()!=""              ?    $set[] = "`code`=".        $this->db->escape($entity->getCode())   : "";
        $entity->getStyle()!=""             ?    $set[] = "`style`=".       $this->db->escape($entity->getStyle())  : "";
        $entity->getReader()!=""            ?    $set[] = "`reader`=".      $this->db->escape($entity->getReader()) : "";

        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getPagerNo());

        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }
    public function table_copy($seq_no, $bcode)
    {
        $sql = "INSERT INTO $this->table(`bname`,`bcode`,`header_title`,`header_upload`,`footer_title`,`footer_upload`,`style`,`writer`,`reader`,`lister`,`reply`)
                SELECT CONCAT(`bname`,'(copy)') as 'bname','$bcode' as 'bcode',`header_title`,`header_upload`,`footer_title`,`footer_upload`,`style`,`writer`,`reader`,`lister`,`reply` FROM $this->table WHERE `$this->primaryKey` = ".$seq_no;

        $boards_no =  $this->safeExecQuery($sql);
        return $boards_no;
    }

    public function getAll()
    {
        $sql = "SELECT code,title FROM ".$this->table;
        $rows = $this->getExecQuery($sql);
        return $rows;
    }
}