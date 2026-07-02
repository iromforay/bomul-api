<?php

namespace App\Controllers;



use App\Service\PageService;

class Page extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new PageService();
        return $this;
    }

    public function getData()
    {
        $debug = $this->request->getGet("debug")??0;
        $this->set_debug($debug);
        $code = $this->request->getGet("code")??0;
        $oResult = $this->service->getBycode($code);
        $this->view($oResult);
    }

}