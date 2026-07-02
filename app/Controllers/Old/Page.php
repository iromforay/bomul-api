<?php

namespace App\Controllers\Old;

use App\Service\PageService;

class Page extends BaseController
{
    private $service; //게시판
    private $post_service; //게시물
    private $bcode;
    private $boardFrame = [];
    public function __construct()
    {

        parent::__construct();
        $this->service = new PageService();
        return $this;
    }
    public function getview()
    {
        $code = $this->request->getVar('bcode');
        $oResult = $this->service->getBycode($code);

        if($oResult['SUCCESS']=="TRUE") {
            $data = $oResult['RECORD'];
            $row = $data['row'];

            if ($row['reader'] == "member") {
                //회원 확인
                if (1) {
                    $this->_warning_redirect("읽기 권한이 없습니다.");
                }
            }//every
            $data['frame']['style'] = $row['style'];
            $this->view("default", "Page/view", $data);
        }else{
            $msg = $oResult['MSG']??"존재하지 않는 페이지 입니다.";
            $this->_warning_redirect($msg);
        }
    }
}