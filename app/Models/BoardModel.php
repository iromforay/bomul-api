<?php

namespace App\Models;

use App\Entity\Board_Entity;

class BoardModel extends CI_Model
{/*
    protected  $db;
    protected $table;
    protected $primaryKey;*/

    public function __construct($dbs)
    {
        parent::__construct($dbs,"boards","boards_no");
        /*$this->table="Boards";
        $this->primaryKey="boards_no";*/

        return $this;
    }

    public function sql_save(Board_Entity $entity)
    {


            $osql = " INSERT INTO `$this->table` SET
                        `bname`=%s,`bcode` = %s,`header_title`=%s,
                          `footer_title`=%s, `style`=%s,`writer`=%s,
                          `reader` = %s,`lister`=%s,`reply`=%s,`secret`=%s ,`write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getBname()), $this->db->escape( $entity->getBcode()), $this->db->escape( $entity->getHeaderTitle()),
              $this->db->escape( $entity->getFooterTitle()), $this->db->escape( $entity->getStyle()), $this->db->escape( $entity->getWriter()),
              $this->db->escape( $entity->getReader()), $this->db->escape( $entity->getLister()), $this->db->escape( $entity->getReply()), $this->db->escape( $entity->getSecret())
        );

        $boards_no =  $this->safeExecQuery($sql);
        return $boards_no;
    }

    public function sql_edit(Board_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        $entity->getBname()!=""             ?    $set[] = "`bname`=".           $this->db->escape($entity->getBname())       : "";
        $entity->getStyle()!=""             ?    $set[] = "`style`=".           $this->db->escape($entity->getStyle())       : "";
        $entity->getLister()!=""            ?    $set[] = "`lister`=".          $this->db->escape($entity->getLister())      : "";
        $entity->getWriter()!=""            ?    $set[] = "`writer`=".          $this->db->escape($entity->getWriter())      : "";
        $entity->getReader()!=""            ?    $set[] = "`reader`=".          $this->db->escape($entity->getReader())      : "";
        $entity->getReply()!=""             ?    $set[] = "`reply`=".           $this->db->escape($entity->getReply())       : "";
        $entity->getSecret()!=""            ?    $set[] = "`secret`=".          $this->db->escape($entity->getSecret())      : "";
        $entity->getHeaderTitle()!=""       ?    $set[] = "`header_title`=".    $this->db->escape($entity->getHeaderTitle()) : "";
        $entity->getFooterTitle()!=""       ?    $set[] = "`footer_title`=".    $this->db->escape($entity->getFooterTitle()) : "";

        $entity->getHeaderDir()!=""         ?    $set[] = "`header_dir`=".      $this->db->escape($entity->getHeaderDir())   : "";
        $entity->getHeaderUpload()!=""      ?    $set[] = "`header_upload`=".   $this->db->escape($entity->getHeaderUpload()): "";
        $entity->getFooterDir()!=""         ?    $set[] = "`footer_dir`=".      $this->db->escape($entity->getFooterDir())   : "";
        $entity->getFooterUpload()!=""      ?    $set[] = "`footer_upload`=".   $this->db->escape($entity->getFooterUpload()): "";


        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getBoardsNo());
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
        $sql = "SELECT bcode as 'code',bname as 'title' FROM ".$this->table;
        $rows = $this->getExecQuery($sql);
        return $rows;
    }
//##########################################
    public function get_by_code($code)
    {
        $sql = "SELECT * FROM $this->table WHERE bcode = ".$this->db->escape($code);
        $row = $this->getExecQuery($sql);
        return $row;
    }
}