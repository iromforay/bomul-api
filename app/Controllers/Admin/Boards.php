<?php

namespace App\Controllers\Admin;
use App\Service\BoardService;

class Boards extends BaseController
{
    private  $vdir;
    private $service;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->vdir="Admin/Boards/";
        $this->service = new BoardService();
        return $this;
    }

    public function getindex()
    {
        $this->getList();
        return $this;
    }
    public function getList()
    {
        $pager = service("pager");

        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 5;

        $rows = $this->service->get_list($page,$perPage);
        $total = $rows['total'];

        $pager_links = $pager->makeLinks($page, $perPage, $total);
        $num = $total-(($page-1)*$perPage );
        $data = [
            // ...
            'pager_links' => $pager_links,
            'data'=>$rows['rows'],
            'num' =>$num
        ];
         $this->view($this->vdir."list",$data);
    }
    public function getWrite()
    {
        $data = array();
        $data['bcode'] = $this->service->makecode();
        $this->view($this->vdir."write_form",$data);
    }
    public function getEdit()
    {
        $seq_no = $this->request->getVar('seq_no');

        $data['board']=$this->service->get_by_id($seq_no)[0];

        $this->view($this->vdir."modify_form",$data);
    }
    public function getCopy()
    {
        $seq_no = $this->request->getVar('seq_no');
        $this->service->table_copy($seq_no);
    }

    public function postregister()
    {

        //파일 업로드 처리
        $header_file = $_FILES['header_upload'];
        $footer_file = $_FILES['footer_upload'];

        $data['bcode']=$this->request->getVar('bcode');
        $data['bname']=$this->request->getVar('bname');
        $data['header_title']=$this->request->getVar('header_title');
        //$files
        $data['footer_title']=$this->request->getVar('footer_title');
        //$files
        $data['style']=$this->request->getVar('style');
        $data['writer']=$this->request->getVar('writer');
        $data['reader']=$this->request->getVar('reader');
        $data['lister']=$this->request->getVar('lister');
        $data['reply']=$this->request->getVar('reply');
        $data['secret'] =$this->request->getVar('secret');

        $bseq = $this->service->set_code($data['bcode'])->save($data,$header_file,$footer_file);
        if($bseq>0){
            $this->_redirect(Admin_SITE."/Boards/edit?seq_no=$bseq");
        }else{
            $this->_redirect(Admin_SITE."/Boards/write");
        }
    }

    public function postmodify()
    {

        //파일 업로드 처리
        $header_file = $_FILES['header_upload'];
        $footer_file = $_FILES['footer_upload'];

        //form 데이터 가져오기
        $data['board_no'] = $seq_no = $this->request->getVar('board_no');
        $data['bcode']=$this->request->getVar('bcode');
        $data['bname']=$this->request->getVar('bname');
        $data['header_title']=$this->request->getVar('header_title');
        //$files
        $data['footer_title']=$this->request->getVar('footer_title');
        //$files
        $data['style']=$this->request->getVar('style');
        $data['writer']=$this->request->getVar('writer');
        $data['reader']=$this->request->getVar('reader');
        $data['lister']=$this->request->getVar('lister');
        $data['reply']=$this->request->getVar('reply');
        $data['secret'] =$this->request->getVar('secret');

            //수정
        $oResult = $this->service->set_code($data['bcode'])->edit($data,$header_file,$footer_file);
        if($oResult['SUCCESS'] == "TRUE") {
            $this->_redirect(Admin_SITE."/Boards/list");
        }else{
            $this->_redirect(Admin_SITE."/Boards/edit?seq_no=$seq_no");
        }
    }

}