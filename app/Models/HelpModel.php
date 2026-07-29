<?php

namespace App\Models;

class HelpModel  extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"","");
        return $this;
    }

    public function get_info_by_fid($fid)
    {

        $sql  = "SELECT * FROM bomul.member WHERE fid=".$this->db->escape($fid);

        $rows = $this->getExecQuery($sql);
        return $rows;
    }

    public function getSession($session_id)
    {
        $sql="SELECT * FROM bomul.login_session WHERE session_id=".$this->db->escape($session_id);

        $rows = $this->getExecQuery($sql);
        return $rows[0]??array();
    }

    public function get_session_status($session_id)
    {
        $sql="SELECT * FROM bomul.member WHERE fid=".$this->db->escape($session_id);
        $rows = $this->getExecQuery($sql);
        return $rows[0]??array();
        exit;
    }

    // 새벽 배치(app/batch/analy.php)가 순회할 대상 - service='Y'인(서비스 이용중인) 중개사의 fid 전체.
    // BasicService::352에서 로그인 게이트로 쓰는 것과 동일한 컬럼/조건.
    public function get_active_fids()
    {
        $sql = "SELECT fid FROM bomul.member WHERE service = 'Y'";
        $rows = $this->getExecQuery($sql);
        return array_column($rows, 'fid');
    }
}