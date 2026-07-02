<?php

namespace App\Controllers\Admin;

use App\Service\BoardService;
use App\Service\PostService;

class Post extends BaseController
{
    private $bcode;
    public function __construct()
    {

        parent::__construct();
        $this->service = new BoardService();
        $this->post_service = new PostService();
        return $this;
    }
    public function getList()
    {
        $this->bcode = $this->request->getVar('bcode');
        $pager = service("pager");

        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        //게시물 가져오기getcode  get_list_grant   get_list
        $this->post_service->getcode($this->bcode);

        //뷰에 보낼 데이터 정리
        $rows = $this->post_service->get_list($page,$perPage); //리스트 가져오기
        $total = $rows['total'];
        $pager_links = $pager->makeLinks($page, $perPage, $total);
        $num = $total-(($page-1)*$perPage );

        $data = [
            'pager_links' => $pager_links,
            'data'=>$rows['rows'],
            'num' =>$num,
            'bcode' => $this->bcode
        ];

        $this->view("Admin/Post/list",$data);
    }

    public function getView()
    {

        $post_no = $this->request->getVar('post_no');
        $mode =  $this->request->getVar('mode');
        $row = $this->post_service->getbyid($post_no);


        $this->bcode = $row[0]['bcode'];

        //권한 체크

        $data['data'] =$row[0];


        $data['bcode']=$this->bcode;
        $data['is_secret'] = false; // 비밀글여부


        $this->view("Admin/Post/view",$data);
    }
    public function getWrite()
    {
        $this->bcode = $this->request->getVar('bcode');
        $data['bcode']=$this->bcode;
        $data['is_secret'] = false; // 비밀글여부

        $this->view("Admin/Post/write",$data);
    }
    public function postWrite()
    {
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

        $post_no = $this->post_service->save($data);
        if($post_no>0){
            $this->_warning_redirect("게시물을 성공적으로 등록하였습니다.",Admin_SITE."/Post/view?post_no=".$post_no);
        }else{//실패처리
            $this->_warning_redirect("게시물 등록에 실패하였습니다.",Admin_SITE."/Post/write?bcode=".$bcode);
        }

    }
    public function getModify()
    {
        $post_no = $this->request->getVar('post_no');

            //$this->bcode = $row[0]['bcode'];
        $row = $this->post_service->getbyid($post_no);
        $data = array(
            "data" => $row[0]
        );
        $data['is_secret'] = false; // 비밀글여부

        $this->view("Admin/Post/modify", $data);

    }
    public function postModify()
    {
        
    }
}