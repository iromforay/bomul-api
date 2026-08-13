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
      //  echo $this->model->getQuery();exit;
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
                // 로그인 성공
                return $this->issueLoginSession($record);
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

    /*
     * ad -> FE -> bo 로 릴레이되는 자동 로그인.
     * ad가 hash_hmac('sha256', $id.$time, $pwd_hash.$salt) 로 만든 session_token을
     * bo에서 동일한 공식(pwd_hash는 bomul.member.user_pwd, salt는 RECEIVER_API_KEY)으로
     * 재계산해서 비교한다 - stateless라 저장/재사용 방지 로직은 없음(30초 초과시 거부로 리스크 완화).
     */
    public function session_login_by_token($user_id, $time, $session_token)
    {
        $this->log->info("session_login_by_token > user_id :".$user_id.", time :".$time);

        if($time === null || $time === "" || abs(time() - (int)$time) > 30){
            $this->log->info("session_login_by_token > user_id :".$user_id." 시간 초과 ");
            return AT_Result::toFailData("시간이 초과되었습니다.");
        }

        $log_row = $this->model->adminLogin($user_id);
        if(!isset($log_row['SUCCESS']) or $log_row['SUCCESS']!=="TRUE"){
            $this->log->info("session_login_by_token > user_id :".$user_id." 존재하지 않는 ID ");
            return AT_Result::toFailData($log_row['MSG']??"존재하지 않는 ID 입니다.");
        }

        $record = (array)$log_row['RECORD'];
        $pwd_hash = $record['user_pwd']??'';
        $salt = env('RECEIVER_API_KEY', '');
        $expected_token = hash_hmac('sha256', $user_id.$time, $pwd_hash.$salt);

        if(!hash_equals($expected_token, (string)$session_token)){
            $this->log->info("session_login_by_token > user_id :".$user_id." token 불일치 ");
            return AT_Result::toFailData("인증에 실패했습니다.");
        }

        $this->log->info("session_login_by_token > user_id :".$user_id." 인증 성공 ");
        return $this->issueLoginSession($record);
    }

    private function issueLoginSession($record)
    {
        $session_id = $this->makeSessionKey($record['member_no']);
        $data = serialize($record);
        $entity = new Login_SessionEntity();
        $entity->setFid($record['fid']);
        $entity->setData($data);
        $entity->setSessionId($session_id);
        $entity->setStatus("Y");
        $this->model->sql_save($entity); //DB세션 저장.

        $this->saveCache($session_id, serialize($record));//파일세션에 기록

        $at_cookie = new AT_Cookie();
        $at_cookie->burn_cookie($data);
        $return_login_data = array("session_id" => $session_id, "fid" => $record['fid']);
        $this->log->info("issueLoginSession return data " . json_encode($return_login_data, JSON_UNESCAPED_UNICODE));
        return AT_Result::toSuccessData($return_login_data);
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