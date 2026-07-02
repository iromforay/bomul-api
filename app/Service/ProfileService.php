<?php

namespace App\Service;

use App\Entity\Profile_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class ProfileService extends CI_Service
{
    private $model;
    private $log;
    private $help_model;
    public function __construct($log,$session_id)
    {
        $this->log = $log;
        parent::__construct($session_id);

        $help_db = db_connect("default");

        $this->model = model('App\Models\ProfileModel',true,$this->db);
        $this->help_model = model('App\Models\HelpModel',true,$help_db);
        return $this;
    }
    public function get_by_profile()
    {
        $rows = $this->model->findbyid(1);

        $data = $row = $rows[0];

        if(isset($data['img_dir']) and isset($data['img_name']) and $data['img_name'] !=""){
            $img_url = SITE_PDS.$data['img_dir'].$data['img_name'];
        }else{
            $img_url="";
        }


        $mall_info = $this->mall_info();
        unset($data);
        $data['nick_name'] = $row['nick_name'];
        $data['img_url'] = $img_url;
        $data['mall_info'] = $mall_info['mall_info'];
        $data['user_info'] = $mall_info['user_info'];

        return $data;
    }


    public function mall_info()
    {
        $row = $this->help_model->get_info_by_fid(FID);

        $data['mall_info']['mall_name']= $row[0]['fname']??"";
        $data['mall_info']['tel']=$row[0]['ftel']??"";
        $data['mall_info']['addr']=$row[0]['faddress1']??"".$row[0]['faddress2']??"";
        $data['mall_info']['regist_num']=$row[0]['regist_num']??"";

        $data['user_info']['name']=$row[0]['mb_name']??"";
        $data['user_info']['user_id']=$row[0]['mb_id']??"";
        $data['user_info']['tel']=$row[0]['mb_hp']??"";
        $data['user_info']['email']=$row[0]['mb_email']??"";



        return $data;
    }
    public function save($data,$file)
    {
        $rows = $this->model->findbyid(1);
        $row = $rows[0];

        $entity = new Profile_Entity();

        $entity->setNickName($data['nick_name']);

        /*파일업로드*/
        /*파일 등록*/
        if(isset($file['name']) and $file['name'] !="") {//파일 있음
            $this->log->info("파일 등록 : ".$file['name']);
            $oResult = $this->file_upload($file);

            if($oResult['SUCCESS'] == "TRUE") {
                if($oResult['RECORD']['file_path'] !="") $entity->setImgDir($oResult['RECORD']['file_path']);
                if($oResult['RECORD']['file_name'] !="") $entity->setImgName($oResult['RECORD']['file_name']);
                $this->log->info("파일 등록 성공: ".$entity->getImgName());
            }
            else{ //등록파일 업로드 실패
                $this->log->info("파일 등록 성공 실패 : ".$oResult['MSG']);
                return AT_Result::toFailData($oResult['MSG']);
            }
            if(isset($row['img_name']) and $row['img_name']!="") { //로고 추가인데, 기존 자료가 있을때, 기존 파일 삭제
                $file['img_dir'] = $row['img_dir'];
                $file['img_name'] = $row['img_name'];
                $this->log->info("기존 파일 삭제 : ".$file['img_dir'].$file['img_name']);
                $this->file_del($file);
            }
            //저장 또는 수정
        }else{
            if(isset($data['img_del']) and $data['img_del']=="Y"){ //기존파일 삭제일때
                //파일 실삭제
                $file['img_dir'] = $row['img_dir'];
                $file['img_name'] = $row['img_name'];
                $this->file_del($file);
                $this->log->info("파일 삭제 요청 (".$data['img_del'].") :".$file['img_dir'].$file['img_name']);
                $entity->setImgDir("");
                $entity->setImgName("");
            }else { //유지
                //기존 데이터 조회
                $this->log->info("기존 파일 유지  ");
                $entity->setImgDir($row['img_dir']??"");
                $entity->setImgName($row['img_name']??"");
            }
        }
        $entity->setProfileNo(1);
        if($this->model->sql_edit($entity)){
            $this->log->info("query :".$this->model->getQuery()." ture");
            return AT_Result::toSuccessData();
        }else{
            $this->log->info("query :".$this->model->getQuery()." false");
            return AT_Result::toFailData();
        }
        exit;
    }
    private function file_upload($file)
    {
        if(!isset($file['name'])) {return AT_Result::toFailData("파일이 없습니다.");}
        $File_Upload = new File_Upload();
        $files = array();
        $dir =  SHOP_CODE."/basic/"; //.$this->code;
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
}