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
        $imgs = $list_data['RECORD']['img'];
    }

        $data = array(
            'active'=> $this->get_code(),
            "img"=>$imgs,
            'rows' =>$list_data['RECORD']['row'],
        );


        $this->view("",'Around/index',$data);
    }
}