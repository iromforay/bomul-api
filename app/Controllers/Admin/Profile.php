<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\ProfileService;

class Profile extends BaseController
{
    private $service;
    private $log;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_auth();

        $this->log = $this->getMonoLog("profile_api");
        $this->service = new ProfileService($this->log,$this->session_id);
        $this->set_debug(0);
        return $this;
    }

    public function getData()
    {
        $this->log->info("[REV] get DATA > session_id:".$this->session_id);
        $row = $this->service->get_by_profile();

        $oResult = AT_Result::toSuccessData("",$row);
        $this->log->info("[RES] get DATA > result : ".($oResult['SUCCESS']?"T":"F"));
        $this->view($oResult);
    }
    public function postSave()
    {
        $this->log->info("[REV] save : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        if(isset($_FILES['img'])) {
            $file = $_FILES['img'];
        }else{
            $file=null;
        }
        $data['nick_name']  = $this->request->getVar("nick_name");
        $data['img_del']    = $this->request->getVar("img_del");
        $oResult = $this->service->save($data,$file);

        $this->log->info("[RES] Profile save > result : ".($oResult['SUCCESS']?"T":"F"));
        $this->view($oResult);
    }

    /*preflight*/
    public function optionsData()
    {
        return $this;
    }

    public function optionsSave()
    {
        return $this;
    }
}