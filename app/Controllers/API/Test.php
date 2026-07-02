<?php

namespace App\Controllers\API;

use App\Service\TestService;
class Test extends BaseController
{
    //private $code = "test";
    public function __construct()
    {
      //  parent::__construct();
        return $this;
    }

    public function getindex()
    {
     echo "TEST PAGE";
     exit;
    }

    public function getcheck()
    {
        define("SHOP_CODE","sample");


        $log = $this->getMonoLog("test");

           $log->info("test");
        exit;
    }

    public function getcheck1()
    {
        echo "check1"."<BR>";
        $service = new TestService();

        $service->test();
    }
}