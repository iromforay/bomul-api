<?php

namespace App\Controllers;

use App\Service\TestService;
class Test extends BaseController
{
private $service;
    public function __construct()
    {
        parent::__construct();

        $this->service = new TestService();
    }

    public function getindex()
    {

        $data=null;
      //  $data['layout'] = $this->layout;
       // echo render("test",$data);
        $data['num']  =1;

        return render("test",$data);
     //   return view('test',$data);
    }

    public function gettest($idx)
    {

        $r = $this->service->get($idx);
        dd($r);
      //  return "test";
    }
}