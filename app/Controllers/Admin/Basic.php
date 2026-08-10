<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\BasicService;
use App\Service\ImgService;


class Basic extends BaseController
{
    private $service;
    private $log;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->is_auth();

        $this->log = $this->getMonoLog("basic_api");
        $this->service = new BasicService($this->log,$this->session_id);
        $this->set_debug(0);
        return $this;
    }

    public function getData()
    {
        $row = $this->service->get_by_basic();

        $oResult = AT_Result::toSuccessData("",$row);
        $this->view($oResult);
    }
    public function postSave()
    {

        $this->log->info("[REV] save : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));
        $data['code']="basic";
        $data['site_name'] = $this->request->getVar("site_name");
        if(isset($_FILES['logo'])) {
            $file = $_FILES['logo'];
        }else{
            $file=null;
        }
        $data['logo_show'] = $this->request->getVar("logo_show");
        $data['favicon_show'] = $this->request->getVar("favicon_show");
        $favicon_file = isset($_FILES['favicon_img']) ? $_FILES['favicon_img'] : "";

        $data['main_menu_color'] = $this->request->getVar("main_menu_color");
        $data['sub_menu_focus_color'] = $this->request->getVar("sub_menu_color");
        $data['menu_back_color'] = $this->request->getVar("menu_back_color");
        $data['menu_back_transparent'] = $this->request->getVar("menu_back_transparent");
        $data['property_show'] = $this->request->getVar("property_show");
        $data['notice_show'] = $this->request->getVar("notice_show");
        $data['around_show'] = $this->request->getVar("around_show");
        $data['charge_show'] = $this->request->getVar("charge_show");
        $data['review_show'] = $this->request->getVar("review_show");
        $data['vod_show'] = $this->request->getVar("vod_show");
        $data['map_show'] = $this->request->getVar("map_show");
        //링크메뉴설정
        $data['link_menu_show'] = $this->request->getVar("link_menu_show");

        $data['naver_reserve'] = $this->request->getVar("naver_reserve");
        $data['naver_reserve_show'] = $this->request->getVar("naver_reserve_show");
        $data['kakao_channel'] = $this->request->getVar("kakao_channel");
        $data['kakao_channel_show'] = $this->request->getVar("kakao_channel_show");
//사이트 링크 메뉴
        $data['sns_link'] = $this->request->getVar("sns_link");
        $data['sns_key'] = $this->request->getVar("sns_key");
        $data['sns_del_key'] = $this->request->getVar("sns_del_key");

        $sns_file = isset($_FILES['sns_img']) ? $_FILES['sns_img'] : "";

        $data['company_name'] = $this->request->getVar("company_name");
        //중개사무소 법정 표시항목
        $data['broker_office_name'] = $this->request->getVar("broker_office_name");
        $data['broker_address'] = $this->request->getVar("broker_address");
        $data['broker_phone'] = $this->request->getVar("broker_phone");
        $data['broker_reg_no'] = $this->request->getVar("broker_reg_no");
        $data['broker_ceo_name'] = $this->request->getVar("broker_ceo_name");
        $data['broker_agent_name'] = $this->request->getVar("broker_agent_name");
        $data['broker_agent_phone'] = $this->request->getVar("broker_agent_phone");
        $data['footer_copy'] = $this->request->getVar("footer_copy");


        $data['logo_del'] = $this->request->getVar("logo_del"); //로고삭제

        $missing = $this->validate_required($data);
        if (!empty($missing)) {
            $this->view(AT_Result::toFailData("필수 입력값이 없습니다: ".implode(', ', $missing)));
            exit;
        }

        $this->log->info("[REV] data : ".json_encode($data,JSON_UNESCAPED_UNICODE));
        $oResult = $this->service->save($data,$file,$favicon_file,$sns_file);
        $this->view($oResult);
    }

    // <meta name="naver-site-verification" content="XXXX"> 형태로 통째로 붙여넣었으면 content
    // 값만 추출, 이미 코드값만 왔으면(정상 케이스) 트림만 해서 그대로 반환.
    // null(필드 자체를 안 보냄)과 ''(필드는 보냈지만 비움)을 구분해서 반환한다 - sql_edit()의
    // is_null() 체크가 null이면 SET절에서 빼서 기존값 유지, ''이면 SET절에 넣어서 실제로 비운다.
    private function extract_naver_verification_code($value)
    {
        if ($value === null) {
            return null;
        }
        $value = trim((string)$value);
        if ($value === '') {
            return '';
        }
        if (preg_match('/content\s*=\s*["\']([^"\']+)["\']/i', $value, $matches)) {
            return trim($matches[1]);
        }
        return $value;
    }

    // 중개사무소 법정 표시항목(공인중개사법 제18조의2) 필수값 검증.
    // basic/save는 한 화면에서 값을 골라 부분수정하는 API라(naver_site_verification만 저장하는 경우 등)
    // 이번 요청에 안 실린 필드는 기존 저장값으로 대체해서 판단한다. 처음 설정하거나(기존값도 없음)
    // 값을 비우려는 시도일 때만 필수값 누락으로 막는다.
    private function validate_required($data)
    {
        $required = [
          //  'company_name'       => '상호명',
            'broker_office_name' => '중개사무소 명칭',
            'broker_address'     => '중개사무소 소재지',
            'broker_phone'       => '중개사무소 연락처',
            'broker_reg_no'      => '중개사무소 등록번호',
            'broker_ceo_name'    => '개업공인중개사 성명',
            'broker_agent_name'  => '소속공인중개사 성명',
            'broker_agent_phone' => '소속공인중개사 연락처',
        ];

        $current = $this->service->get_row();

        $missing = [];
        foreach ($required as $field => $label) {
            $value = $data[$field] ?? null;
            if ($value === null || trim($value) === '') {
                $value = $current[$field] ?? null; // 이번 요청엔 없지만 기존에 저장된 값이 있으면 유지
            }
            if ($value === null || trim($value) === '') {
                $missing[] = $label;
            }
        }
        return $missing;
    }

    // 네이버 예약위젯 노출여부/사이트 인증코드만 부분저장. 중개사무소 정보 등 basic 폼 전체 없이 단독 호출 가능.
    public function postSave_verification_code()
    {
        $this->log->info("[REV] save_verification_code : ".json_encode($_POST,JSON_UNESCAPED_UNICODE));

        $data['naver_reserve_show'] = $this->request->getVar("naver_reserve_show");
        $data['naver_site_verification'] = $this->extract_naver_verification_code(
            $this->request->getVar("naver_site_verification")
        );

        $oResult = $this->service->save_verification_code($data);
        $this->log->info("[REV] data : ".json_encode($data,JSON_UNESCAPED_UNICODE));
        $this->view($oResult);
    }

    /*
     * 첨부파일 삭제
     */
    public function postfile_del()
    {
        $img_service = new ImgService(null,$this->session_id);
        $img_no=$this->request->getVar("img_no");
        $oResult = $img_service->del_image($img_no);
        $this->view($oResult);

    }

}

