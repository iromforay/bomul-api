<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Service\Api\PostService;

class Around extends BaseController
{
    private $log;

    public function __construct()
    {
        $this->set_code("around");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());
        return $this;

    }

    public function getindex()
    {
        $this->set_title("둘러보기");

        $list_data = $this->listing($this->get_code());

    if($list_data['RECORD']['img'] == null) {
        $imgs = array(
            RS_IMG."/sec_02/img_sec03_1.jpg",
            RS_IMG."/sec_02/img_sec03_2.jpg",
            RS_IMG."/sec_02/img_sec03_3.jpg",
            RS_IMG."/sec_02/img_sec03_4.jpg",
            RS_IMG."/sec_02/img_sec03_5.jpg"
        );
    }else{
        // RECORD.img는 [img_no, full_url] 형태 배열(MenuService::getBycode() 참고) - 위 기본이미지
        // 배열(문자열)과 타입을 맞춰야 뷰가 항상 문자열로만 받는다(Array to string conversion 방지).
        $imgs = array_column($list_data['RECORD']['img'], 'full_url');
    }

        $data = array(
            'active'=> $this->get_code(),
            "img"=>$imgs,
            'rows' =>$list_data['RECORD']['row'],
        );


        $this->view("",'Around/index',$data);
    }
}