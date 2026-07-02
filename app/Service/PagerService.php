<?php

namespace App\Service;

use App\Entity\PagerList_Entity;
use App\Entity\PagerDetail_Entity;

class PagerService extends CI_Service
{
    private $listmodel;

    public function __construct()
    {
        parent::__construct();

        $this->listmodel = model('App\Models\PagerListModel',true,$this->db);
        $this->detailmodel = model('App\Models\PagerDetailModel',true,$this->db);
    }

    public function save($data)
    {

        $pager = new PagerList_Entity();

        $pager->setTitle($data['title']);
        $pager->setStyle($data['style']);
        $pager->setCode($data['code']);
        $pager->setReader($data['reader']);

        $pager_no = $this->listmodel->sql_save($pager); //리스트 등록하고
        if($pager_no>0){
            return $pager_no;
        }
        return false;
    }

    public function edit($data)
    {
        $pager = new PagerList_Entity();

        $pager->setPagerNo($data['pager_no']);
        $pager->setTitle($data['title']);
        $pager->setStatus($data['status']);
        $pager->setStyle($data['style']);
        $pager->setReader($data['reader']);

        $this->listmodel->sql_edit($pager); //리스트 수정하고
        $result = $this->set_detail($data['detail']);

        if(in_array("F",$result)){//실패껀이 존재
            if(in_array("T",$result)) {//성공도 존재
                return true;
            }else{//실패만 존재
                return false;
            }
        }else{
            if(in_array("T",$result)) {//모두 성공
                return true;
            }
        }
        return $this;
    }
    public function set_detail($data)
    {
        $result = array();
        foreach($data as $k => $v){
            $detail = new PagerDetail_Entity();
            $detail->setPagerDno($v['seq_no']);
            $detail->setPagerNo($v['pager_no']);
            $detail->setSorty($v['sorty']);
            $detail->setBody($v['body']);

            if($detail->getPagerDno()>0) $re = $this->detailmodel->sql_edit($detail);
            else $re = $this->detailmodel->sql_save($detail);
            $detail = null;
            $result[$k]=$re ? "T" : "F";
        }
        return $result;
    }

    public function get_list($page,$perPage)
    {
        $start = ($page -1)*$perPage;
        $total_count  = $this->listmodel->get_count();
        $row = $this->listmodel->get_list($start,$perPage);
        $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);

    }

    public function get_by_id($idx)
    {
        $row['list'] = $this->listmodel->findbyid($idx)[0];
        $row['detail'] =  $this->detailmodel->get_by_pager_no($idx);
        return $row;
    }
    public function makecode()
    {
        $code = date("Ymdhis");
        $time = microtime();

        $code = "p".substr(md5($code),5,4).substr(md5($time),-5);
        return $code;
    }
    public function after_process($data)
    {
        foreach ($data as $k=>$val)
        {
            $val['reader_text'] =  strtolower($val['reader'])=="member" ? "회원만" :"모두" ;

            $data[$k]=$val;
        }
        return $data;
    }
}