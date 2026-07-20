<?php

namespace App\Service;

use App\Entity\Property_Entity;
use App\Libraries\AT_Result;

class PropertyService extends CI_Service
{
    private $model;
    private $log;
    public function __construct($log="",$session_id)
    {
        parent::__construct($session_id);

        $this->log = $log;
        $this->model = model('App\Models\PropertyModel',true,$this->db);
        return $this;
    }
    public function exec($data)
    {
        if ( isset($data['property_no']) && $data['property_no']>0) {
            $this->log->info("[service] exec > type : edit");
            $oResult = $this->edit($data);
        }else{
            $this->log->info("[service] exec > type : save");
            $oResult = $this->save($data);
        }
        if($oResult){
            return AT_Result::toSuccessData();
        }else{
            return AT_Result::toFailData();
        }
        exit;
    }
    public function save($data)
    {
        $entity = new Property_Entity();
        $entity->fill($data);
        $result = $this->model->register($entity);
        $this->log->info("[Query] ".$this->model->query);
    return $result;
        exit;
    }

    public function edit($data)
    {
        $entity = new Property_Entity();
        $entity->fill($data);
        $result =   $this->model->modify($entity);
        $this->log->info("[Query] ".$this->model->query);
        return $result;
        exit;
    }


    public function get_list($page,$perPage,$where="")
    {
        $start = ($page -1)*$perPage;

        $total_count  = $this->model->get_count();
       // $this->log->info("매물 count Query : ".$this->model->getQuery());
        $row = $this->model->get_list($start,$perPage,$where);

        $this->log->info("board list Query : ".$this->model->getQuery());
        $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);
    }
    public function getbyid($property_no)
    {
        $row = $this->model->findbyid($property_no);
        $row = $this->after_process($row);
        return $row;
    }
    public function after_process($data)
    {
        foreach($data as $k=>$v){

            $value['direction_txt'] = $this->model->get_direction($v['direction']);
            $value['heating_fuel_txt'] = $this->model->get_heating_fuel($v['heating_fuel']);
            $value['heating_type_txt'] = $this->model->get_heating_type($v['heating_type']);
            $value['status_txt'] = $this->model->get_status($v['status']);
            $value['display_yn_txt'] = $this->model->get_display_yn($v['display_yn']);

            $value['description'] = html_entity_decode($v['description']);
            $value['thumbnail_image'] =SITE_PDS.$v['file_dir'].$v['file_name'];
            $value['detail_images'] =SITE_PDS.$v['file_dir'].$v['file_name'];

            $property_list[$k] =  $value;
        }
        return $property_list;
    }
}