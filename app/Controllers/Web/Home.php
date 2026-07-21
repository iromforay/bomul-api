<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Service\Api\PostService;

class Home extends BaseController
{
    private $log;
    public function __construct()
    {
        $this->set_code("main");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());
        return $this;

    }

    public function getindex()
    {
        $page= 1;
        $limit = 10;
        $where= [];
        $this->set_title("보이는 매물");



        $data = array(
            "img"=>RS_IMG."/sec_02/img_sec2.jpg"
        );
        $this->view("",'Home/index',$data);
    }

    public function index()
    {
        dd("web index");
    }

}