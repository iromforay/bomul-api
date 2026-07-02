<?php
namespace App\Controllers\Admin;
use App\Service\BuilderService;


class Setup extends BaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->vdir="Admin/Boards/";
        $this->service = new BuilderService();

        return $this;
    }

    public function getHome()
    {
        //return "Home";
        $data=array();
        $this->view("/Admin/Setup/index",$data);
    }
    public function getDetail()
    {
        $seq_no = $this->request->getVar("seq_no");
        $data['limit_showcount'] = 5; // 게시판 링크시 게시물 노출 최대개수
        $data['row'] = $this->service->getByid($seq_no);

        $data['link']['board'] = $this->service->getBoard();
        $data['link']['pager'] = $this->service->getPager();
        $this->view("/Admin/Setup/detail",$data);
    }

    public function postmodify()
    {
        $data = array(
            'build_no' => $this->request->getVar("build_no"),
            'type' => $this->request->getVar("type"),
            'bg_color' => $this->request->getVar("bg_color"),
            'color' => $this->request->getVar("color"),
            'uses' => $this->request->getVar("uses"),
            'text' => $this->request->getVar("text"),
            'link_type' => $this->request->getVar("link_type"),
            'link' => $this->request->getVar("link"),
            'show_count' => $this->request->getVar("bo_show_count")
        );

        $oResult = $this->service->edit($data,$_FILES['img_file']);

        if($oResult['SUCCESS'] == "TRUE"){
            $this->_warning_redirect("정상적으로 수정하였습니다.",Admin_SITE."/Setup/home");
        }else{
            $msg = $oResult['MSG']?? "실패하였습니다.";
            $this->_warning_redirect($msg,Admin_SITE."/Setup/detail?seq_no=".$data['build_no']);
        }


    }
}