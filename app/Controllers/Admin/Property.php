<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Libraries\ImageWatermark;
use App\Service\BasicService;
use App\Service\PropertyService;
use App\Service\ImgService;

class Property extends BaseController
{
    private $log;
    private $service;
    private $imgService;

    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("property_api");
        $this->service = new PropertyService($this->log, $this->session_id);
        $this->imgService = new ImgService($this->log, $this->session_id);

        $this->set_debug(0);
        return $this;
    }

    public function getList()
    {
        $this->log->info("[REV] property listing : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $page  = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 10;
        $debug = $this->request->getGet('debug') ?? 0;

        // 검색조건: 매물종류/거래방식/거래상태는 equal, 지역/매물명은 like.
        // "all"이거나 값이 없으면 해당 조건은 제외(전체 검색).
        $search = array(
            'property_type'    => $this->request->getGet('property_type') ?? "",
            'transaction_type' => $this->request->getGet('transaction_type') ?? "",
            'status'           => $this->request->getGet('status') ?? "",
            'region'           => $this->request->getGet('region') ?? "",
            'property_name'    => $this->request->getGet('property_name') ?? "",
        );

        $counts = $this->service->status_total_count(); //거래상태별 전체 매물수.

        $rows = $this->service->get_list($page, $limit, "", $search);
        $pager_links = $this->calPaging($page, $rows['total'], 10, $limit);

        $data = array(
            'rows'   => $rows['rows'],
            'pager'  => $pager_links,
            'counts' => $counts
        );

        $oResult = AT_Result::toSuccessData("", $data);
        $this->log->info("[RES] property listing > result : ".($oResult['SUCCESS'] ? "T" : "F"));
        $this->set_debug($debug)->view($oResult);
    }

    /*
    * $page_num : 현재페이지 (기본 :1)
    * $total_items : 전체매물수
    * $block_size : 페이지 블럭사이즈(기본:10)
    * $limit : 페이지당 매물수 (기본:10)
    * */
    public function getDetail()
    {
        $this->log->info("[REV] property detail : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $property_no = $this->request->getGet('property_no');

        $rows = $this->service->getbyid($property_no);

        $oResult = AT_Result::toSuccessData("", $rows);
        $this->set_debug($this->get_debug())->view($oResult);
        //
    }

    /*preflight*/
    public function optionsSave()
    {
        return $this;
    }

    /*preflight*/
    public function optionsList()
    {
        return $this;
    }

    public function PostSave() //저장 , 수정 통합 -primary_Key 판단 후 자동처리
    {
        $post = $this->request->getPost();
        $this->log->info("save > data : ".serialize($post));

        // 신규 등록(property_no 없음)일 때만 필수값 검증 - 수정은 부분 업데이트라 매번 강제하지 않음
        if (empty($post['property_no'])) {
            $missing = $this->validate_required($post);
            if (!empty($missing)) {
                $this->view(AT_Result::toFailData("필수 입력값이 없습니다: ".implode(', ', $missing)));
                exit;
            }
        }

        $oResult = $this->service->save_property($post);

        $this->view($oResult);
        exit;
    }

    public function PostDelete() // Board::Postboard_del() 참고 - soft delete
    {
        $this->log->info("[REV] property delete : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $property_no = $this->request->getVar('property_no');

        if ($property_no != "" && $property_no > 0) {
            $oResult = $this->service->del($property_no);
        } else {
            $oResult = AT_Result::toFailData("삭제할 매물이 없습니다.");
        }

        $this->view($oResult);
        exit;
    }

    /*preflight*/
    public function optionsDelete()
    {
        return $this;
    }

    // property 테이블 NOT NULL(기본값 없음) 컬럼과 동일한 필수값 검증
    private function validate_required($post)
    {
        $required = [
            'property_name' => '매물명',
            'addr_sido'     => '시도',
            'addr_sigungu'  => '시군구',
            'addr_dong'     => '읍면동',
        ];

        $missing = [];
        foreach ($required as $field => $label) {
            if (!isset($post[$field]) || trim($post[$field]) === '') {
                $missing[] = $label;
            }
        }
        return $missing;
    }

    /*preflight*/
    public function optionsUpload_images()
    {
        return $this;
    }

    public function postUpload_images()
    {
        $file = $_FILES['image'] ?? null;

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return $this->response->setJSON(AT_Result::toFailData('업로드할 파일이 없습니다.'));
        }

        $oResult = $this->imgService->upload_file($file, SHOP_CODE."/temp_property/");

        if ($oResult['SUCCESS'] === 'TRUE') {
            $fullPath = APP_PDS . rtrim($oResult['RECORD']['file_path'], '/') . '/' . $oResult['RECORD']['file_name'];
            $companyName = $this->getCompanyName();
            $watermarked = ImageWatermark::apply($fullPath, $companyName);
            $this->log->info("[REV] watermark 적용 file=".$oResult['RECORD']['file_name']." company=".var_export($companyName, true)." applied=".var_export($watermarked, true));

            $oResult['RECORD']['file_url'] = SITE_PDS.rtrim($oResult['RECORD']['file_path'], '/')."/".$oResult['RECORD']['file_name'];
        }

        return $this->response->setJSON($oResult);
    }

    // basic.company_name(부동산명) 조회 - 워터마크 텍스트로 씀
    private function getCompanyName()
    {
        $basicService = new BasicService($this->log, $this->session_id);
        $basic = $basicService->get_by_basic();
        return $basic['company_name'] ?? '';
    }
}
