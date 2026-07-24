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
        $row = $Menumodel->findbyCode($code);
        $menu_no = $row['row']['menu_no'];

        return $this->save_image('menu', $menu_no, $file, SHOP_CODE."/menu/");
    }

    // parent_table/parent_no를 직접 받는 범용 이미지 저장. property 등에서 직접 호출.
    public function save_image($parent_table, $parent_no, $file, $upload_dir)
    {
        if (!isset($file['name']) || $file['name'] == "") {
            return AT_Result::toFailData("업로드할 파일이 없습니다.");
        }

        $oResult = $this->file_upload($file, $upload_dir);
        if ($oResult['SUCCESS'] != "TRUE") {
            return AT_Result::toFailData($oResult['MSG']);
        }

        return $this->save_img_row($parent_table, $parent_no, $oResult['RECORD']['file_path'], $oResult['RECORD']['file_name']);
    }

    // img 테이블에 저장하지 않고 파일 업로드만 수행. property 대표이미지(main)처럼
    // 별도 컬럼에 직접 기록해야 하는 경우에 사용.
    public function upload_file($file, $upload_dir)
    {
        return $this->file_upload($file, $upload_dir);
    }

    // 이미 temp에 업로드된 파일을 실제 경로로 move 후 img 테이블에 저장. property detail 등에서 사용.
    public function move_and_save($parent_table, $parent_no, $from_dir, $file_name, $to_dir)
    {
        if ($file_name == "") return AT_Result::toFailData("이미지가 없습니다.");

        $File_Upload = new File_Upload();
        if (!$File_Upload->move($from_dir, $file_name, $to_dir)) {
            return AT_Result::toFailData("이미지 이동에 실패했습니다.");
        }

        return $this->save_img_row($parent_table, $parent_no, $to_dir, $file_name);
    }

    // img_dir/img_name을 img 테이블에 저장하고 결과 응답을 구성하는 공통 로직.
    // save_image()/move_and_save() 둘 다 파일을 확보한 뒤 이 지점에서 합류한다.
    private function save_img_row($parent_table, $parent_no, $img_dir, $img_name)
    {
        $img_entity = new Img_Entity();
        $img_entity->setParentTable($parent_table);
        $img_entity->setParentNo($parent_no);
        $img_entity->setImgDir($img_dir);
        $img_entity->setImgName($img_name);

        $img_no = $this->model->save_img($img_entity);

        if ($img_no > 0) {
            $img['img']['img_no'] = sprintf("%s", $img_no);
            $img['img']['full_url'] = SITE_PDS.rtrim($img_dir, '/')."/".$img_name;
            return AT_Result::toSuccessData("", $img);
        }
        return AT_Result::toFailData("이미지 저장에 실패했습니다.");
    }

    // parent_table/parent_no로 등록된 이미지 목록 조회 (상세페이지 갤러리용)
    public function get_images($parent_table, $parent_no)
    {
        $rows = $this->model->get_image($parent_no, $parent_table);
        foreach ($rows as $k => $row) {
            $rows[$k]['full_url'] = SITE_PDS.rtrim($row['img_dir'], '/')."/".$row['img_name'];
            unset($rows[$k]['parent_table'], $rows[$k]['parent_no'], $rows[$k]['write_date']);
        }
        return $rows;
    }

    public function del_image($img_no)
    {
        $row = $this->model->findbyid($img_no);
        if (empty($row)) {
            return AT_Result::toFailData("존재하지 않는 이미지입니다. (img_no=".$img_no.")");
        }

        $File_Upload = new File_Upload();
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
    private function file_upload($file, $dir)
    {
        if(!isset($file['name'])) {return AT_Result::toFailData("파일이 없습니다.");}
        $File_Upload = new File_Upload();

        $oResult = $File_Upload->setTargetDir($dir)->upload($file);

        return $oResult;
    }
}