<?php

namespace App\Controllers\Admin;
use App\Libraries\Aes;
use App\Libraries\AT_Cookie;
use App\Libraries\AT_Result;
use App\Service\UserService;

class User extends BaseController
{
    private $service;
    private $log;
    public function __construct()
    {
        parent::__construct();
        $this->log = $this->getMonoLog("login");

        $this->service = new UserService($this->log);

        return $this;
    }
    public function postlogin()
    {

        $user_id = trim($this->request->getVar("user_id"));
        $password = trim($this->request->getVar("password"));
        $this->log->info("login user_id:".$user_id.", password : ".$password);
        $debug = $this->request->getVar("debug")??0;
        $this->set_debug($debug?1:0);
       $loginResult = $this->service->login($user_id,$password);
        $this->log->info("login user_id:".$user_id.", result : ".$loginResult['SUCCESS']=="TRUE"? "Y": "N");
        if($loginResult['SUCCESS']=="TRUE") {
            $this->log->info("login info user_id:" . $user_id . ", session_id : " . $loginResult['MSG']['session_id']. ", fid : " . $loginResult['MSG']['fid'] );
        }
        $this->log->info("result",$loginResult);
       $this->view($loginResult);
    }

    public function postsessionLogin()
    {
        $id = trim($this->request->getVar("id"));
        $time = trim($this->request->getVar("time"));
        $session_token = trim($this->request->getVar("session_token"));

        $this->log->info("sessionLogin id:".$id.", time:".$time);

        $oResult = $this->service->session_login_by_token($id, $time, $session_token);

        $this->log->info("sessionLogin info result : ".json_encode($oResult,JSON_UNESCAPED_UNICODE));
        echo AT_Result::toJson($oResult);
        exit;
    }
    /*
     * public function getSession()
    {
        $cookie = new AT_Cookie();

        echo "Session_login";

        $_cookie = $cookie->getCookie();
        d($_cookie);
    }*/

    public function gettest()
    {
        /*
        $aes = new Aes();
        $text="pDb1YZUoMGYiDblzupOon/cDx2wYUg87+M2BBEnG8sxjfFHAkmgF+XnUrHrXbBfPXP/OyjCtrSo6RqMVN+8J2D/QMV5T0Az7reC6Q06nqfWgjIZyLC6Sb/ntPPczM+32W2DBRl7CiXNs13HziEftiBVvuzoCSl7w7VjfvgGD+yT86pYWypUAG6PLqJs5f7uYuUivvDf9JqjzRFqyAacmbzXjAy32iJmgXbojTUxZ+t7P114DFDKjjPH2ef48ao5pMnJjYyzNohBZgu58VGISwNv8c+/R5eUACGUuz4w5KElYl0sQz8/ojpzckWEfddYI329KNQpmzGIxxYj5rxfJYg==";


       $data['a']="ao";
        $data['b']="123";
        $serial_data = serialize($data);
        $aes->set_key(AES_KEY);
        $ciphertext = $aes->encrypt($serial_data);
        $text = $ciphertext;
        $aes->set_key(AES_KEY);
        $desrypt = $aes->decrypt($text);

        $text = unserialize($desrypt);

//d($text);
        $plaintext="a";
        $cipher = "aes-256-cbc";

        // Generate initialization vector
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, AES_KEY, OPENSSL_RAW_DATA, $iv);
        $enc = base64_encode($iv . $ciphertext);
        d($ivlen);
        d($iv);
        d(AES_KEY);
        d($ciphertext);
        d($enc);
        exit;
        */
    }

    /*preflight*/
    public function getis_login()
    {
        echo "is login";
        exit;
    }
    public function optionslogin()
    {
        return $this;
    }
    public function optionsis_login()
    {
        return $this;
    }
}