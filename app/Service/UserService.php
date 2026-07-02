<?php

namespace App\Service;

use CodeIgniter\Config\Services;
use App\Entity\Login_SessionEntity;
use App\Libraries\AT_Cookie;
use App\Libraries\AT_Result;
use App\Libraries\Curl;

class UserService
{
    private $model,$help_model;
    private $log;
    public function __construct($log)
    {
        defined('AES_KEY') || define('AES_KEY', $this->shop_code . "_" . getenv('AES_KEY'));
        $this->log = $log;
        $this->db = db_connect("default");

        $this->model = model('App\Models\LoginModel',true, $this->db );
        $this->help_model = model('App\Models\HelpModel',true, $this->db );

        return $this;
    }
    public function login($user_id,$password)
    {

            //새롭게 bomul databases에서 직접 로그인 체크함.
        $log_row = $this->model->adminLogin($user_id);

            /* 기존 방식 curl로 로그인 함.
            $curl = new Curl();
            $host = "https://help.all-teaching.com/User/Login/cafeLogin";
            $curl->set_url($host)->set_method("POST")->setdata("userid", $user_id)->setdata("password", $password);
            $this->log->info("login connetction host:" . $host . ", userid : " . $user_id);
            $curl->exec();
            $log_row = $curl->return_data();
            */
            $this->log->info("help reuslt :" . json_encode($log_row, JSON_UNESCAPED_UNICODE));

        if(isset($log_row['SUCCESS']) and $log_row['SUCCESS']=="TRUE") { //로그인 정보 확인
            $record = (array)$log_row['RECORD'];
            $password = $this->password_hash($password);
            if (isset($record['user_pwd']) and $password === $log_row['RECORD']['user_pwd']){
                // 로그인 성공 - db세션 값 등록
                $session_id=$this->makeSessionKey($record['member_no']);
                $data = serialize($record);
                $entity = new Login_SessionEntity();
                $entity->setFid($record['fid']);
                $entity->setData($data);
                $entity->setSessionId($session_id);
                $entity->setStatus("Y");
                $session_no = $this->model->sql_save($entity); //DB세션 저장.

                $this->saveCache($session_id, serialize($record));//파일세션에 기록

                $at_cookie = new AT_Cookie();
                $at_cookie->burn_cookie($data);
                $return_login_data = array("session_id" => $session_id, "fid" => $record['fid']);
                $this->log->info("login return data " . json_encode($return_login_data, JSON_UNESCAPED_UNICODE));
                return AT_Result::toSuccessData($return_login_data);
        }else{ //로그인 실패
                $this->log->info("login file  > 패스워드가 틀림  ");
                return AT_Result::toFailData("패스워드가 틀렸습니다.");
                exit;
        }

        }else{
            $msg = $log_row['MSG']??"";
            $this->log->info("login file  > msg : ".$msg);
            return AT_Result::toFailData($log_row['MSG']??"로그인에 실패하셨습니다.",$log_row['CODE']??"");
        }
        exit;
        return $log_row;
    }

    public function session_login($session_id)
    {
        $this->log->info("session_login > session_id :".$session_id);
        /*임시 로그인 통과*/
        if($session_id =="foray" || $session_id=="sample"){
            $expire_date = date("YmdHis",strtotime("+1day"));
            $row = array("status"=>"Y","expire_date"=>$expire_date,"fid"=>$session_id);
        }else {
            $row = $this->help_model->getSession($session_id);
        }
        $this->log->info("session_login return data ".json_encode($row,JSON_UNESCAPED_UNICODE));
        if(isset($row['status']) and $row['status'] =="Y"){ //로그인
            $now = date("YmdHis");
            if(isset($row['expire_date']) and  $row['expire_date'] > $now){//만료일 인정
                $this->log->info("session_login > session_id :".$session_id." 로그인 성공 ");
              $oResult =  AT_Result::toSuccessData(array("session_id"=>$session_id, "fid"=>$row['fid']));
            }else{//만료일 초과
                $this->log->info("session_login > session_id :".$session_id." 시간 초과 ");
                $oResult = AT_Result::toFailData("시간 초과");
            }
        }else{//로그아웃됨
            $this->log->info("session_login > session_id :".$session_id." 로그아웃 상태 ");
            $oResult = AT_Result::toFailData("로그인 안됨");
        }
        return $oResult;
    }

    private function saveCache($cachename,$data)
    {
        $cache = Services::cache();
        $chche_time = 60 * 50;
        cache()->save($cachename, $data, $chche_time);
        return $this;
    }

    public function password_hash($password)
    {
        return  hash('sha256', $password);
    }

    function makeSessionKey($memberNo): string
    {
        return hash(
            'sha256',
            $memberNo .
            random_bytes(32) .
            microtime(true)
        );
    }
}