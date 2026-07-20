<?php

namespace App\Controllers\API;

use App\Controllers\API\BaseController;
use App\Libraries\AT_Result;
use App\Service\PageService;
use App\Service\PropertyService;

class Property extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->log = $this->getMonoLog("property_api");
        $this->service = new PropertyService($this->log,$this->session_id);
        return $this;
    }

    public function getindex()
    {
        $this->getList();
    }
    public function getList()
    {
        $page =  $this->request->getVar('page')??1;
        $limit =  $this->request->getVar('limit')??10;
        $debug = $this->request->getVar('debug')??0;

        $where[] = "`status`=1"; //조건은 배열로 전달

        $rows = $this->service->get_list($page,$limit,$where);

        $total = $rows['total'];

        $pager_links = $this->calPaging($page,$total,10, $limit);
        // dd($pager_links);
        $data = array(
            'rows' =>$rows['rows'],
            //     "boards"=>$boards,
            'pager_links'=> $pager_links
        );

        $oResult = AT_Result::toSuccessData("",$data);

        $this->set_debug($debug)->view($oResult);
        exit;
    }


    public function getDetail()
    {
        $property_no = $this->request->getVar("property_no");

        $debug  = $this->request->getVar("debug");
        if($property_no!="" and $property_no>0) {
            $datas = $this->service->getbyid($property_no);
            $data = $datas[0];

            if($data['status']=="N"){
                //삭제된 게시물
                $oResult = AT_Result::toFailData("삭제된 매물 입니다.");
            }else {
               // $this->service->viewcount($property_no);

                $oResult = AT_Result::toSuccessData("", $data);
            }
        }else {
            $oResult = AT_Result::toFailData("존재하지 않는 매물 입니다.");
        }
        $this->set_debug($debug)->view($oResult);
    }

    public function optionsList()
    {
        return $this;
    }
    public function optionsDetail()
    {
        return $this;
    }
}