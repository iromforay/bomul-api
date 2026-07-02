<?php

namespace App\Service;

use App\Libraries\AT_Result;

class FrontService extends CI_Service
{
    private $model;
    private $Imgmodel;

    public function __construct()
    {
        parent::__construct();

        $this->model = model('App\Models\MenuModel',true,$this->db);
        $this->Imgmodel = model('App\Models\ImgModel',true,$this->db);

    }

    public function getBycode($code)
    {
        $row = $this->model->findbyCode($code);
        $menu_no = $row['row']['menu_no'];
        $img = $this->Imgmodel->get_image($menu_no);
        $row['img'] = $img;

        return $row;
    }


    public function getButton_menu()
    {
        $rows = $this->model->get_button_menu();
        $oResult = AT_Result::toSuccessData("", $rows);
        return $oResult;
    }
}