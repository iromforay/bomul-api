<?php

namespace App\Controllers\API\old;

use App\Controllers\API\BaseController;
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
        $this->getData();
    }
    public function getData()
    {
        $debug = $this->request->getGet("debug")??0;
        $this->set_debug($debug);

        $data = $this->service->apiAll();


        $this->view($data);
    }

}