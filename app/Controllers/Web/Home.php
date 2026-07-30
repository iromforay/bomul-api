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
        // 모든 중개사 홈페이지가 똑같이 "보이는 매물"만 뜨면 검색엔진이 사이트를 구분 못 하니
        // 부동산명(company_name)을 붙여서 사이트별로 고유한 title이 나가게 한다.
        $basic = $this->get_body();
        $siteTitle = "보이는 매물" . (!empty($basic['company_name']) ? " - " . $basic['company_name'] : "");
        $this->set_title($siteTitle);



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