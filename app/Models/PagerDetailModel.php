<?php

namespace App\Models;

use App\Entity\PagerDetail_Entity;

class PagerDetailModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"pager_detail","pager_dno");
        /*$this->table="Boards";
        $this->primaryKey="boards_no";*/

        return $this;
    }

    public function sql_save(PagerDetail_Entity $entity)
    {


        $osql = " INSERT INTO `$this->table` SET
                        `pager_no`=%s,`body` = %s,`sorty`=%s,
                          `status`=%s ,`write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getPagerNo()), $this->db->escape( $entity->getBody()), $this->db->escape( $entity->getSorty()),
                 $this->db->escape( $entity->getStatus())
        );

        $pager_dno =  $this->safeExecQuery($sql);
        return $pager_dno;
    }

    public function sql_edit(PagerDetail_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        $entity->getPagerNo()!=""           ?    $set[] = "`pager_no`=".    $this->db->escape($entity->getPagerNo())    : "";
        $entity->getBody()!=""              ?    $set[] = "`body`=".        $this->db->escape($entity->getBody())       : "";
        $entity->getSorty()!=""             ?    $set[] = "`sorty`=".       $this->db->escape($entity->getSorty())      : "";
        $entity->getStatus()!=""            ?    $set[] = "`status`=".      $this->db->escape($entity->getStatus())     : "";
        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getPagerDno());
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

    public function get_by_pager_no($pager_no)
    {
        $sql="SELECT * FROM $this->table WHERE `pager_no` = ".$pager_no;
        $rows = $this->getExecQuery($sql);

        return $rows;
    }
}