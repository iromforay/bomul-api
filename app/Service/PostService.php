<?php

namespace App\Service;

use App\Libraries\Aes;
use App\Libraries\AT_Result;
use App\Entity\Post_Entity;
use App\Libraries\File_Upload;

class PostService extends CI_Service
{
    protected $code;
    protected $model;
    protected $aes;
    private $log;
    /**
     * @param $model
     */
    public function __construct($log,$session_id)
    {
        parent::__construct($session_id);
       // $this->aes =  new Aes();
        $this->log=$log;
        $this->model = model('App\Models\PostModel',true,$this->db);
        return $this;
    }
    public function get_csrf($bno=0,$limit=120)
    {
        $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+|';
        $salt = substr(str_shuffle($characters), 0, 5);
        $time = time();
        $hash = md5($bno.$salt.$time.$limit);
        $code = array(
            'bno'=>$bno,
            "salt"=>$salt,
            "time"=>$time,
            "limit"=>$limit,
            'hash' =>$hash
        );
        $text = serialize($code);
        $this->aes->set_key(AES_KEY);
        $enc_string = $this->aes->encrypt($text);

        return $enc_string;
    }
    public function verify($bno=0,$hash)
    {
        // 값 검증
        $this->aes->set_key(AES_KEY);
        $text = $this->aes->decrypt($hash);

        return $this->csrf_verify($bno,unserialize($text));

    }
    public function csrf_verify($_bno,$code)
    {
        $at_result  = new AT_Result();
        $salt = $code['salt'];
        $time = $code['time'];
        $bno = $code['bno'];
        $limit = $code['limit'];
        $hash = md5($bno.$salt.$time.$limit);

        //발급한 게시물 체크
        if($bno !== $_bno) return AT_Result::toFailData('다시 시도하세요','BE01');
        //시간 체크
        else if(time() >$time+$limit) return AT_Result::toFailData('다시 시도하세요','BE02');
        else if($hash === $code['hash'] or $hash === $code['HASH']) return AT_Result::toSuccessData();
        else return AT_Result::toFailData('다시 시도하세요','BE03');
    }

    public function setcode($code="")
    {
        $this->code = $code;

        return $this;
    }

    public function get_list_grant($data)
    {
        return $this;
    }
    public function getbyid($post_no)
    {
        $row = $this->model->findbyid($post_no);
        return $row;
    }
    public function get_keyword($keyword="")
    {
        $_keyword = array(
            "title"=>"subject",
            "content"=>"content",
            "writer"=>"name"
        );

        if($keyword !="")
            return $_keyword[$keyword];
        else return "secret";
    }

