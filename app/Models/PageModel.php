<?php

namespace App\Models;

class PageModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"pager_detail","pager_dno");
        return $this;
    }

    public function findbycode($code)
    {
        $sql="SELECT * FROM pager_list WHERE status = 'T' AND code = '".$code."'";
        $rows = $this->getExecQuery($sql);

        return $rows[0];
    }
    public function findBypager_no($seq_no)
    {
        $sql="SELECT * FROM pager_detail WHERE status='T' AND pager_no = '".$seq_no."' ORDER BY `sorty` ASC";
        $rows = $this->getExecQuery($sql);

        return $rows;
    }

}