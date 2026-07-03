<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        return $this;

    }

    public function getindex()
    {
        $data = array();
        $this->view("",'Home/index',$data);
    }

    public function index()
    {
        dd("web index");
    }

}