<?php

namespace App\Service;

use App\Entity\Property_Entity;
use App\Libraries\AT_Result;
use App\Libraries\File_Upload;
use App\Libraries\NaverGeocoder;

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

        $post = $this->resolveLatLng($post);

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

    // BO 화면에서 주소검색(Admin\Map) 위젯으로 이미 확인된 lat/lng을 그대로 실어보내는 게 기본
    // 경로 - FE가 확인한 좌표를 서버가 다시 지오코딩해서 덮어쓰면 사용자가 고른 것과 달라질 수
    // 있으므로 post에 lat/lng이 오면 그대로 믿고 쓴다(Property_Entity 컬럼명인 latitude/longitude로
    // 매핑만 함). 아직 그 위젯을 안 붙였거나 값이 비어오면, 주소 필드로 서버가 직접 지오코딩해서
    // 채워주는 걸로 폴백한다 - 실패해도(주소 못 찾음 등) 저장 자체는 계속 진행한다(지도 표시는
    // 부가기능이지 매물 저장을 막을 이유가 아님). Property/view.php가 latitude/longitude 있을
    // 때만 지도를 보여주므로 값이 없어도 안전하다.
    private function resolveLatLng($post)
    {
        if (!empty($post['lat']) && !empty($post['lng'])) {
            $post['latitude'] = $post['lat'];
            $post['longitude'] = $post['lng'];
            unset($post['lat'], $post['lng']);
            return $post;
        }

        $address = trim(($post['addr_sido'] ?? '') . ' ' . ($post['addr_sigungu'] ?? '') . ' ' . ($post['addr_dong'] ?? '') . ' ' . ($post['addr_road'] ?? ''));
        if ($address === '') {
            return $post;
        }

        $geo = NaverGeocoder::geocode($address);
        if ($geo !== null) {
            $post['latitude'] = $geo['lat'];
            $post['longitude'] = $geo['lng'];
            $this->log->info('[REV] property 주소 지오코딩 성공 address=' . $address . ' lat=' . $geo['lat'] . ' lng=' . $geo['lng']);
        } else {
            $this->log->info('[REV] property 주소 지오코딩 실패 address=' . $address);
        }

        return $post;
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

    // NoteService::status_total_count()와 동일한 패턴 - 거래상태별(거래가능/거래중/거래완료/거래보류/비공개) 전체 매물수.
    public function status_total_count($where=array())
    {
        $rows = $this->model->status_total_count($where);
        $_status = array();
        foreach ($rows as $v) {
            $_status[$v['status']] = $v['total_count'];
        }

        $total = 0;
        foreach ($this->model->get_status("all") as $code => $title) {
            $status[$code]['title'] = $title;
            $status[$code]['total_count'] = $_status[$code] ?? 0;
            $total += $status[$code]['total_count'];
        }
        $status['all']['title'] = "전체";
        $status['all']['total_count'] = $total;

        return $status;
    }

    // 위와 동일한 패턴 - 노출여부(노출/숨김)별 전체 매물수.
    public function display_yn_total_count($where=array())
    {
        $rows = $this->model->display_yn_total_count($where);
        $_display = array();
        foreach ($rows as $v) {
            $_display[$v['display_yn']] = $v['total_count'];
        }

        $total = 0;
        foreach ($this->model->get_display_yn("all") as $code => $title) {
            $display[$code]['title'] = $title;
            $display[$code]['total_count'] = $_display[$code] ?? 0;
            $total += $display[$code]['total_count'];
        }
        $display['all']['title'] = "전체";
        $display['all']['total_count'] = $total;

        return $display;
    }

    public function get_list($page=1,$perPage=10,$where="",$search=array())
    {
        $start = ($page -1)*$perPage;

        $conditions = empty($where) ? [] : (is_array($where) ? $where : [$where]);
        $conditions = array_merge($conditions, $this->build_search_where($search));

        $total_count  = $this->model->get_count($conditions);
       // $this->log->info("매물 count Query : ".$this->model->getQuery());
        $row = $this->model->get_list($start,$perPage,$conditions);

        $this->log->info("board list Query : ".$this->model->getQuery());
        $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);
    }

    // 매물종류/거래방식/거래상태 = equal, 지역/매물명 = like. 값이 비었거나 "all"이면 그 조건은 제외(전체 검색).
    private function build_search_where($search=array())
    {
        $property_type    = $search['property_type'] ?? "";
        $transaction_type = $search['transaction_type'] ?? "";
        $status           = $search['status'] ?? "";
        $region           = $search['region'] ?? "";
        $property_name    = $search['property_name'] ?? "";
        $display_yn    = $search['display_yn'] ?? "";

        $where = array();

        if ($display_yn !== "" && $display_yn !== "all") {
            $where[] = "`display_yn` = ".$this->db->escape($display_yn);
        }
        if ($property_type !== "" && $property_type !== "all") {
            $where[] = "`property_type` = ".$this->db->escape($property_type);
        }
        if ($transaction_type !== "" && $transaction_type !== "all") {
            $where[] = "`transaction_type` = ".$this->db->escape($transaction_type);
        }
        if ($status !== "" && $status !== "all") {
            $where[] = "`status` = ".$this->db->escape($status);
        }
        if ($region !== "" && $region !== "all") {
            $kw = $this->db->escapeLikeString($region);
            $where[] = "(`addr_sido` LIKE '%$kw%' OR `addr_sigungu` LIKE '%$kw%' OR `addr_dong` LIKE '%$kw%' OR `addr_road` LIKE '%$kw%')";
        }
        if ($property_name !== "" && $property_name !== "all") {
            $kw = $this->db->escapeLikeString($property_name);
            $where[] = "`property_name` LIKE '%$kw%'";
        }

        return $where;
    }

    public function viewcount($property_no)
    {
        return $this->model->viewcount($property_no);
    }

    // Board::Postboard_del() 참고 - soft delete (is_deleted=1로만 세우고 row는 남김)
    public function del($property_no)
    {
        $entity = new Property_Entity();
        $entity->fill(['property_no' => $property_no]);

        $result = $this->model->sql_del($entity);
        $this->log->info("[Query] ".$this->model->getQuery());

        if ($result) {
            return AT_Result::toSuccessData();
        }
        return AT_Result::toFailData();
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
        $property_list = [];
        foreach($data as $k=>$v){
            $value = $v;
            $value['direction_txt'] = $this->model->get_direction($v['direction']);
            $value['heating_fuel_txt'] = $this->model->get_heating_fuel($v['heating_fuel']);
            $value['heating_type_txt'] = $this->model->get_heating_type($v['heating_type']);
            $value['status_txt'] = $this->model->get_status($v['status']);
            $value['status_stamp_txt'] = $this->build_status_stamp_txt($v['status']);
            $value['display_yn_txt'] = $this->model->get_display_yn($v['display_yn']);
            $value['property_type_txt'] = $this->model->get_property_type($v['property_type']);
            $value['transaction_type_txt'] = $this->model->get_transaction_type($v['transaction_type']);

            $value['description'] = html_entity_decode($v['description']);
            if(!empty($v['main_image']) && !empty($v['main_path'])) {
                $value['main_image_url'] = SITE_PDS.rtrim($v['main_path'], '/')."/".$v['main_image'];
            }
            // BO가 저장할 때 lat/lng으로 보내니(resolveLatLng() 참고), 조회 응답도 같은 이름으로
            // 맞춰서 내려준다 - FE가 편집화면 지도 위젯에 그대로 채워 쓸 수 있게.
            $value['lat'] = $v['latitude'] ?? null;
            $value['lng'] = $v['longitude'] ?? null;
            $value['url_slug'] = $this->build_url_slug($value);
            $property_list[$k] =  $value;
        }
        return $property_list;
    }

    // 공개 웹에서 매물사진 위에 도장처럼 찍는 문구. 거래가능(1)/비공개(5)는 도장 없음(null).
    // 거래보류(4)는 사유가 다양해 그대로 노출하면 오해 소지가 있어 "확인중"으로 순화해서 보여줌.
    private function build_status_stamp_txt($status)
    {
        $map = [2 => '거래중', 3 => '거래완료', 4 => '매물 확인중'];
        return $map[$status] ?? null;
    }

    // 공개 웹 상세보기 URL 뒤에 붙는 장식용 슬러그(지역+거래방식). 라우팅엔 쓰이지 않음.
    // 가격은 일부러 안 넣는다 - number_format()이 만드는 콤마(예: "30,000")가 URL에 그대로 들어가면
    // 인코딩 안 된 콤마 때문에 "disallowed characters" 오류가 나고, 매물마다 가격이 바뀌어서
    // 검색엔진 매칭율도 떨어진다.
    //
    // 자동 슬러그(지역+거래방식+금액)는 검색엔진 매칭용으로 만든 것이지만, 매물명이 이미 그 정보를
    // 담아 더 구체적으로 작성된 경우가 있다 - 그때는 매물명을 우선한다.
    // 1) 매물명에 지역명/거래방식/금액 중 2개 이상이 포함되면 매물명 채택
    // 2) 그렇지 않으면 매물명/슬러그 중 글자수가 더 긴 쪽 채택(정보량이 많다고 판단)
    public function build_url_slug($row)
    {
        switch ($row['transaction_type']) {
            case 'sale':
                $price = $row['sale_price']> 10000 ? ($row['sale_price']/10000)."억원" : $row['sale_price']."만원";

                    //$price = number_format($row['sale_price']).'만원';
                break;
            case 'jeonse':
                $price = $row['jeonse_price']> 10000 ? ($row['jeonse_price']/10000)."억원" : $row['jeonse_price']."만원";
             //   $price = number_format($row['jeonse_price']).'만원';
                break;
            case 'monthly':
                $price = $row['monthly_rent']> 10000 ? ($row['monthly_rent']/10000.)."억원" : $row['monthly_rent']."만원";
              //  $price = number_format($row['monthly_rent']).'만원';
                break;
            default:
                $price = '';
        }

        $addr = trim($row['addr_dong'] ?? '');
        $type_txt = trim($row['transaction_type_txt'] ?: $row['transaction_type']);
        $slug = trim($addr.' '.$type_txt)." ".$price; // 가격은 매칭율 저하로 제거

        $property_name = trim($row['property_name'] ?? '');
        if ($property_name === '') {
            return $slug;
        }

        $matched = 0;
        if ($addr !== '' && mb_strpos($property_name, $addr) !== false) $matched++;
        if ($type_txt !== '' && mb_strpos($property_name, $type_txt) !== false) $matched++;
        if ($price !== '' && mb_strpos($property_name, $price) !== false) $matched++;

        if ($matched >= 2) {
            return $property_name;
        }

        return mb_strlen($property_name) > mb_strlen($slug) ? $property_name : $slug;
    }
}