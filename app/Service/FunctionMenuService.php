<?php

namespace App\Service;

use App\Entity\Add_menu_Entity;
use App\Entity\FunctionMenu_Entity;
use App\Libraries\AT_Result;

class FunctionMenuService extends CI_Service
{
    private $model;
    private $log;

    public function __construct($log="",$session_id)
    {
        parent::__construct($session_id);

        $this->log = $log;
        $this->model = model('App\Models\FunctionMenuModel',true,$this->db);
        return $this;
    }

    public function getMenuName($key)
    {
        $menu = array(
            'property_name' => '매물명 ' ,
            'building_name' => '건물명 ' ,
            'addr_sido' => '시도 ' ,
            'addr_sigungu' => '시군구 ' ,
            'addr_dong' => '읍면동 ' ,
            'addr_road' => '도로명 ' ,
            'supply_area' => '공급면적 ' ,
            'exclusive_area' => '전용면적 ' ,
            'total_floor' => '총층수 ' ,
            'current_floor' => '해당층 ' ,
            'room_count' => '방개수 ' ,
            'bathroom_count' => '화장실수 ' ,
            'parking_available' => '주차가능여부Y ' ,
            'parking_count' => '총주차가능대수 ' ,
            'completion_date' => '준공일 ' ,
            'move_in_date' => '입주가능일 ' ,
            'direction' => '방향 ' ,
            'heating_fuel' => '난방연료 ' ,
            'heating_type' => '난방방식 ' ,
            'sale_price' => '매매가 ' ,
            'jeonse_price' => '전세가 ' ,
            'deposit_price' => '보증금 ' ,
            'monthly_rent' => '월세 ' ,
            'loan_amount' => '융자금 ' ,
            'maintenance_fee' => '관리비 ' ,
            'status' => '거래상태 ' ,
            'display_yn' => '홈페이지노출 ' ,
            'summary' => '한줄소개 ' ,
            'description' => '상세설명 ' ,
            'thumbnail_image' => '대표이미지 ' ,
            'detail_images' => '추가이미지 ' ,
        );
        return $menu[$key];
    }
    public function save(array $post)
    {
        $true = true;
        foreach($post as $key=>$val) {
            $oResult = $this->add_function_menu($key,$val);
            if($oResult['SUCCESS'] != 'TRUE') $true = false;
        }

        if($true){
            return AT_Result::toSuccessData();
        }else{
            return AT_Result::toFailData();
        }

    }
    public function add_function_menu($key,$val)
    {
        if($key != "") {
            $entity = new FunctionMenu_Entity();


            $entity->setFunctionMenuKey($key);
            $entity->setFunctionMenuValue($val);


            $add_func_menu_data = $this->model->findbyKey($entity->getFunctionMenuKey());
            $entity->setFunctionMenuNo($add_func_menu_data['function_menu_no']);

            $this->model->sql_edit($entity);

            if($this->model->sql_edit($entity)){
                return AT_Result::toSuccessData();
            }else{
                return AT_Result::toFailData();
            }
        }else{
            return $this;
        }
        return $this;
    }

    public function getdata()
    {

        $rows= $this->model->getdata();
        $oResult['RECORD'] = $rows;
        return $oResult;
    }

}