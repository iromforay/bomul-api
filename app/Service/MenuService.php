<?php

namespace App\Service;

use App\Entity\Img_Entity;
use App\Entity\Menu_Entity;
use App\Entity\ButtonMenu_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class MenuService extends CI_Service
{
    private $model;
    private $img_model;
    private $log;

    public function __construct($log,$session_id="")
    {
        parent::__construct($session_id);
        $this->log=$log;
        $this->model = model('App\Models\MenuModel',true,$this->db);
        $this->img_model = model('App\Models\ImgModel',true,$this->db);
    }

    public function get_list ()
    {

      //  $total_count  = $this->model->get_count();
        $row = $this->model->get_list(0,0);
        $row = $this->after_process($row);
        return $row;
       // return array("total"=>$total_count, "rows"=>$row);
    }
    public function getLink_type($type="")
    {
        $_type = array(
            "bo"=>"게시판",
            "pa"=>"페이지",
            "re"=>"외부링크"
        );

        if($type =="") return $_type;
        else return $_type[$type];
    }
    public function after_process($data)
    {
        foreach ($data as $k=>$v){
     //       $v['link_type_txt'] = $this->getLink_type($v['link_type']);
            $data[$k] = $v;
        }
        return $data;
    }
    public function get_by_id($idx)
    {

        $row = $this->model->findbyid($idx);
        return $row[0];
    }
    public function getBycode($code)
    {
        $data= $this->model->findbyCode($code);
        if(isset($data['row']['menu_no']) and $data['row']['menu_no']>0) {
            $image_row = $this->img_model->get_image($data['row']['menu_no']);

            foreach ($image_row as $k => $v) {
                $image[$k]['img_no'] = $v['img_no'];
                $image[$k]['full_url'] = SITE_PDS . $v['img_dir'] . $v['img_name'];
            }

            if (isset($image) and count($image) > 0)
                $data['img'] = $image;
            else $data['img'] = null;
            if (isset($data['row']['code']) and $data['row']['code'] == $code) {
                $oResult = AT_Result::toSuccessData("", $data);
            } else {
                $oResult = AT_Result::toFailData();
            }
        }else {
            $oResult = AT_Result::toFailData("존재하지 않는 코드 입니다. (code: $code)");
        }
        return $oResult;

    }

    public function edit($data,$file)
    {
        $this->log->info("메뉴 처리");
        $entity =  new Menu_Entity();
        $entity->setName($data['name']);
        $entity->setCode($data['code']);
        $entity->setBgcolor($data['bgcolor']);
        $entity->setTransparent($data['transparent']);
        $entity->setTitle($data['title']);
        $entity->setTitleShow($data['title_show']);
        $entity->setBgcolorShow($data['bgcolor_show']);

        $row = $this->model->findbyCode($entity->getCode());
        $img = $this->img_model->get_image($row['row']['menu_no']);
/*파일 등록*/
        if(isset($file['name']) and $file['name'] !="") {//파일 있음
            $this->log->info("첨부파일");
            $img_entity = new Img_Entity();
            $oResult = $this->file_upload($file);

            if($oResult['SUCCESS'] == "TRUE") {
                if($oResult['RECORD']['file_path'] !="") $img_entity->setImgDir($oResult['RECORD']['file_path']);
                if($oResult['RECORD']['file_name'] !="") $img_entity->setImgName($oResult['RECORD']['file_name']);
            }
            else{ //등록파일 업로드 실패
                return AT_Result::toFailData($oResult['MSG']);
            }
            $this->log->info("첨부파일 : ".$img_entity->getImgName());
        }else{
            $this->log->info("첨부파일 없음");
            $img_entity = new Img_Entity();
            if(isset($data['file_del']) and $data['file_del']=="Y"){ //기존파일 삭제일때
                $this->log->info("파일삭제");
                //파일 실삭제
                $file['img_dir'] = $img['img_dir'];
                $file['img_name'] = $img['img_name'];
                $this->file_del($file);
                $img_entity->setImgDir("");
                $img_entity->setImgName("");
            }else {
                $this->log->info("첨부파일 유지");
                //기존 데이터 조회
                $img_entity->setImgDir($row['img_dir']??"");
                $img_entity->setImgName($row['img_name']??"");
            }
        }
        $entity->setMenuNo($row['row']['menu_no']);

        if($this->model->sql_edit($entity)){
            $this->log->info("query :".$this->model->getQuery()." ture");
            //첨부파일 등록
            $img_entity->setParentTable("menu");
            $img_entity->setParentNo($entity->getMenuNo());

            if($img_entity->getImgName()!="")
                $this->img_model->save_img($img_entity);
            $this->log->info("이미지 등록 query :".$this->img_model->getQuery()." ture");
            return AT_Result::toSuccessData();
        }else{
            $this->log->info("쿼리 실행 실패");
            return AT_Result::toFailData();
        }
    }
    public function save($data)
    {
        $entity =  new Menu_Entity();
        $entity->setName($data['name']);
        $entity->setCode($data['code']);
        $entity->setBgcolor($data['bgcolor']);
        $entity->setTransparent($data['transparent']);
        $entity->setTitle($data['title']);
        $entity->setTitleShow($data['title_show']);
        $entity->setBgcolorShow($data['bgcolor_show']);
        $result = $this->model->sql_save($entity);

    }

    public function getButton_menu()
    {
        $rows = $this->model->get_button_menu();
        $oResult = AT_Result::toSuccessData("", $rows);
        return $oResult;
    }

    public function button_edit($data,$file)
    {
        $entity = new ButtonMenu_Entity();

        //파일 업로드
        if(isset($file['name']) and $file['name'] !="") {
            $oResult = $this->file_upload($file);

            if($oResult['SUCCESS'] == "TRUE") {
                if($oResult['RECORD']['file_path'] !="") $entity->setImgDir($oResult['RECORD']['file_path']);
                if($oResult['RECORD']['file_name'] !="") $entity->setImgName($oResult['RECORD']['file_name']);
            }
            else{ //등록파일 업로드 실패
                return AT_Result::toFailData($oResult['MSG']);
            }
        }else{
            $row = $this->model->findbuttonmenubyid($data['button_menu_no']);
            if($data['file_del']=="Y"){ //기존파일 삭제일때
                $entity->setImgDir("");
                $entity->setImgName("");
            }else {
                //기존 데이터 조회
                $entity->setImgDir($row['img_dir']);
                $entity->setImgName($row['img_name']);
            }
        }
        //데이터 저장
        $entity->setMenuNo($data['button_menu_no']);
        $entity->setMenuName($data['menu_name']);
        $entity->setBgColor($data['bg_color']);
        $entity->setShow($data['show']);
        $entity->setLink($data['link']);
        if($this->model->edit_button_menu($entity)){
             return AT_Result::toSuccessData();
        }else{
            return AT_Result::toFailData();
        }
    }
    public function button_save($data,$file)
    {
        $this->log->info("button_save > data :  ".json_encode($data,JSON_UNESCAPED_UNICODE));
        $entity = new ButtonMenu_Entity();

        //파일 업로드
        if(isset($file['name']) and $file['name'] !="") {
            $oResult = $this->file_upload($file);

            if ($oResult['SUCCESS'] == "TRUE") {
                if ($oResult['RECORD']['file_path'] != "") $entity->setImgDir($oResult['RECORD']['file_path']);
                if ($oResult['RECORD']['file_name'] != "") $entity->setImgName($oResult['RECORD']['file_name']);
            } else { //등록파일 업로드 실패
                $entity->setImgDir("");
                $entity->setImgName("");
            }
        }else{
            $entity->setImgDir("");
            $entity->setImgName("");
        }
        //데이터 저장

        $entity->setMenuName($data['menu_name']);
        $entity->setBgColor($data['bg_color']);
        $entity->setShow($data['show']);
        $entity->setLink($data['link']);
        $menu_no = $this->model->save_button_menu($entity);
        $this->log->info("query :".$this->model->getQuery()." ture");
        if($menu_no>0){
            return AT_Result::toSuccessData("",$menu_no);
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
    private function file_del($file)
    {
        $File_Upload = new File_Upload();

        $files['file_path'] = $file['img_dir'];
        $files['file_name'] = $file['img_name'];

        $File_Upload->delete($files);
    }
    public function getLocation($session_id="")
    {
        $add_menu = new AddMenuService("",$session_id);
        $row = $add_menu->get_addmenu("map");


        $oResult = $this->getBycode("map");
        $data = $oResult['RECORD'];
        $rows = $data['row'];

        unset($rows['menu_no']); unset($rows['write_date']);
        $data['row'] = $rows;
        foreach($row as $v) {
            $data['row'][$v['key']] = $v['text'];
            if($v['key'] =="subway"){
                $data['row']['subway_show'] = $v['show'];
            }else if($v['key']=="bus"){
                $data['row']['bus_show'] = $v['show'];
            }else if($v['key']=="file2") {
                $data['row']['file2'] = SITE_PDS . $v['img_dir'] . $v['img_name'];
            }
        }


        $oResult['RECORD'] = $data;

        return $oResult;
    }
    public function getFooter()
    {
        $basic_model = model('App\Models\BasicModel',true,$this->db);

        $row = $basic_model->findbyid(1);
        dd($row);
    }

}