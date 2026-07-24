<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\AddMenuService;
use App\Service\FunctionMenuService;
use App\Service\ImgService;
use App\Service\MenuService;
use App\Service\PropertyService;

class Menu extends BaseController
{
    private $vdir;
    private $service;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("menu_api");
        $this->property_log = $this->getMonoLog("property_api");
      //  $this->vdir="Admin/Menu/";
        $this->service = new MenuService($this->log,$this->session_id);
        $this->FMservice = new FunctionMenuService($this->log,$this->session_id);
        $this->property_service = new PropertyService($this->property_log,$this->session_id);

        $this->set_debug(0);
        return $this;
    }

    public function getlist()
    {
        $rows = $this->service->get_list();



        $data = [
            // ...
            'data'=>$rows
        ];
        $oResult = AT_Result::toSuccessData("",$data);
        $this->view($oResult);
    }
    //메뉴 정보 조회
    public function getData()
    {


        $code = $this->request->getGet("code")??0;
        $debug = $this->request->getGet("debug")??0;
        $this->set_debug($debug);

        if($code=="property"){
            $rows = $this->property_service->get_list();
            $oResult = AT_Result::toSuccessData("",$rows);
        }elseif($code =="button") $oResult=$this->service->getButton_menu();
        elseif ($code=="map"){
            $oResult=$this->service->getLocation($this->session_id);
        }
        elseif ($code=="footer") $oResult=$this->service->getFooter();
        else $oResult = $this->service->getBycode($code);
        $this->view($oResult);
    }
    //메뉴 정보 저장(수정)
    public function postSave()
    {
        $this->log->info("[REV] save : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        if(isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }else $file=null;

        if ($this->request->getVar("code") =="property"){
            $post = $this->request->getPost();
            $oResult = $this->FMservice->save($post);
            $this->view($oResult);
            exit;
        }

        $data['name']=$this->request->getVar("name");
        $data['code']=$this->request->getVar("code");
        $data['bgcolor']=$this->request->getVar("bgcolor");
        $data['transparent']=$this->request->getVar("transparent");
        $data['title']=$this->request->getVar("title");
        $data['title_show']=$this->request->getVar("title_show");
        $data['bgcolor_show']=$this->request->getVar("bgcolor_show");

        $oResult = $this->service->edit($data,$file); //추가는 안되고, 기존 메뉴 수정만 가능
        //오시는길설정에는 위치정보 추가 정보도 함께 등록(수정).
        if($data['code'] =="map"){
            $this->add_service = new AddMenuService($this->log,$this->session_id);
            $addData['code'] = $data['code'];
            $addData['tel']=$this->request->getVar("tel");
            $addData['addr']=$this->request->getVar("addr");
            $addData['subway']=$this->request->getVar("subway");
            $addData['bus']=$this->request->getVar("bus");
            $addData['google']=$this->request->getVar("google")??"";

            $addData['subway_show']=$this->request->getVar("subway_show");
            $addData['bus_show']=$this->request->getVar("bus_show");

            $file2 = isset($_FILES['file2']) ? $_FILES['file2'] : null;

            $this->add_service->edit($addData,$file2);

        }
        $this->log->info("[REV] data : ".json_encode($data,JSON_UNESCAPED_UNICODE));


        $this->view($oResult);
    }
    //첨부파일 등록
    public function postfile_up()
    {
        $this->log->info("[REV] Menu file_up : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        $img_service = new ImgService(null,$this->session_id);

        $code=$this->request->getVar("code");

        if(isset($_FILES['file'])) {
            $this->log->info("[REV] postfile_up : ".json_encode($_FILES,JSON_UNESCAPED_UNICODE));
            $file = $_FILES['file'];
            $oResult = $img_service->add_image($code,$file);
            //파일 업로드
        }else {
            $oResult = AT_Result::toFailData("등록할 파일이 없습니다.");
        }
        $this->log->info("[RES] Menu file_up > result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);
    }
    /*
     * 첨부파일 삭제
     */
    public function postfile_del()
    {
        $this->log->info("[REV] file_del : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        $img_service = new ImgService(null,$this->session_id);
        $img_no=$this->request->getVar("img_no");
        $oResult = $img_service->del_image($img_no);
        $this->log->info("[RES] Menu file_del > img_no : $img_no,  result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);

    }
    //하단메뉴 저장
    public function postsavebottom_menu()
    {
        $this->log->info("[REV] savebottom_menu : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        //$this->service->getButton_menu();
        if(isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }else $file=null;
        $data['menu_name']=$this->request->getVar("menu_name");
        $data['bg_color']=$this->request->getVar("bg_color");
        $data['show']=$this->request->getVar("show");
        $data['link']=$this->request->getVar("link");
        $this->log->info("[postsavebottom_menu] button_save : ".json_encode($data,JSON_UNESCAPED_UNICODE));
        $oResult = $this->service->button_save($data,$file); //추가는 안되고, 기존 메뉴 수정만 가능
        $this->log->info("[RES] savebottom_menu result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);
    }
    //하단메뉴 수정
    public function posteditbottom_menu()
    {
        $this->log->info("[REV] editbottom_menu : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        if(isset($_FILES['file'])) {
            $file = $_FILES['file'];
        }else $file=null;
        $data['button_menu_no']=$this->request->getVar("button_menu_no");
        $data['menu_name']=$this->request->getVar("menu_name");
        $data['bg_color']=$this->request->getVar("bg_color");
        $data['show']=$this->request->getVar("show");
        $data['link']=$this->request->getVar("link");
        $data['file_del']  = $this->request->getVar("file_del");
        $this->log->info("[posteditbottom_menu] button_edit : ".json_encode($data,JSON_UNESCAPED_UNICODE));
        $oResult = $this->service->button_edit($data,$file); //추가는 안되고, 기존 메뉴 수정만 가능
        $this->log->info("[RES] editbottom_menu result : ".$oResult['SUCCESS']?"T":"F");
        $this->view($oResult);
    }
    /*preflight*/
    public function optionsList()
    {
        return $this;
    }
    public function optionsData()
    {
        return $this;
    }
    public function optionsSave()
    {
        return $this;
    }
    public function optionsfile_up()
    {
        return $this;
    }
    public function optionsfile_del()
    {
        return $this;
    }
    public function optionssavebottom_menu()
    {
        return $this;
    }
    public function optionseditbottom_menu()
    {
        return $this;
    }
}