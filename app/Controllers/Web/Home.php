<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Service\Api\PostService;
use App\Service\MenuService;

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

        $service = new MenuService($this->log,null);
        $img = $service->getBycode($this->get_code());
        $data = array();
        //$basic['sns_link'];

        if($img['SUCCESS'] =="TRUE" && isset($img['RECORD']['img']) && count($img['RECORD']['img'])>0){//실제 배경이미지 등록을 한경우
            $data["img"] = [];
            foreach($img['RECORD']['img'] as $v){
                $data["img"][] = $v['full_url'];
            }
        }else{
            // 기존 img_sec2.jpg는 이전(스터디룸) 프로젝트의 내부 사진이 그대로 남아있던 것이라 교체함.
            // 실제 매물/사무소 사진이 준비되면 이 경로를 그 사진으로 바꾸면 됨.
            // Home/index.php가 항상 $img를 배열로 받도록(원소 1개짜리) - 등록 이미지가 있을 때와
            // 타입을 통일해야 뷰에서 분기 없이 count($img)로만 판단할 수 있음.
            $data["img"] = [
                RS_IMG."/demo/menu/1208688f54de1325932540cdc603e08f.png",
                RS_IMG."/demo/menu/1b1af2a6800de5f3bf39ca1fbb60c797.png",
                RS_IMG."/demo/menu/f1b067a9149ee94df627b8c0a1d32f3e.png"
                ];
          //  $data["img"] = [RS_IMG . "/sec_02/hero_skyline.svg"];
        }

        $this->view("",'Home/index',$data);
    }

    public function index()
    {
        dd("web index");
    }

}