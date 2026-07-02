<?php

namespace App\Controllers\API;

use App\Service\FrontService;
use App\Service\MenuService;
use App\Service\BasicService;

class Map extends BaseController
{
    private $code = "map";

    public function __construct()
    {
        parent::__construct();
        return $this;
    }

    public function getindex()
    {
        $debug = $this->request->getGet("debug")??0;
        $this->set_debug($debug);

        $data = $this->listing($this->code);

        $data['RECORD']['row'] = $this->process_data($data['RECORD']['row']);
        $this->view($data);
    }
    //데이터 후가공
    public function process_data($row){

        unset($row['menu_no']);
        unset($row['write_date']);
        return $row;
    }
    /*preflight*/
    public function optionsIndex()
    {
        return $this;
    }
}