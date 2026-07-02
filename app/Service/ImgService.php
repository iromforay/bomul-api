<?php

namespace App\Service;

use App\Entity\Img_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class ImgService extends CI_Service
{
    private $model;

    public function __construct($log,$session_id)
    {
        parent::__construct($session_id);
        $this->model = model('App\Models\ImgModel',true,$this->db);
    }

    public function add_image($code,$file)
    {
        $Menumodel = model('App\Models\MenuModel',true,$this->db);
        $img_entity = new Img_Entity();
        $row = $Menumodel->findbyCode($code);
        $menu_no = $row['row']['menu_no'];
        //파일 업로드
        if(isset($file['name']) and $file['name'] !="") {//파일 있음

            $oResult = $this->file_upload($file);

            if($oResult['SUCCESS'] == "TRUE") {
                if($oResult['RECORD']['file_path'] !="") $img_entity->setImgDir($oResult['RECORD']['file_path']);
                if($oResult['RECORD']['file_name'] !="") $img_entity->setImgName($oResult['RECORD']['file_name']);
            }
            $img_entity->setParentTable("menu");
            $img_entity->setParentNo($menu_no);
            $this->model->save_img($img_entity);
            $img_no = $this->model->getLastId();

            $img['img']['img_no'] = sprintf("%s",$img_no);
            $img['img']['full_url'] = SITE_PDS.$img_entity->getImgDir().$img_entity->getImgName();

            if($img_no >0) return AT_Result::toSuccessData("",$img);
            else{ //등록파일 업로드 실패
                return AT_Result::toFailData($oResult['MSG']);
            }
        }
        return AT_Result::toFailData("업로드할 파일이 없습니다.");
    }

    public function del_image($img_no)
    {
        $File_Upload = new File_Upload();
        $row = $this->model->findbyid($img_no);

        $file['file_path'] = $row[0]['img_dir'];
        $file['file_name'] = $row[0]['img_name'];
        $File_Upload->delete($file);

        if($this->remove_image($img_no)){
            return AT_Result::toSuccessData();
        }else{
            return AT_Result::toFailData();
        }
        exit;
    }

    private function remove_image($img_no)
    {
        $img_entity = new Img_Entity();

        $img_entity->setImgNo($img_no);
        return $this->model->delete_image($img_entity);

    }
    private function file_upload($file)
    {
        if(!isset($file['name'])) {return AT_Result::toFailData("파일이 없습니다.");}
        $File_Upload = new File_Upload();
        $files = array();
        $dir =  SHOP_CODE."/menu/"; //.$this->code;
        // $this->set_dir($dir);

        $oResult = $File_Upload->setTargetDir($dir)->upload($file);

        return $oResult;
    }
}