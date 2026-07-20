<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Entity\FunctionMenu_Entity;
use App\Libraries\AT_Result;
use App\Service\FunctionMenuService;

class FunctionMenu extends BaseController
{
    private $service;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("menu_api");
        //  $this->vdir="Admin/Menu/";
        $this->service = new FunctionMenuService($this->log,$this->session_id);

        return $this;
    }

    public function postSave_property()
    {
        $post = $this->request->getPost();

        $oResult = $this->service->save($post);

            $this->view($oResult);
        exit;

    }

    public function getData()
    {
        $rows = $this->service->getdata();

        echo AT_Result::toJson($rows);
        exit;
    }
}