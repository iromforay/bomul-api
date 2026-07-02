<?php

namespace App\Models;

use App\Entity\Post_Entity;
class PostModel extends CI_Model
{
    private $code;
    public function __construct($dbs)
    {
        parent::__construct($dbs,"post","post_no");

        return $this;
    }

    /**
     * @return mixed
     */
    public function setCode($code="")
    {
        $this->code = $code;
        return $this;
    }

    public function sql_save(Post_Entity $entity)
    {
        $osql = " INSERT INTO `$this->table` SET
                         `bcode` = %s,`user_id`=%s,`name`=%s, 
                         `password`=%s,`secret`=%s,`tel` = %s,
                         `subject`=%s,`content`=%s,`file_dir`=%s,`file_name`=%s,
                         `add01`=%s, `add02`=%s,`orgin_name`=%s,`write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getBcode()), $this->db->escape( $entity->getUserId()), $this->db->escape( $entity->getName()),
            $this->db->escape( $entity->getPassword()), $this->db->escape( $entity->getSecret()), $this->db->escape( $entity->getTel()),
            $this->db->escape( $entity->getSubject()), $this->db->escape( $entity->getContent()), $this->db->escape( $entity->getFileDir()), $this->db->escape( $entity->getFileName()),
            $this->db->escape( $entity->getAdd01()), $this->db->escape( $entity->getAdd02()), $this->db->escape( $entity->getOrginName())
        );

        $post_no =  $this->safeExecQuery($sql);
        return $post_no;
    }

    public function sql_edit(Post_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        ! is_null($entity->getName())              ?    $set[] = "`name`=".              $this->db->escape($entity->getName())           : "";
        ! is_null($entity->getSecret())            ?    $set[] = "`secret`=".            $this->db->escape($entity->getSecret())         : "";
        ! is_null($entity->getTel())               ?    $set[] = "`tel`=".               $this->db->escape($entity->getTel())            : "";
        ! is_null($entity->getSubject())           ?    $set[] = "`subject`=".           $this->db->escape($entity->getSubject())        : "";
        ! is_null($entity->getContent())           ?    $set[] = "`content`=".           $this->db->escape($entity->getContent())        : "";
        ! is_null($entity->getFileDir())           ?    $set[] = "`file_dir`=".          $this->db->escape($entity->getFileDir())        : "";
        ! is_null($entity->getFileName())          ?    $set[] = "`file_name`=".         $this->db->escape($entity->getFileName())       : "";
        ! is_null($entity->getOrginName())         ?    $set[] = "`orgin_name`=".        $this->db->escape($entity->getOrginName())      : "";
        ! is_null($entity->getAdd01())             ?    $set[] = "`add01`=".             $this->db->escape($entity->getAdd01())          : "";
        ! is_null($entity->getAdd02())             ?    $set[] = "`add02`=".             $this->db->escape($entity->getAdd02())          : "";

        $set[] = "`modify_date`= now()";
        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getPostNo());
//echo $sql."\n";
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }
    public function sql_del(Post_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET status=0 ";
        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getPostNo());
//echo $sql."\n";
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }
    public function viewcount($entity)
    {
        $sql  = "UPDATE `$this->table` SET view_count = view_count+1 WHERE `$this->primaryKey` = ". $this->db->escape($entity->getPostNo());
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }

    }

    public function get_where($where=array())
    {

        if(is_array($this->code)){
            $_where[0] = "`bcode` IN ('".implode("','",$this->code)."')";
        }elseif($this->code !="")
            $_where[0] = "`bcode` = '".$this->code."'";
        else $_where=array();

        if(is_array($where) and sizeof($where)>0){
            $_where = array_merge($_where,$where);
        }else if(isset($where[0]) and $where[0]!=""){
            $_where = array_merge($_where,$where);
        }

        $where_ = (count($_where)>0 ? "WHERE " .implode(" AND ",$_where) : (isset($_where[0]) ?$_where[0] : ""));

        return $where_;
    }
    public function get_list($start=1,$limit=9,$where=array())
    {
        $where_ = $this->get_where($where);
        $sql = "SELECT * FROM $this->table $where_  ORDER BY $this->primaryKey desc  LIMIT $start,$limit ";

        $rows = $this->getExecQuery($sql);
        return $rows;
    }
    public function getbyCodeOrderBy($bcode,$limit,$order,$sort)
    {
        $sql = "SELECT * FROM $this->table WHERE bcode='".$bcode."' AND `status`=1  ORDER BY $order $sort  LIMIT $limit ";
        $rows = $this->getExecQuery($sql);
        return $rows;
    }
    public function get_count($where = array())
    {
        $where_ = $this->get_where($where);

        $sql = "SELECT count(*) as 'cnt' FROM ".$this->table." ".$where_;

        $rows = $this->getExecQuery($sql);

        return $rows[0]['cnt'];
    }
    public function board_total_count()
    {
        $sql="SELECT bcode,COUNT(*) as 'total_count' FROM post WHERE `status` =1 GROUP BY bcode";
        $rows = $this->getExecQuery($sql);
        return $rows;
    }
    public function get_all_board()
    {
       $rows = array("notice","around","charge","review","vod");
        return $rows;
    }

}
/*
CREATE TABLE `post` (
  `post_no` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bcode` VARCHAR(64) DEFAULT NULL COMMENT '게시판 코드',
  `user_id` VARCHAR(20) DEFAULT NULL COMMENT '회원id',
  `name` VARCHAR(255) DEFAULT NULL COMMENT '등록자이름(비회원)',
  `password` VARCHAR(255) DEFAULT NULL COMMENT '패스워드',
  `secret` CHAR(1) DEFAULT 'F' COMMENT '비밀글여부(T:비밀글,F:일반글)',
  `tel` VARCHAR(255) DEFAULT NULL COMMENT '연락처',
  `status` VARCHAR(4) DEFAULT '0' COMMENT '게시물상태(0: 노출,1:차단)',
  `subject` VARCHAR(255) DEFAULT NULL COMMENT '제목',
  `content` TEXT COMMENT '내용',
  `file` VARCHAR(255) DEFAULT NULL COMMENT '첨부파일',
  `write_date` DATETIME DEFAULT NULL COMMENT '등록일',
  `modify_date` DATETIME DEFAULT NULL COMMENT '수정일',
  `add01` VARCHAR(64) DEFAULT NULL COMMENT '추가1',
  `add02` VARCHAR(64) DEFAULT NULL COMMENT '추가2',
  PRIMARY KEY (`post_no`)
)
 */

