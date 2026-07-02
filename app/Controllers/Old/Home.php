<?php

namespace App\Controllers\Old;

class Home extends BaseController
{
    public function getindex()
    {
        echo "home";


    }
    public function index()
    {

        $data = array();
        $this->view("",'Home/index',$data);
    }
}
