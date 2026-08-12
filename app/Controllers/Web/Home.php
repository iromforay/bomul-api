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

        // Home은 다른 페이지와 달리 이미지가 항상 기본 배경이다(등록이미지 있으면 그걸, 없으면
        // 기본이미지로 폴백) - bgcolor_show="Y"일 때 색상은 그 위에 반투명 오버레이로 얹힐 뿐,
        // 이미지를 대체하지 않는다. 색상/투명도 자체는 $sub(BaseController::body() 공통 로직)가
        // 이미 갖고 있어서 뷰에서 header_menu.php가 선언한 --sub_back_color 등 CSS 변수로 처리한다.
        if ($img['SUCCESS'] == "TRUE" && !empty($img['RECORD']['img'])) {
            $data["img"] = array_column($img['RECORD']['img'], 'full_url');
        } else {
            // 기존 img_sec2.jpg는 이전(스터디룸) 프로젝트의 내부 사진이 그대로 남아있던 것이라 교체함.
            // 실제 매물/사무소 사진이 준비되면 이 경로를 그 사진으로 바꾸면 됨.
            $data["img"] = [
                RS_IMG."/demo/menu/1208688f54de1325932540cdc603e08f.png",
                RS_IMG."/demo/menu/1b1af2a6800de5f3bf39ca1fbb60c797.png",
                RS_IMG."/demo/menu/f1b067a9149ee94df627b8c0a1d32f3e.png"
                ];
        }
        $this->view("",'Home/index',$data);
    }

    public function index()
    {
        dd("web index");
    }

}