<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Service\Api\PostService;

class Notice extends BaseController
{
    private $log;

    public function __construct()
    {
        $this->set_code("notice");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());

        return $this;
    }


    public function getindex()
    {
        $this->set_title("공지&이벤트");
        $data = array();
        /*세팅 */

        $page = (int) ($this->request->getGet('page') ?? 1);
        $code = $this->request->getGet('code') ?? "all";
        if ($page < 1) $page = 1;
        $limit = 10; //10;
        $where = [];
    // 공지 사항외 코드 차단.
        if(in_array( $code,array("notice","event","all"))){ //공지,이벤트,전체일떄만
            $bcode = $code=="all"?"notice":$code; //
        }else{//그외는 무조건 공지로 고정
            $bcode = "notice";
        }

        $service = new PostService($this->log);
        $rows = $service->set_code($bcode)->get_list($page,$limit,$where); //리스트 가져오기
        $total = $rows['total'];
        $paging = $this->get_paging($total,$page,$limit);

        $data = array(
            'active'=> $this->get_code(),
            'rows' =>$rows['rows'],
            'paging'=> $paging
        );

        $this->view("",'Notice/index',$data);
    }

    public function getview($board_no)
    {
        $board_no = (int) $board_no;

        $service = new PostService($this->log);
        $rows = $service->getbyid($board_no);
        $row = $rows[0] ?? null;

        if (!$row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $service->viewcount($board_no);

        $this->set_title($row['board_title']);

        $data = array(
            'active'=> $this->get_code(),
            'row'   => $row
        );

        $this->view("",'Notice/view',$data);
    }
}