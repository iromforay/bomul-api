<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;

class Review extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        return $this;

    }

    public function getindex()
    {
        $data = array();
        $this->view("",'Review/index',$data);
    }
}