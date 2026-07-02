<?php

namespace App\Service;

use App\Entity\Build_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class BuilderService extends CI_Service
{
    private $model;
    private $board_model;
    private $pager_model;

    public function __construct()
    {
        parent::__construct();

        $this->board_model = model('App\Models\BoardModel',true,$this->db);
        $this->pager_model = model('App\Models\PagerListModel',true,$this->db);

        $this->model = model('App\Models\BuilderModel',true,$this->db);
    }
    public function getByid($seq_no)
    {
        $row = $this->model->findbyid($seq_no);

        return $row[0];
    }
    public function edit($data,$file)
    {
        //파일 업로드
        if(isset($file['name']) and $file['name']!="") $oResult = $this->file_upload($file);
        else $oResult = AT_Result::toSuccessData("",array("file_path"=>"","file_name"=>""));

        if($oResult['SUCCESS'] == "TRUE"){
            //entity 변환
            $entity = new Build_Entity();
            $entity->setBuildNo($data['build_no']);
            $entity->setType($data['type']);
            $entity->setText($data['text']);
            $entity->setBgColor($data['bg_color']);
            $entity->setColor($data['color']);
            $entity->setLinkType($data['link_type']);
            $entity->setLink($data['link']);
            $entity->setUses($data['uses']);
            $entity->setShowCount($data['show_count']);
            if($oResult['RECORD']['file_path'] !="") $entity->setImgUrl($oResult['RECORD']['file_path']);
            if($oResult['RECORD']['file_name'] !="") $entity->setFileName($oResult['RECORD']['file_name']);

            //db로 set

            if($this->model->sql_edit($entity)){
                return AT_Result::toSuccessData();
            }else{
                return AT_Result::toFailData("등록에 실패했습니다");
            }

        }else{ //등록파일 업로드 실패
            return AT_Result::toFailData("파일 등록에 실패했습니다");
        }


    }

    private function file_upload($file)
    {
        if(!isset($file['name'])) {return ATC_Result::toFailData();}
        $File_Upload = new File_Upload();
        $files = array();
        $dir =  SHOP_CODE."/build". date("/YmdHis/", time());
       // $this->set_dir($dir);

        $oResult = $File_Upload->setTargetDir($dir)->upload($file);

        return $oResult;
    }
    public function getBoard()
    {
        $rows = $this->board_model->getAll();
        return $this->process_board($rows);
    }
    public function getPager()
    {
        $rows = $this->pager_model->getAll();
        return $this->process_board($rows);
    }
    private function process_board($row)
    {
        $data = array();
        foreach($row as $k=>$v){
            $data[$v['code']] = $v['title'];
        }
        return $data;
    }
}