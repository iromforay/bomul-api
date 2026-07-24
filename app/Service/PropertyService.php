<?php

namespace App\Service;

use App\Entity\Property_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;

class PropertyService extends CI_Service
{
    private $model;
    private $log;
    private $imgService;
    private $fileUpload;

    public function __construct($log="",$session_id)
    {
        parent::__construct($session_id);

        $this->log = $log;
        $this->model = model('App\Models\PropertyModel',true,$this->db);
        $this->model->getLog($log);
        $this->imgService = new ImgService($log, $session_id);
        $this->fileUpload = new File_Upload();
        return $this;
    }

    // FE에서 넘어온 raw post를 받아 매물 저장 + 대표이미지 반영까지 한번에 처리
    public function save_property($post)
    {
        $main_image    = json_decode($post['main_image'] ?? '', true);
        $detail_images = json_decode($post['detail_images'] ?? '', true) ?? [];
        $del_img       = json_decode($post['del_img'] ?? '', true) ?? [];
        unset($post['main_image'], $post['detail_images'], $post['del_img']);

        $oResult = $this->exec($post);
        $this->log->info("[save_property] result : ".var_export($oResult, true));
        //DB 처리가 성공일때 이미지 처리
        if ($oResult['SUCCESS'] === 'TRUE'){
            $property_no = $oResult['RECORD']['property_no'];
            $target_dir  = SHOP_CODE."/property/".$property_no."/";

            // del_img 먼저 처리  - main 이미지 update 전에 기존 이미지 정보를 베이스로 실삭제 해야하니
            $this->log->info("[del_img] raw=".var_export($del_img, true));
            if (!empty($del_img['main'])) {
                $row = $this->model->findbyid($property_no);
                if (!empty($row[0]['main_image'])) {
                    $deleted = $this->fileUpload->delete(['file_path' => $row[0]['main_path'], 'file_name' => $row[0]['main_image']]);
                    $this->log->info("[del_img][main] file=".rtrim($row[0]['main_path'], '/')."/".$row[0]['main_image']." deleted=".var_export($deleted, true));

                    // 새 main 이미지로 교체하는 경우엔 아래 setMainImage()가 어차피 덮어쓰니 컬럼을 따로 안 비움
                    if (empty($main_image['name'])) {
                        $this->model->setMainImage($property_no, null, null);
                    }
                } else {
                    $this->log->info("[del_img][main] skip - no existing main_image on property_no=".$property_no);
                }
            }
            if (!empty($del_img['img_no'])) {
                foreach ($del_img['img_no'] as $img_no) {
                    $r = $this->imgService->del_image($img_no);
                    $this->log->info("[del_img][detail] img_no=".$img_no." result=".var_export($r, true));
                }
            }
            //main 이미지 처리
            if(!empty($main_image['name'])) {
                $moved = $this->fileUpload->move(SHOP_CODE . "/temp_property/", $main_image['name'], $target_dir);
                $this->log->info("[main_image] move=".var_export($moved, true)." name=".$main_image['name']);
                $this->model->setMainImage($property_no, $main_image['name'], $target_dir);
            }
            //detail 이미지 처리
            foreach ($detail_images as $img) {
                if (empty($img['name'])) continue;
                $r = $this->imgService->move_and_save('property', $property_no, SHOP_CODE."/temp_property/", $img['name'], $target_dir);
                $this->log->info("[detail_image] name=".$img['name']." result=".var_export($r, true));
            }
        }

        return $oResult;
    }

    public function exec($data)
    {
        if ( isset($data['property_no']) && $data['property_no']>0) {
            $this->log->info("[service] exec > type : edit");
            $property_no = $data['property_no'];
            $oResult = $this->edit($data);
        }else{
            $this->log->info("[service] exec > type : save");
            $property_no = $this->save($data); // 성공 시 새 property_no, 실패 시 0
            $oResult = $property_no > 0;
        }
        if($oResult){
            return AT_Result::toSuccessData("", ['property_no' => $property_no]);
        }else{
            return AT_Result::toFailData();
        }
    }
    public function save($data)
    {
        $entity = new Property_Entity();
        $entity->fill($this->filterEntityData($data));
        $result = $this->model->register($entity);
        $this->log->info("[Query] ".$this->model->getQuery());

        return $result;
    }

    public function edit($data)
    {
        $entity = new Property_Entity();
        $entity->fill($this->filterEntityData($data));
        $result =   $this->model->modify($entity);
        $this->log->info("[Query] ".$this->model->getQuery());
        return $result;
    }

    // Property_Entity에 실제 정의된 필드만 통과시킴. detail_images/del_img 등 img 테이블에서
    // 별도 처리되는 값이나 FE의 필드명 오타(공백 등으로 인한 변형 키)가 SQL로 새는 것을 방지.
    private function filterEntityData($data)
    {
        $entity = new Property_Entity();
        return array_intersect_key($data, $entity->toRawArray());
    }

    public function get_list($page=1,$perPage=10,$where="")
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
        if (isset($row[0])) {
            $row[0]['detail_images'] = $this->imgService->get_images('property', $property_no);
        }
        return $row;
    }
    public function after_process($data)
    {
        foreach($data as $k=>$v){
            $value = $v;
            $value['direction_txt'] = $this->model->get_direction($v['direction']);
            $value['heating_fuel_txt'] = $this->model->get_heating_fuel($v['heating_fuel']);
            $value['heating_type_txt'] = $this->model->get_heating_type($v['heating_type']);
            $value['status_txt'] = $this->model->get_status($v['status']);
            $value['display_yn_txt'] = $this->model->get_display_yn($v['display_yn']);
            $value['property_type_txt'] = $this->model->get_property_type($v['property_type']);
            $value['transaction_type_txt'] = $this->model->get_transaction_type($v['transaction_type']);

            $value['description'] = html_entity_decode($v['description']);
            if(!empty($v['main_image']) && !empty($v['main_path'])) {
                $value['main_image_url'] = SITE_PDS.rtrim($v['main_path'], '/')."/".$v['main_image'];
            }
            $property_list[$k] =  $value;
        }
        return $property_list;
    }
}