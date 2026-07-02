<?php

namespace App\Controllers;

use App\Service\BoardService;
use App\Service\PostService;

class Post extends BaseController
{
    private $service; //게시판
    private $post_service; //게시물
    private $bcode;
    private $boardFrame = [];
    public function __construct()
    {

        parent::__construct();
        $this->service = new BoardService();
        $this->post_service = new PostService();
        return $this;
    }

    public function get_board()
    {
        $data = $this->service->get_board($this->bcode);

        $this->boardFrame = $data['boardFrame'];
        $this->acter = $data['acter'];
        return $this;
    }

    public function getList()
    {
        $this->bcode = $this->request->getVar('bcode');
        $pager = service("pager");

        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;
        //게시판 로딩
        $this->get_board();

        //권한 체크
        $button  = $this->service->grant($this->acter);

        //게시물 가져오기getcode  get_list_grant   get_list
        $this->post_service->getcode($this->bcode);
        $this->post_service->get_list_grant($button['button']['list']);
        
        //뷰에 보낼 데이터 정리
        $where[] = "`status`=1";
        $rows = $this->post_service->get_list($page,$perPage,$where); //리스트 가져오기
        $total = $rows['total'];
        $pager_links = $pager->makeLinks($page, $perPage, $total);
        $num = $total-(($page-1)*$perPage );

        $data = [
            'pager_links' => $pager_links,
            'data'=>$rows['rows'],
            'num' =>$num,
            'frame' =>$this->boardFrame,
            'bcode' => $this->bcode,
            'button'=> $button['button']
        ];

        $this->view("default","/Post/list",$data);
    }
    public function getWrite()
    {
        $session = session();
        $this->bcode = $this->request->getVar('bcode');
        $this->get_board();
        //권한 체크
        $button  = $this->service->grant($this->acter);

        $session->set(array("csrf_no"=>md5(microtime())));
        $data['csrf']=$this->post_service->get_csrf($_SESSION['csrf_no']);

        $data['button']= $button['button'];
        $data['frame'] = $this->boardFrame;
        $data['bcode']=$this->bcode;


        $this->view("default","/Post/write",$data);
    }

    public function getModify()
    {
        $post_no = $this->request->getVar('post_no');
        //$row = $this->post_service->getbyid($post_no);

        $Oreturn= $this->post_service->is_modify($post_no);

        if($Oreturn['SUCCESS'] == "TRUE") {//수정권한 있음.

            $this->bcode = $row[0]['bcode'];
            $this->get_board();

            $data = array(
                "frame" => $this->boardFrame,
                "data" => $row[0],
                "csrf" => $this->post_service->get_csrf($post_no)
            );
            $this->view("default", "/Post/modify", $data);
        }else{//수정권한 없음 또는 패스워드 필요시
            if($Oreturn['CODE'] == "IN_PWD") { //패스워드 필요

            }else{ //권한 없음.
                $this->_warning_redirect("수정 권한이 없습니다.","/Post/modify?post_no=".$post_no);
            }
        }
    }
    public function postview()
    {
       $this->getView();
    }
    public function getView()
    {
        $post_no = $this->request->getVar('post_no');
        $password = $this->request->getVar('password');
        $mode =  $this->request->getVar('mode');
        $row = $this->post_service->getbyid($post_no);


        $this->bcode = $row[0]['bcode'];
        //게시판 로딩
        $this->get_board();

        //권한 체크
        $data  = $this->service->grant($this->acter);
        $data['data'] =$row[0];
        $data['frame'] = $this->boardFrame;
        $oResult = $this->post_service->is_auth($row[0],$password);

        if($oResult['SUCCESS']=="TRUE"){

        }else if($oResult['CODE']=="IN_PWD"){
            $this->get_password($data);
        }

        $data['bcode']=$this->bcode;

        $this->view("default","/Post/view",$data);
    }
    public function get_password($param)
    {
        $data=array();
        $data['post_no'] = $param['data']['post_no'];
        $data['frame'] = $param['frame'];
        $this->view("default","/Post/password",$data);
        exit;
    }
    public function postWrite()
    {
        $session = session();
        $scrf_token = $this->request->getVar('token');
        //csrf
        $csrf_no = $session->get("csrf_no");
        /*필드데이터*/
        $bcode = $this->request->getVar('bcode');
        $title = $this->request->getVar('title')??"";
        $name = $this->request->getVar('name')??"";
        $secret = $this->request->getVar('secret')??"";
        $password = $this->request->getVar('password')??"";
        $content = $this->request->getVar('content')??"";
        $tel = $this->request->getVar('tel')??"";

        $data= array(
            "title" => $title,
            "name" => $name,
            "secret" => $secret,
            "password" => $password,
            "content" => $content,
            "tel" =>$tel,
            "bcode" => $bcode
        );

        $return = $this->post_service->verify($csrf_no,$scrf_token);
        
        if($return['SUCCESS'] == "TRUE"){ //저장
            $post_no = $this->post_service->save($data);
            $this->_warning_redirect("게시물을 성공적으로 등록하였습니다.","/Post/view?post_no=".$post_no);
        }else{//실패처리
            $this->_warning_redirect("게시물 등록에 실패하였습니다.","/Post/write?bcode=".$bcode);
        }

    }
    public function postModify()
    {

        $scrf_token = $this->request->getVar('token');
        //csrf

        /*필드데이터*/
        $post_no = $this->request->getVar('post_no');
        $title = $this->request->getVar('title')??"";
        $name = $this->request->getVar('name')??"";
        $secret = $this->request->getVar('secret')??"";
        $password = $this->request->getVar('password')??"";
        $content = $this->request->getVar('content')??"";
        $tel = $this->request->getVar('tel')??"";

        $data= array(
            "title" => $title,
            "name" => $name,
            "secret" => $secret,
            "password" => $password,
            "content" => $content,
            "tel" =>$tel,
            'post_no'=>$post_no
        );

        $return = $this->post_service->verify($post_no,$scrf_token);

        if($return['SUCCESS'] == "TRUE"){ //저장
            $post_no = $this->post_service->save($data);
            $this->_warning_redirect("수정 하였습니다.","/Post/view?post_no=".$post_no);
        }else{//실패처리
            $this->_warning_redirect("수정에 실패하였습니다.","/Post/modify?post_no=".$post_no);
        }

    }
}