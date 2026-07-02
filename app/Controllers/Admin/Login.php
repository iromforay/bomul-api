<?php

namespace App\Controllers\Admin;

class Login extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        //  $this->vdir="Admin/Menu/";
        $this->service = new MenuService();

        $this->set_debug(1);
        return $this;
    }
    public function login()
    {

    }
}