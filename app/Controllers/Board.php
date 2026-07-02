<?php

namespace App\Controllers;

use App\Libraries\AT_Result;
use App\Service\BoardService;
use App\Service\Api\PostService;

class Board extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new PostService();
        return $this;
    }
    public function getindex()
    {
        $bcode = $this->request->getVar('code')??"";
        $debug = $this->request->getVar('debug')??0;
        if($bcode=="") {
            $oResult = AT_Result::toFailData("게시판 코드가 없습니다.", "B01");
        }else{
            $info = $this->get_board($bcode);
            $data = array(
                'board_info'=>$info['board_info'],
                'permission'=>$info['permission']
            );

            $oResult = AT_Result::toSuccessData("",$data);
        }


        $this->set_debug($debug)->view($oResult);
    }
    public function getlist()
    {
        $bcode = $this->request->getVar('code')??"";
        $page =  $this->request->getVar('page')??1;
        $limit =  $this->request->getVar('limit')??10;
        $debug = $this->request->getVar('debug')??0;
        if($bcode=="") {
            $oResult = AT_Result::toFailData("게시판 코드가 없습니다.", "B01");
        }else{

            $info = $this->get_board($bcode);
            $where[] = "`status`=1"; //조건은 배열로 전달
            $rows = $this->service->set_code($bcode)->get_list($page,$limit,$where); //리스트 가져오기


            $total = $rows['total'];

            $pager_links = $this->calPaging($page,$total,10, $limit);
           // dd($pager_links);
            $data = array(
                'rows' =>$rows['rows'],
                'pager_links'=> $pager_links,
                'board_info'=>$info['board_info'],
                'permission'=>$info['permission']
            );

            $oResult = AT_Result::toSuccessData("",$data);
        }


        $this->set_debug($debug)->view($oResult);

    }
    public function getdata()
    {
        $post_no = $this->request->getVar('post_no');
        $debug = $this->request->getVar('debug')??0;
        $password = $this->request->getVar('password');
        $mode =  $this->request->getVar('mode');
        $row = $this->service->getbyid($post_no);

        $data['row'] = $row = $row[0];

        $bcode = $row['bcode'];
        //게시판 로딩
        $info = $this->get_board($bcode);
        $data = array_merge($data,$info);

        $oResult = $this->service->auth($data, $password);

        if($oResult['SUCCESS'] == 'TRUE'){
            $oResult['RECORD'] = $data;
        }
        $this->set_debug($debug)->view($oResult);
    }


    public function postwrite()
    {

    }
    public function postedit()
    {

    }
    public function postdel()
    {

    }

    private function get_board($bcode)
    {
        $bservice = new BoardService();
        $temp = $bservice->get_board($bcode);
        $data['board_info'] = $temp['boardFrame'];
        $data['permission'] = $temp['acter'];
        return $data;
    }
}