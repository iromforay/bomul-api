<?php

namespace App\Controllers\Admin;

class Home extends BaseController
{
    public function getindex()
    {
        $data=array();
        echo $this->view("Admin/Service/index",$data);
    }
}