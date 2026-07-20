<?php

namespace App\Controllers\Admin;

use App\Service\PropertyService;

class Property extends BaseController
{
    private $service;

    public function __construct()
    {

        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("property_api");
        $this->service = new PropertyService($this->log,$this->session_id);

        return $this;
    }

    public function PostSave() //저장 , 수정 통합 -primary_Key 판단 후 자동처리
    {
        $post = $this->request->getPost();
        $this->log->info("save > data : ".serialize($post));
        $oResult = $this->service->exec($post);

        $this->view($oResult);
        exit;
    }




}
/*$_post
    property_no
property_name
building_name
addr_sido
addr_sigungu
addr_dong
addr_road
supply_area
exclusive_area
total_floor
current_floor
room_count
bathroom_count
parking_available
parking_count
completion_date
move_in_date
direction
heating_fuel
heating_type
sale_price
jeonse_price
deposit_price
monthly_rent
loan_amount
maintenance_fee
status
display_yn
summary
description
thumbnail_image
detail_images*/
