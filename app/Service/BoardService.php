<?php

namespace App\Service;

use App\Entity\Board_Entity;
use App\Libraries\Aes;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class BoardService extends CI_Service
{
    private $model;
    private $code;
    private $post_model;

    public function __construct()
    {
        parent::__construct();

        $this->model = model('App\Models\BoardModel',true,$this->db);
    }

    public function set_code($code)
    {
        $this->code = $code;
        return $this;
    }
    public function save($data,$header_file,$footer_file)
    {

        $board = new Board_Entity();

        /*파일 업로드*/
        if(isset($header_file['name']) and $header_file['name']!="") $hResult = $this->file_upload($header_file);
        else $hResult = AT_Result::toSuccessData("",array("file_path"=>"","file_name"=>""));

        if($hResult['SUCCESS'] == "TRUE") {
            if($hResult['RECORD']['file_path'] !="") $board->setHeaderDir($hResult['RECORD']['file_path']);
            if($hResult['RECORD']['file_name'] !="") $board->setHeaderUpload($hResult['RECORD']['file_name']);
        }
        else{ //등록파일 업로드 실패
            return AT_Result::toFailData($hResult['MSG']);
        }

        if(isset($footer_file['name']) and $footer_file['name']!="") $fResult = $this->file_upload($footer_file);
        else $fResult = AT_Result::toSuccessData("",array("file_path"=>"","file_name"=>""));

        if($fResult['SUCCESS'] == "TRUE") {
            if($fResult['RECORD']['file_path'] !="") $board->setFooterDir($fResult['RECORD']['file_path']);
            if($fResult['RECORD']['file_name'] !="") $board->setFooterUpload($fResult['RECORD']['file_name']);
        }
        else{ //등록파일 업로드 실패
            return AT_Result::toFailData($fResult['MSG']);
        }

        $board->setBcode($data['bcode']);
        $board->setBname($data['bname']);
        $board->setHeaderTitle($data['header_title']);
        $board->setFooterTitle ($data['footer_title']);
        $board->setStyle($data['style']);
        $board->setWriter($data['writer']);
        $board->setReader($data['reader']);
        $board->setLister($data['lister']);
        $board->setReply($data['reply']);
        $board->setSecret($data['secret']);

        $bseq = $this->model->sql_save($board);
        if($bseq>0){
            return $bseq;
        }
        return false;
       // $this->model->save($board);
    }

    public function edit($data,$header_file,$footer_file)
    {
        $board = new Board_Entity();
        /*파일 업로드*/
        if(isset($header_file['name']) and $header_file['name']!="") $hResult = $this->file_upload($header_file);
        else $hResult = AT_Result::toSuccessData("",array("file_path"=>"","file_name"=>""));

        if($hResult['SUCCESS'] == "TRUE") {
            if($hResult['RECORD']['file_path'] !="") $board->setHeaderDir($hResult['RECORD']['file_path']);
            if($hResult['RECORD']['file_name'] !="") $board->setHeaderUpload($hResult['RECORD']['file_name']);
        }
        else{ //등록파일 업로드 실패
            return AT_Result::toFailData($hResult['MSG']);
        }

        if(isset($footer_file['name']) and $footer_file['name']!="") $fResult = $this->file_upload($footer_file);
        else $fResult = AT_Result::toSuccessData("",array("file_path"=>"","file_name"=>""));

        if($fResult['SUCCESS'] == "TRUE") {
            if($fResult['RECORD']['file_path'] !="") $board->setFooterDir($fResult['RECORD']['file_path']);
            if($fResult['RECORD']['file_name'] !="") $board->setFooterUpload($fResult['RECORD']['file_name']);
        }
        else{ //등록파일 업로드 실패
            return AT_Result::toFailData($fResult['MSG']);
        }

        $board->setBoardsNo($data['board_no']);
        $board->setBcode($data['bcode']);
        $board->setBname($data['bname']);
        $board->setHeaderTitle($data['header_title']);
        $board->setFooterTitle ($data['footer_title']);
        $board->setStyle($data['style']);
        $board->setWriter($data['writer']);
        $board->setReader($data['reader']);
        $board->setLister($data['lister']);
        $board->setReply($data['reply']);
        $board->setSecret($data['secret']);

        if($this->model->sql_edit($board)){
            return AT_Result::toSuccessData();
        }else
            return AT_Result::toFailData();

    }
    public function get_list($page,$perPage)
    {
        $start = ($page -1)*$perPage;
        $total_count  = $this->model->get_count();
        $row = $this->model->get_list($start,$perPage);
        $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);

    }

    public function after_process($data)
    {
        foreach ($data as $k=>$val)
        {
            $val['reader_text'] = strtolower($val['reader'])=="self" ? "본인만" : ( strtolower($val['reader'])=="member" ? "회원만" :"모두" );
            $val['writer_text'] = strtolower($val['writer'])=="admin" ? "관리자만" : ( strtolower($val['writer'])=="member" ? "회원만" :"모두" );
            $val['lister_text'] =  strtolower($val['lister'])=="self" ? "내꺼만" :"모두" ;
            $val['reply_text'] = $val['reply']=="N" ? "불가" : "가능";
            $val['secret_text'] = $val['secret']=="N" ? "불가" : "가능";

            $data[$k]=$val;
        }
        return $data;
    }
    public function get_by_id($idx)
    {

        $row = $this->model->findbyid($idx);
        return $row;
    }
    public function makecode()
    {
        $code = date("Ymdhis");
        $time = microtime();

        $code = "b".substr(md5($code),5,4).substr(md5($time),-5);
        return $code;
    }
    public function table_copy($seq_no)
    {
        $bcode= $this->makecode();
        $bseq = $this->model->table_copy($seq_no, $bcode);
        if($bseq>0){
            return $bseq;
        }
        return false;
    }
    private function file_upload($file)
    {
        if(!isset($file['name'])) {return ATC_Result::toFailData();}
        $File_Upload = new File_Upload();
        $files = array();
        $dir =  SHOP_CODE."/board/"; //.$this->code;
        // $this->set_dir($dir);

        $oResult = $File_Upload->setTargetDir($dir)->upload($file);

        return $oResult;
    }
    //####################################
    public function get_by_code($code)
    {
        $row = $this->model->get_by_code($code);
        return $row;
    }
    public function get_board($bcode)
    {

        $cachename= $this->shop_code."_board_".$bcode;
        if( ! $row = $this->getCache($cachename) ){

            $row = $this->get_by_code($bcode);
            $this->setCache($cachename,$row);
        }

        $boardFrame['header_text'] =  $row[0]['header_title']??"";
        $boardFrame['header_image'] = $row[0]['header_upload']=="" ? "" : SITE.$row[0]['header_dir'].$row[0]['header_upload'];
        $boardFrame['footer_text'] =$row[0]['footer_title']??"";
        $boardFrame['footer_image'] = $row[0]['footer_upload']=="" ?"" : SITE.$row[0]['footer_dir'].$row[0]['footer_upload'];
        $boardFrame['name'] = $row[0]['bname']??"일반 게시판";
        $boardFrame['perpage'] = $row[0]['perpage'] ?? 10;
        $boardFrame['style'] = $row[0]['style'] ?? 1;
        $acter['write'] = $row[0]['writer']??"every";
        $acter['read'] = $row[0]['reader']??"every";
        $acter['list'] = $row[0]['lister']??"every";
        $acter['reply'] = $row[0]['reply']??"N";
        $acter['secret'] = $row[0]['secret']??"N";

        return array("boardFrame"=>$boardFrame , "acter"=>$acter);
    }
    public function grant($acter)
    {

        //글쓰기 권한 체크.
        if(strtoupper($acter['write']) =="ADMIN" ) {//관리자만 글쓰기 가능
            $data['button']['write'] = false;
        }else if(strtoupper($acter['write']) =="MEMBER" and $is_member) { //회원글쓰기권한에 회원
            $data['button']['write'] = true;
        }else if(strtoupper($acter['write']) =="EVERY" ){ // 모두 글쓰기권한
            $data['button']['write'] = true;
        }else{//회원 글쓰기 권한에 비회원 경우..
            $data['button']['write'] = false;
        }

        //리스트 보기 권한 체크.
        if(strtoupper($acter['list']) =="SELF" ) {//관리자만 글쓰기 가능
            //    내 글만 리스팅함.
            $data['button']['list'] = "SELF";
        }else{// every
            // 모두 리스팅함.
            $data['button']['list'] = "EVERY";
        }

        $data['button']['secret'] = $acter['secret'];
        return $data;
    }

}