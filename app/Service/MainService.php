<?php

namespace App\Service;

class MainService extends CI_Service
{
    private $model;
    private $Postmodel;
    private $Pagermodel;

    public function __construct()
    {
        parent::__construct();

        $this->model = model('App\Models\BuilderModel',true,$this->db);
        $this->Postmodel = model('App\Models\PostModel',true,$this->db);
        $this->Pagermodel = model('App\Models\PageModel',true,$this->db);
    }
    public function apiAll()
    {
        $rows = $this->model->findbyAll();
        $data = $this->after_process_api($rows);
        return $data;
    }
    public function getAll()
    {
        $rows = $this->model->findbyAll();
        $data = $this->after_process($rows);
        return $data;
    }

    public function after_process_api($rows)
    {
        foreach($rows as $k=>$v){
            if($v['type'] =="image") {
                $v['img_url'] = PDS_URL . $v['img_url'] . $v['file_name'];
            }else{$v['img_url'] = "";}
            $v['build_no'] = (int)$v['build_no'];
            $v['show_count'] = (int)$v['show_count'];

            if($v['link_type']=="bo") {
                $v['code'] = $v['link'];
                $v['link']="";
            } elseif ($v['link_type']=="pa"){
                $v['code'] = $v['link'];
                $v['link']="";
                //$v['link'] = SITE."Page/view?bcode=".$v['link'];
            }


            $data[$k]=$v;
        }
        return $data;
    }

    public function after_process($rows)
    {

        $data = array();
        foreach($rows as $k=>$v) {
            $link=$link_post="";
            if ($v['link_type'] == "re"){
                $link = "<a href='" . $v['link'] . "'>";
                $link_post = "</a>";
            }

            if($v['type'] =="image") {
                $code= "<img src='".PDS_URL . $v['img_url'] . $v['file_name']."' />";
            }else{
                $format =  "<Div calss='%s' style='background:%s; color:%s'>%s</DIV>";
                $code  = sprintf($format,$v['code'],$v['bg_color'],$v['color'],nl2br($v['text']) );
            }

            $data[$v['code']]['text'] = sprintf("%s%s%s",$link,$code,$link_post);
            if($v['link_type']=="bo") {
                $rows= $this->getBoardData($v['link'],$v['show_count'],"post_no","DESC");
                $data[$v['code']]['row'] = $rows;
            }
        }

        return $data;
    }
    public function getBoardData($bcode,$limit=1,$order="post_no",$sort="DESC")
    {
        $row =$this->Postmodel->getbyCodeOrderBy($bcode,$limit,$order,$sort);
        return $row;
    }
    public function getPaperData($bcode)
    {
         $this->Pagermodel->getbyCode($bcode);
    }
}