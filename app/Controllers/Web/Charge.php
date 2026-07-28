<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;

class Charge extends BaseController
{
    public function __construct()
    {
        $this->set_code("charge");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());
        return $this;

    }

    public function getindex()
    {
        $data=[];
        $this->view("",'Charge/index',$data);
    }
}