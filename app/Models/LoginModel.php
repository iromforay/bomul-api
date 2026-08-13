<?php

namespace App\Models;

use App\Entity\Login_SessionEntity;
use App\Libraries\AT_Result;

class LoginModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"login_session","login_session_no");
        return $this;

    }
    public function sql_save(Login_SessionEntity $entity)
    {
       $row = $this->get_session_id($entity->getSessionId());
       if(isset($row['login_no']) and $row['login_no']>0){
           $entity->setLoginNo($row['login_no']);
           return  $this->sql_edit($entity);
           exit;
       }

        $osql = " INSERT INTO `$this->table` SET
                        `session_id`=%s,`data`=%s, `fid`=%s,
                     `write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getSessionId()), $this->db->escape( $entity->getData()), $this->db->escape( $entity->getFid())
        );
   //        echo $sql."\n";
        $boards_no =  $this->safeExecQuery($sql);
        return $boards_no;
    }
    public function sql_edit(Login_SessionEntity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        ! is_null($entity->getSessionId())         ?    $set[] = "`session_id`=".        $this->db->escape($entity->getSessionId())     : "";
        ! is_null($entity->getData())              ?    $set[] = "`data`=".              $this->db->escape($entity->getData())          : "";
        ! is_null($entity->getFid())               ?    $set[] = "`fid`=".               $this->db->escape($entity->getFid())           : "";
        ! is_null($entity->getStatus())            ?    $set[] = "`status`=".            $this->db->escape($entity->getStatus())        : "";

        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getLoginNo());
//echo $sql."\n";
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }

    }
    // write_date 기준 SESSION_EXPIRE_DAYS(.env, 기본 7일)가 지난 세션은 만료 처리.
    // status 컬럼은 로그아웃 등에서 갱신되지 않아 사실상 의미가 없어 조건에서 제외한다.
    public function get_session_id($session_id)
    {
        $days = (int) env('SESSION_EXPIRE_DAYS', 7);
        $sql = "SELECT * FROM `$this->table` WHERE session_id = ".$this->db->escape($session_id)
            ." AND write_date > (NOW() - INTERVAL $days DAY)";
        $rows = $this->getExecQuery($sql);

        if(isset($rows[0]))
            return $rows[0];
        else return null;

    }

    // Cron\SessionCleanup이 주기적으로 호출 - write_date 기준 만료된 row를 물리 삭제.
    public function deleteExpired($days)
    {
        $days = (int) $days;
        $sql = "DELETE FROM `$this->table` WHERE write_date < (NOW() - INTERVAL $days DAY)";
        $this->safeExecQuery($sql);
        return $this->getAffectedRows();
    }
    public function get_fid_by_session_id($session_id)
    {
        $sql = "SELECT fid FROM `$this->table` WHERE session_id = ".$this->db->escape($session_id);
        $rows = $this->getExecQuery($sql);

        if(isset($rows[0]['fid']))
            return $rows[0]['fid'];
        else return null;

    }

    public function adminLogin($user_id)
    {
        $sql = "SELECT * FROM bomul.`member` WHERE user_id = ".$this->db->escape($user_id);
        $rows = $this->getExecQuery($sql);

        if(isset($rows[0]['member_no']) && $rows[0]['member_no']>0){
            return AT_Result::toSuccessData("",$rows[0]);
        }else{
            return AT_Result::toFailData("존재하지 않는 ID 입니다.");
        }

    }
}