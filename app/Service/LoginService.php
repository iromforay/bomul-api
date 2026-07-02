<?php

namespace App\Service;

use App\Libraries\AT_Cookie;

class LoginService extends CI_Service
{
    public function __construct()
    {
        parent::__construct();

        $this->model = model('App\Models\BuilderModel',true,$this->db);
        $this->Postmodel = model('App\Models\PostModel',true,$this->db);
        $this->Pagermodel = model('App\Models\PageModel',true,$this->db);
    }
    public function is_login()
    {
        $cookie = new AT_Cookie();
        $login_cookie =  $cookie->getCookie();
d($login_cookie);
        if(isset($login_cookie['IS_LOGIN']) && $login_cookie['IS_LOGIN']) {
            $checksum = $cookie->hash_check($login_cookie);

            if($checksum){
                //세션 기록
                $session = session();

                $login=array(
                    "is_login" => true,
                    "MB_ID" => $login_cookie['MB_ID'],
                    "login_time" =>microtime()
                );

                $session->set(array("login"=>$login));
                return true;
            }
            else {
                //세션 삭제

                return false;
            }
        }else{
            return false;
        }
    }
}