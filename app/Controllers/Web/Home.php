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
            // 기존 img_sec2.jpg는 이전(스터디룸) 프로젝트의 내부 사진이 그대로 남아있던 것이라 교체함.
            // 실제 매물/사무소 사진이 준비되면 이 경로를 그 사진으로 바꾸면 됨.
            "img"=>RS_IMG."/sec_02/hero_skyline.svg"
        );
        $this->view("",'Home/index',$data);
    }

    public function index()
    {
        dd("web index");
    }

}