<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;

class Map extends BaseController
{
    private $log;
    public function __construct()
    {
        $this->set_code("map");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());
        return $this;

    }

    public function getindex()
    {
        $data=[];
//dd($this->getSubBody());
        $this->view("",'Map/index',$data);
    }
}