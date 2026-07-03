<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;

class Map extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        return $this;

    }

    public function getindex()
    {
        $data = array();
        $this->view("",'Map/index',$data);
    }
}