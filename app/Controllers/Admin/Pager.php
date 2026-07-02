<?php

namespace App\Controllers\Admin;

use App\Service\PagerService;

class Pager extends BaseController
{
    private  $vdir;
    private $service;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->vdir="Admin/Pager/";
        $this->service = new PagerService();
        return $this;
    }
    public function getIndex()
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
        $data['code'] = $this->service->makecode();
        $this->view($this->vdir."write_form",$data);
    }
    public function getEdit()
    {
        $seq_no = $this->request->getVar('seq_no');

        $row =$this->service->get_by_id($seq_no);
        $data['detail'] = $row['detail'];
        $data['pager'] = $row['list'];
        $data['num']=1;
        $data['max_count'] = 3; // 페이지 내용 허용칸

        $this->view($this->vdir."modify_form",$data);
    }
    public function getCopy()
    {
        $seq_no = $this->request->getVar('seq_no');
        $this->service->table_copy($seq_no);
    }

    public function postregister()
    {

        $data['code']=$this->request->getVar('code');
        $data['title']=$this->request->getVar('title');
        $data['style']=$this->request->getVar('style');
        $data['reader']=$this->request->getVar('reader');

        $pager_no = $this->service->save($data);
        if($pager_no>0){
            $this->_redirect(Admin_SITE."/Pager/edit?seq_no=$pager_no");
        }else{
            $this->_redirect(Admin_SITE."/Pager/write");
        }
    }

    public function postmodify()
    {
        //파일 업로드 처리


        //form 데이터 가져오기
        $data['pager_no'] = $seq_no = $this->request->getVar('seq_no');
        $data['code']=$this->request->getVar('code');
        $data['title']=$this->request->getVar('title');
        $data['style']=$this->request->getVar('style');
        $data['reader']=$this->request->getVar('reader');
        $data['status']=$this->request->getVar('status')?? "T";

        //(다중처리)내용 가져오기
        $pager_dno = $this->request->getVar('pager_dno');
        $sorty = $this->request->getVar('sorty');
        $body = $this->request->getVar('body');
        foreach($pager_dno as $k => $v){
            $detail[$k]['seq_no'] = $v;
            $detail[$k]['pager_no'] =  $data['pager_no'];
            $detail[$k]['sorty'] = $sorty[$k];
            $detail[$k]['body'] = $body[$k];
        }

        $data['detail'] = $detail;
        $d_result = $this->service->set_detail($detail);
        //수정
        $bseq = $this->service->edit($data);
        if($bseq>0 and $d_result){
            $this->_redirect(Admin_SITE."/Pager/list");
        }else{
            $this->_redirect(Admin_SITE."/Pager/edit?seq_no=$seq_no");
        }
    }
}