    public function board_total_count()
    {
        $rows = $this->model->board_total_count();
        $_board = array();
        foreach ($rows as $v) {
            if($v['bcode'] == "event" || $v['bcode'] == "notice"){
                $_board['notice']  = $_board['notice']??0;
                $_board['notice'] += $v['total_count'];
            }else {
                $_board[$v['bcode']] = $v['total_count'];
            }
        }

        $total=0;
        foreach($this->model->get_all_board() as $v){
         if(isset($_board[$v])) $board[$v]['total_count'] =$_board[$v];
         else $board[$v]['total_count'] = 0;
         $total += $board[$v]['total_count'];
        }
        $board['around']['total_count']=$total;
        return $board;

    }
    public function get_list($page,$perPage,$search,array $where =[])
    {

        $start = ($page -1)*$perPage;
        if($this->code == "notice") {
            $code[] = $this->code;
            $code[] = "event";
        }elseif($this->code =="all"){
            $code = "";
        }else{
            $code = $this->code;
        }
    if(isset($search['keyword']) and $search['keyword'] !="" and isset($search['txt']) and $search['txt']!="") {
        $where[] = $this->get_keyword($search['keyword']) . " like '%" . $search['txt'] . "%'";
        $where[]="status=1";
    }else {
        $where[]="status=1";
    }

        $total_count  = $this->model->setCode($code)->get_count($where);


        $row = $this->model->get_list($start,$perPage,$where);
        $this->log->info("query :".$this->model->getQuery());
        $this->log->info("result count :".count($row));
        if(count($row)>0)
            $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);
    }


    //수정가능 권한 체크 및 데이터 리턴
    public function is_modify($post_no)
    {
        $row = $this->getbyid($post_no);
        /*체크*/
        if($row['user_id']!=""){ //회원등록글이면
            if($row['user_id']  == $_SESSION['user_id']) {//등록자와 로그인 ID가 같다면 패스
                AT_Result::toSuccessData($row);
            }else{ //다르면 list로 리턴
                AT_Result::toFailData("","WRONG_AUTH");
            }

        }else{ //비회원 등록글이면
            AT_Result::toFailData("","IN_PWD");
            /*
            1-1 패스워드 비교!!
                1-1-1 같으면 패스
                1-1-2 다르면 list로 리턴
        */
        }

    }

    public function after_process($data)
    {
        foreach($data as $k=>$v){
            $value['board_no'] = $v['post_no'];
            $value['board_type'] = $v['bcode'];
            $value['score'] = $v['score']??"";
            $value['board_title'] = $v['subject'];
            $value['board_writer'] = $v['name'];
            $value['board_date'] = $v['write_date'];
            $value['board_hit'] = $v['view_count']??0;
            $value['board_status'] = $v['status'] ? "Y" : "N" ;

            $value['board_content'] = html_entity_decode($v['content']);

            if($v['bcode']=="review") {
                $value['score'] = $v['add01'];
            }
            if($v['bcode'] =="vod") {
                $value['movie_url'] = $v['add02'];
                $value['file_name'] = $v['orgin_name'];
                $value['file'] = SITE_PDS.$v['file_dir'].$v['file_name'];
            }


            $board_list[$k] =  $value;
        }

        return $board_list;
    }
    public function save($data=null,$file)
    {
        $entity = new Post_Entity();
        $data['title'] = esc($data['title']);
        $data['content'] = esc($data['content']);

        /*entity 주입*/
        $entity->setSubject($data['title']);
        $entity->setContent($data['content']);
        $entity->setName($data['name']);
        $entity->setBcode($data['bcode']);
        $entity->setStatus("1");

        $entity->setAdd01($data['score']??""); //별점
        $entity->setAdd02($data['movie_url']??""); //영상url
/*
        $entity->setSecret($data['secret']);
        $entity->setPassword($this->password($data['password']));
        $entity->setTel($data['tel']);
*/

        /*파일 등록*/
        if(isset($file['name']) and $file['name'] !="") {//파일 있음
            $this->log->info("첨부파일  존재",$file);
            $oResult = $this->file_upload($file);

            if($oResult['SUCCESS'] == "TRUE") {
                $this->log->info("업로드 성공",$oResult);
                $entity->setOrginName($file['name']);
                if($oResult['RECORD']['file_path'] !="") $entity->setFileDir($oResult['RECORD']['file_path']);
                if($oResult['RECORD']['file_name'] !="") $entity->setFileName($oResult['RECORD']['file_name']);
            }
            else{ //등록파일 업로드 실패
                $this->log->info("업로드 실패",$oResult);
                return AT_Result::toFailData($oResult['MSG']);
            }
            if(isset($row['file_dir']) and $row['file_name']!="") { // 추가인데 기존 자료가 있을때, 기존 파일 삭제
                $file['img_dir'] = $row['file_dir'];
                $file['img_name'] = $row['file_name'];
                $this->log->info("기존 파일 삭제",$file);
                $this->file_del($file);
            }
            //저장 또는 수정
        }else{
            $this->log->info("첨부파일  없음");
            $entity->setOrginName("");
            $entity->setFileDir("");
            $entity->setFileName("");
        }

        $post_no = $this->model->sql_save($entity);
      //  $this->log->info("query :".$this->model->getQuery());
        if($post_no){
            $this->log->info("query :".$this->model->getQuery()." true");
            return AT_Result::toSuccessData();
        }else{
            $this->log->info("query :".$this->model->getQuery()." false");
            return AT_Result::toFailData();
        }
    }
    public function del($post_no)
    {
        $entity = new Post_Entity();

        $entity->setPostNo($post_no);
        if($this->model->sql_del($entity)){
            $this->log->info("query :".$this->model->getQuery()." ture");
            return AT_Result::toSuccessData();
        }else{
            $this->log->info("query :".$this->model->getQuery()." false");
            return AT_Result::toFailData();
        }
    }
    public function edit($data,$file)
    {
        $entity = new Post_Entity();
        $data['title'] = esc($data['title']);
        $data['content'] = esc($data['content']);
        $rows = $this->model->findbyid($data['post_no']); $row = $rows[0];
        /*entity 주입*/
        $entity->setPostNo($data['post_no']);
        $entity->setSubject($data['title']);
        $entity->setContent($data['content']);
        $entity->setName($data['name']);
        $entity->setStatus("1");
        $entity->setAdd01($data['score']??""); //별점
        $entity->setAdd02($data['movie_url']??""); //영상url

        /*파일 등록*/
        if(isset($file['name']) and $file['name'] !="") {//파일 있음

            $oResult = $this->file_upload($file);

            if($oResult['SUCCESS'] == "TRUE") {
                $entity->setOrginName($file['name']);
                if($oResult['RECORD']['file_path'] !="") $entity->setFileDir($oResult['RECORD']['file_path']);
                if($oResult['RECORD']['file_name'] !="") $entity->setFileName($oResult['RECORD']['file_name']);
            }
            else{ //등록파일 업로드 실패
                return AT_Result::toFailData($oResult['MSG']);
            }
            if(isset($row['file_dir']) and $row['file_name']!="") { // 추가인데 기존 자료가 있을때, 기존 파일 삭제
                $file['img_dir'] = $row['file_dir'];
                $file['img_name'] = $row['file_name'];
                $this->file_del($file);
            }
            //저장 또는 수정
        }else{
            if(isset($data['file_del']) and $data['file_del']=="Y"){ //기존파일 삭제일때
                //파일 실삭제
                $file['img_dir'] = $row['file_dir'];
                $file['img_name'] = $row['file_name'];
                $this->file_del($file);

                $entity->setOrginName("");
                $entity->setFileDir("");
                $entity->setFileName("");
            }else { //유지
                //기존 데이터 조회
                $entity->setOrginName($row['orgin_name']??"");
                $entity->setFileDir($row['file_dir']??"");
                $entity->setFileName($row['file_name']??"");
            }
        }

        /*
                $entity->setSecret($data['secret']);
                $entity->setPassword($this->password($data['password']));
                $entity->setTel($data['tel']);
        */
        if($this->model->sql_edit($entity)){
            $this->log->info("query :".$this->model->getQuery()." ture");
            return AT_Result::toSuccessData();
        }else{
            $this->log->info("query :".$this->model->getQuery()." false");
            return AT_Result::toFailData();
        }
    }

    public function password($text)
    {
        $password = $text;

        return $password;
    }
    public function is_auth($data,$password="")
    {
        //비밀글 여부 확인
        if($data['secret']=="T"){//비밀글일때 , 쓰니가 회원인가?
            if($data['user_id']==""){//비회원이쓴 비밀글은 반드시 비번을 입력 받아야함.
                if($password=="") {
                    return AT_Result::toFailData("패스워드를 입력하세요", "IN_PWD");
                }else if($data['password'] == $this->password($password) ) { //비번 확인
                    return AT_Result::toSuccessData();
                }else{
                    return AT_Result::toFailData("패스워드가 틀렸습니다.", "IN_PWD");
                }
            }else if($data['user_id'] == $_SESSION['user_id']){ //회원이쓴 비밀글은 글쓴 회원이라면 읽을수 있다.
                return AT_Result::toSuccessData();
            }
        }else{ //아닐때
            return AT_Result::toSuccessData();
        }
    }
    private function file_upload($file)
    {
        if(!isset($file['name'])) {return AT_Result::toFailData("파일이 없습니다.");}
        $File_Upload = new File_Upload();
        $files = array();
        $dir =  SHOP_CODE."/board/"; //.$this->code;
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