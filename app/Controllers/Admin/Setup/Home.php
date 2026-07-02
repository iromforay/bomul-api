<?php

use App\Service\BuilderService;

namespace \App\Controllers\Admin;

class Home extends BaseController
{

    public function __construct()
    {
        $this->vdir="Admin/Boards/";
        $this->service = new BuilderService();
        return $this;
    }

    public function getlist()
    {
        return "index";
    }
}