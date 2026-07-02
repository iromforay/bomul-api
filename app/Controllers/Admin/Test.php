<?php

namespace App\Controllers\Admin;

class Test extends BaseController
{
    public function __construct()
    {
       // parent::__construct();
        return $this;
    }
    public function getindex()
    {
     //   print_r($_SERVER);
        $session_id = $_SERVER['HTTP_AUTHORIZATION']??"";
        $session_id = str_replace("Bearer ","",$session_id);
    //PHP_AUTH_PW
        echo "session_id : ".$session_id."\n";

        exit;
    }
    public function optionsIndex()
    {
        $session_id = $_SERVER['HTTP_AUTHORIZATION']??"";
        $session_id = str_replace("Bearer ","",$session_id);
        //PHP_AUTH_PW
        echo "[options]session_id : ".$session_id."\n";

        exit;
    }
    public function getCheck()
    {
        define("SHOP_CODE","sample");


        $log = $this->getMonoLog("test");

        $log->info("test");
        exit;
    }
}