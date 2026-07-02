<?php

namespace App\Service;

use App\Entity\Img_Entity;
use App\Entity\Add_menu_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class AddMenuService extends CI_Service
{
    private $model;
    private $log;

    public function __construct($log="",$session_id)
    {
        parent::__construct($session_id);

        $this->log = $log;
        $this->model = model('App\Models\AddMenuModel',true,$this->db);
        return $this;
    }

    public function get_addmenu($code)
    {
        $code= "map";

        $rows = $this->model->findbyCode($code);
        return $rows;
    }
    public function add_menu_tel($data)
    {
        if(isset($data['tel'])) {
            $entity = new Add_menu_Entity();
            $entity->setCode($data['code']);

            $entity->setKey("tel");
            $entity->setText($data['tel']);

            $entity->setShow("Y");
            $entity->setImgDir("");
            $entity->setImgName("");
            $data = $this->model->findbyCodeAndKey($entity->getCode(),$entity->getKey());
            $entity->setAddMenuNo($data['add_menu_no']);

            $this->model->sql_edit($entity);
        }else{
            return $this;
        }
        return $this;
    }
    public function add_menu_addr($data)
    {
        if(isset($data['addr'])) {
            $entity =  new Add_menu_Entity();
            $entity->setCode($data['code']);

            $entity->setKey("addr");
            $entity->setText($data['addr']);

            $entity->setShow("Y");
            $entity->setImgDir("");
            $entity->setImgName("");

            $data = $this->model->findbyCodeAndKey($entity->getCode(),$entity->getKey());
            $entity->setAddMenuNo($data['add_menu_no']);
            $this->model->sql_edit($entity);
        }else{
            return $this;
        }
        return $this;
    }

    public function add_menu_subway($data)
    {
        if(isset($data['subway'])) {

            $entity =  new Add_menu_Entity();

            $entity->setCode($data['code']);

            $entity->setKey("subway");
            $entity->setText($data['subway']);

            $entity->setShow($data['subway_show']??"N");
            $entity->setImgDir("");
            $entity->setImgName("");

            $data = $this->model->findbyCodeAndKey($entity->getCode(),$entity->getKey());
            $entity->setAddMenuNo($data['add_menu_no']);
            $this->model->sql_edit($entity);
        }else{
            return $this;
        }
        return $this;
    }
    public function add_menu_bus($data)
    {
        if(isset($data['bus'])) {
            $entity =  new Add_menu_Entity();
            $entity->setCode($data['code']);

            $entity->setKey("bus");
            $entity->setText($data['bus']);

            $entity->setShow($data['bus_show']??"N");
            $entity->setImgDir("");
            $entity->setImgName("");

            $data = $this->model->findbyCodeAndKey($entity->getCode(),$entity->getKey());
            $entity->setAddMenuNo($data['add_menu_no']);
            $this->model->sql_edit($entity);
        }else{
            return $this;
        }
        return $this;
    }
    public function add_menu_google($data)
    {
        if(isset($data['google'])) {
            $entity =  new Add_menu_Entity();
            $entity->setCode($data['code']);

            $entity->setKey("google");
            $entity->setText($data['google']);

            $entity->setShow("Y");
            $entity->setImgDir("");
            $entity->setImgName("");

            $row = $this->model->findbyCodeAndKey($entity->getCode(),$entity->getKey());

            $entity->setAddMenuNo($row['add_menu_no']);
            $this->model->sql_edit($entity);
            $this->log->info("add_menu_google query :".$this->model->getQuery()." ture");
        }else{
            return $this;
        }
        return $this;
    }

    public function edit($data,$file2)
    {
        $this->add_menu_tel($data)->add_menu_addr($data)->add_menu_subway($data)->add_menu_bus($data)->add_menu_google($data);

        $entity =  new Add_menu_Entity();
        $entity->setCode($data['code']);
        $entity->setKey("file2");
        $row = $this->model->findbyCodeAndKey($entity->getCode(),$entity->getKey());
        $entity->setAddMenuNo($row['add_menu_no']);

        /*파일 등록*/
        if(isset($data['file2_del']) and $data['file2_del']=="Y"){ //기존파일 삭제일때
            $entity->setImgDir("");
            $entity->setImgName("");
            //파일 실삭제
        }else {
            //기존 데이터 조회
            $entity->setImgDir($row['img_dir']);
            $entity->setImgName($row['img_name']);
        }

        if(isset($file2['name']) and $file2['name'] !="") {

            //파일 업로드
            $oResult = $this->file_upload($file2);

            if ($oResult['SUCCESS'] == "TRUE") {
                if ($oResult['RECORD']['file_path'] != "") $entity->setImgDir($oResult['RECORD']['file_path']);
                if ($oResult['RECORD']['file_name'] != "") $entity->setImgName($oResult['RECORD']['file_name']);
            } else { //등록파일 업로드 실패
                return AT_Result::toFailData($oResult['MSG']);
            }
        }


        if($this->model->sql_edit($entity)){

            return AT_Result::toSuccessData();
        }else{
            return AT_Result::toFailData();
        }
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