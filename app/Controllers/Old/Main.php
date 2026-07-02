<?php

namespace App\Controllers\Old;
use App\Service\MainService;

class Main extends BaseController
{
    private $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new MainService();
        return $this;
    }
    public function getindex()
    {
        $data = $this->service->getAll();


        $data=array("data"=>$data);

        $this->view("default","main/index",$data);
    }

}