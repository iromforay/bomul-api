<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Service\Api\PostService;

class Charge extends BaseController
{
    private $log;
    public function __construct()
    {
        $this->set_code("charge");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());
        return $this;

    }

    public function getindex()
    {
        $this->set_title("이용요금");

        /*세팅 */
        $page = (int) ($this->request->getGet('page') ?? 1);

        if ($page < 1) $page = 1;
        $limit = 2; //10;
        $where = [];
        $bcode = $this->get_code();

        $service = new PostService($this->log);
        $rows = $service->set_code($bcode)->get_list($page,$limit,$where); //리스트 가져오기
        $total = $rows['total'];
        $paging = $this->get_paging($total,$page,$limit);

        $data = array(
            'active'=> $this->get_code(),
            'rows' =>$rows['rows'],
            'paging'=> $paging
        );
        $this->view("",'Charge/index',$data);
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

        $this->view("",'Charge/view',$data);
    }
}