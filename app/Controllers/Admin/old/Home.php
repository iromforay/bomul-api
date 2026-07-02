<?php


class Home extends \App\Controllers\Admin\BaseController
{
    private  $vdir;
    public function __construct()
    {
        \App\Controllers\Admin\BaseController::__construct();
        $this->vdir="Admin/";
        return $this;
    }

    public function getindex()
    {

        $data=array();
        echo $this->view($this->vdir."index",$data);
    }
}