<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\BasicService;
use App\Service\ImgService;


class Basic extends BaseController
{
    private $service;
    private $log;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_auth();

        $this->log = $this->getMonoLog("basic_api");
        $this->service = new BasicService($this->log,$this->session_id);
        $this->set_debug(0);
        return $this;
    }

    public function getData()
    {
        $row = $this->service->get_by_basic();

        $oResult = AT_Result::toSuccessData("",$row);
        $this->view($oResult);
    }
    public function postSave()
    {

        $this->log->info("[REV] save : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        $data['code']="basic";
        $data['site_name'] = $this->request->getVar("site_name");
        if(isset($_FILES['logo'])) {
            $file = $_FILES['logo'];
        }else{
            $file=null;
        }
        $data['logo_show'] = $this->request->getVar("logo_show");

        $data['main_menu_color'] = $this->request->getVar("main_menu_color");
        $data['sub_menu_focus_color'] = $this->request->getVar("sub_menu_color");
        $data['menu_back_color'] = $this->request->getVar("menu_back_color");
        $data['menu_back_transparent'] = $this->request->getVar("menu_back_transparent");
        $data['notice_show'] = $this->request->getVar("notice_show");
        $data['around_show'] = $this->request->getVar("around_show");
        $data['charge_show'] = $this->request->getVar("charge_show");
        $data['review_show'] = $this->request->getVar("review_show");
        $data['vod_show'] = $this->request->getVar("vod_show");
        $data['map_show'] = $this->request->getVar("map_show");
        //링크메뉴설정
        $data['link_menu_show'] = $this->request->getVar("link_menu_show");

        $data['naver_reserve'] = $this->request->getVar("naver_reserve");
        $data['naver_reserve_show'] = $this->request->getVar("naver_reserve_show");
        $data['kakao_channel'] = $this->request->getVar("kakao_channel");
        $data['kakao_channel_show'] = $this->request->getVar("kakao_channel_show");
//사이트 링크 메뉴
        $data['sns_link'] = $this->request->getVar("sns_link");
        $data['sns_key'] = $this->request->getVar("sns_key");
        $data['sns_del_key'] = $this->request->getVar("sns_del_key");

        $sns_file = isset($_FILES['sns_img']) ? $_FILES['sns_img'] : "";

        $data['company_name'] = $this->request->getVar("company_name");
        $data['footer_copy'] = $this->request->getVar("footer_copy");

        $data['logo_del'] = $this->request->getVar("logo_del"); //로고삭제

        $this->log->info("[REV] data : ".json_encode($data,JSON_UNESCAPED_UNICODE));
        $oResult = $this->service->save($data,$file,$sns_file);
        $this->view($oResult);
    }

    /*
     * 첨부파일 삭제
     */
    public function postfile_del()
    {
        $img_service = new ImgService(null,$this->session_id);
        $img_no=$this->request->getVar("img_no");
        $oResult = $img_service->del_image($img_no);
        $this->view($oResult);

    }

    /*preflight*/
    public function optionsData()
    {
        return $this;
    }
    public function optionsSave()
    {
        return $this;
    }
    public function optionsfile_del()
    {
        return $this;
    }
}

