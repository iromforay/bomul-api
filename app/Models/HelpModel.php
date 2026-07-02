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
}