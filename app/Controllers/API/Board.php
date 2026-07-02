<?php

namespace App\Controllers\API;

use App\Libraries\AT_Result;
use App\Service\Api\PostService;

class Board extends BaseController
{
    private $service;
    private $log;


    public function __construct()
    {
        parent::__construct();
        $this->log = $this->getMonoLog("api");
        $this->service = new PostService($this->log);
        return $this;
    }
    public function getindex()
    {
        $this->getlist();
    }

    public function getlist()
    {

        $this->log->info("get List > data : ".json_encode($_GET,JSON_UNESCAPED_UNICODE));
        $bcode = $this->request->getVar('code')??"";
        $page =  $this->request->getVar('page')??1;
        $limit =  $this->request->getVar('limit')??10;
        $debug = $this->request->getVar('debug')??0;
        if($bcode=="") {
            $oResult = AT_Result::toFailData("게시판 코드가 없습니다.", "B01");
        }else{

            //$info = $this->get_board($bcode);
            $where[] = "`status`=1"; //조건은 배열로 전달
            $rows = $this->service->set_code($bcode)->get_list($page,$limit,$where); //리스트 가져오기

           // $boards = $this->service->board_total_count(); //게시판별 전체 게시물수.
            $total = $rows['total'];

            $pager_links = $this->calPaging($page,$total,10, $limit);
            // dd($pager_links);
            $data = array(
                'rows' =>$rows['rows'],
           //     "boards"=>$boards,
                'pager_links'=> $pager_links
            );

            $oResult = AT_Result::toSuccessData("",$data);
        }


        $this->set_debug($debug)->view($oResult);

    }

    public function getdetail()
    {
        $post_no = $this->request->getVar("board_no");
        $debug  = $this->request->getVar("debug");
        if($post_no!="" and $post_no>0) {
            $datas = $this->service->getbyid($post_no);
            $data = $datas[0];

            if($data['board_status']=="N"){
                //삭제된 게시물
                $oResult = AT_Result::toFailData("삭제된 게시물 입니다.");
            }else {
                $this->service->viewcount($post_no);

                $oResult = AT_Result::toSuccessData("", $data);
            }
        }else {
            $oResult = AT_Result::toFailData("게시물번호가 없습니다.");
        }
        $this->set_debug($debug)->view($oResult);
    }
    /*preflight*/
    public function optionsIndex()
    {
        return $this;
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