<?php

namespace App\Models;

use CodeIgniter\Model;
class CI_Model extends Model
{
    protected $db;
    protected $table;
    protected $primaryKey;
    protected $query;
    /**
     * @param $db
     */
    public function __construct($db,$table,$primaryKey)
    {
        $this->db = $db;
        $this->table=$table;
        $this->primaryKey= $primaryKey;
       // dd($this->db);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getQuery()
    {
        return $this->query;
    }
    public function get_list($start=0,$limit=0)
    {
        $_limit="";
        if($limit>0 and $start>0){
            $_limit = " LIMIT $start,$limit ";
        }
        $sql = "SELECT * FROM $this->table ORDER BY $this->primaryKey desc ".$_limit ;

        $rows = $this->getExecQuery($sql);
        return $rows;
    }
    public function findbyid($seq_no)
    {
        $sql="SELECT * FROM $this->table WHERE $this->primaryKey = ".$seq_no;
        $rows = $this->getExecQuery($sql);

        return $rows;
    }
    public function get_count()
    {
        $sql = "SELECT count(*) as 'cnt' FROM ".$this->table;
        $rows = $this->getExecQuery($sql);
        return $rows[0]['cnt'];

    }

    public function safeExecQuery($sql)
    {
        $seq_no=0;
        $deny_query = array("DROP","ALTER","CREATE");
        $deny_str = "/".implode("|",$deny_query)."/";
        $SQL = strtoupper($sql);
        $this->query=$sql;
        preg_match_all ("$deny_str" ,trim($SQL),$result); //$sql

        if(count($result[0]) >1){ //사용 불가 쿼리문 포함.
            //   print_r($result);
            d("사용불가 쿼리문 포함"); d($result);
            return $this;
        }else{
            $this->db->transBegin();
            $this->db->query($sql);
            preg_match ("/INSERT/" ,trim($SQL),$insertquery); //$sql
            preg_match ("/UPDATE/" ,trim($SQL),$updatequery); //$sql
            preg_match ("/DELETE/" ,trim($SQL),$deletequery); //$sql

            $this->db->transCommit();

            if(isset($insertquery[0])){
                $this->db->insertID();
                return true;
            }else if(isset($updatequery[0]) or isset($deletequery)){
                return $this->db->transStatus();
            }

            if($this->db->transStatus() === false) {
                return false;
            }else return true;
        }
    }
    public function getExecQuery($sql)
    {
        $deny_query = array("INSERT","DELETE","UPDATE","ALTER","CREATE");
        $deny_str = "/".implode("|",$deny_query)."/";
        $SQL = strtoupper($sql);
        $this->query=$sql;
        preg_match_all ("$deny_str" ,trim($SQL),$result); //$sql

        if(count($result[0]) >1){ //사용 불가 쿼리문 포함.
            //   print_r($result);
            d("사용불가 쿼리문 포함"); d($result);
            return $this;
        }else{
            $query = $this->db->query($sql);
            $rows = $query->getResultArray();
            return $rows;
        }
    }
}