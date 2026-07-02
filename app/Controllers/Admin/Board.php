<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\PostService;

class Board extends BaseController
{
    private $log;
    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("board_api");
        $this->service = new PostService($this->log,$this->session_id);

        $this->set_debug(0);
        return $this;
    }

    public function Postboard_del()
    {
        $this->log->info("[REV] board board_del : ".json_encode($_GET,JSON_UNESCAPED_UNICODE));
        $post_no = $this->request->getVar('board_no');

        if($post_no!="" and $post_no>0) {
            $data = $this->service->getbyid($post_no);
            $oResult = $this->service->del($post_no);
        }else {
            $oResult = AT_Result::toFailData("삭제할 게시물이 없습니다.");
        }

        $this->log->info("[RES] board getdetail > result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);
    }

    public function getlist()
    {

        $this->log->info("[REV] board listing : ".json_encode($_GET,JSON_UNESCAPED_UNICODE));
        $code = $this->request->getGet('code')??"";
        $page =  $this->request->getGet('page')??1;
        $limit =  $this->request->getGet('limit')??10;
        $debug = $this->request->getGet('debug')??0;
        $search['keyword'] = $this->request->getGet('keyword')??""; //title,content, writer
        $search['txt'] = $this->request->getGet('search_txt')??"";

        $boards = $this->service->board_total_count(); //게시판별 전체 게시물수.
            //$info = $this->get_board($code);
        $where[] = "`status`=1"; //조건은 배열로 전달


        $rows = $this->service->setcode($code)->get_list($page,$limit,$search); //리스트 가져오기


        $total = $rows['total'];

        $pager_links = $this->calPaging($page,$total,10, $limit);
            // dd($pager_links);
        $data = array(
            'rows' =>$rows['rows'],
            "boards"=>$boards,
            'pager'=> $pager_links
        );

        $oResult = AT_Result::toSuccessData("",$data);
        $this->log->info("[RES] board listing > result : ".$oResult['SUCCESS']?"T":"F");
        $this->set_debug($debug)->view($oResult);
    }

    public function getDetail()
    {
        $this->log->info("[REV] board getdetail : ".json_encode($_GET,JSON_UNESCAPED_UNICODE));
        $post_no = $this->request->getGet('board_no');
        $debug  = $this->request->getVar("debug");
        if($post_no!="" and $post_no>0) {
            $data = $this->service->getbyid($post_no);
            $data = $this->service->after_process($data);
            $oResult = AT_Result::toSuccessData("",$data);
        }else {
            $oResult = AT_Result::toFailData();
        }
        $this->log->info("[RES] board getdetail > result : ".$oResult['SUCCESS']?"T":"F");
        $this->set_debug($debug)->view($oResult);
    }
    public function postsave()
    {
        $this->log->info("[REV] board save : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        $file=null;

        $data['bcode'] = $this->request->getVar('code')??"";

        $data['title'] = $this->request->getVar('board_title')??"";
        $data['name'] = $this->request->getVar('board_writer')??"";
        $data['content'] = $this->request->getVar('board_content')??"";


        if($data['bcode'] =="review"){
            $data['score'] = $this->request->getVar('score')??"";
        }else if($data['bcode']=="vod"){
            if(isset($_FILES['file'])) {
                $file = $_FILES['file'];
            }
            $data['movie_url'] = $this->request->getVar('movie_url')??"";
        }

        if($data['bcode']==""){
            $this->view(AT_Result::toFailData("게시판 코드가 없습니다."));
        }
        $oResult = $this->service->save($data,$file);
        $this->log->info("[RES] board save > result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);
    }

    public function postmodify()
    {
        $this->log->info("[REV] board edit : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        $file=null;

        $data['post_no'] = $this->request->getVar('board_no');

        $data['title'] = $this->request->getVar('board_title')??"";
        $data['name'] = $this->request->getVar('board_writer')??"";
        $data['content'] = $this->request->getVar('board_content')??"";

        $data['movie_url'] = $this->request->getVar('movie_url')??"";
        $data['score']      = $this->request->getVar('score')??"";
        $data['score'] = $this->request->getVar('score')??"";

        if(isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }
        $data['movie_url'] = $this->request->getVar('movie_url')??"";
        $data['file_del'] = $this->request->getVar('file_del')??"N";

        $oResult = $this->service->edit($data,$file);
        $this->log->info("[RES] board edit > result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);
    }

    /*
* $page_num : 현재페이지 (기본 :1)
* $total_items : 전체게시물수
* $block_size : 페이지 블럭사이즈(기본:10)
* $limit : 페이지당 게시물수 (기본:10)
* */
    public function calPaging($page_num=1, $total_items, $block_size = 10, $limit = 5)
    {

        $items_per_page=$limit; //페이지당 게시물수
        $total_pages=0; //젠체 페이지수

        $total_pages = ceil($total_items / $items_per_page);

        // 페이지 블럭의 시작 번호와 끝 번호 계산
        $block_start = max(1, $page_num - $block_size);
        $block_end = min($total_pages, $page_num + $block_size);

        $item_num_start = $total_items - ($page_num-1)*$limit ;
        $paging = array(
            'item_num_start' =>$item_num_start,
            'now_page' =>$page_num,
            'total_items' => $total_items,
            'total_page'=>$total_pages,
            'page_per_items' =>$items_per_page,
            'block_start' => $block_start,
            'block_end'=>$block_end
        );
        return $paging;
    }

    /*preflight*/
    public function optionsBoard_del()
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
    public function optionsSave()
    {
        return $this;
    }
    public function optionsModify()
    {
        return $this;
    }
}