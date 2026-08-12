<?php

namespace App\Controllers\Web;

use App\Controllers\Web\BaseController;
use App\Libraries\AT_Result;
use App\Service\MemoService;
use App\Service\NoteService;
use App\Service\PropertyService;

class Property extends BaseController
{
    public function __construct()
    {
        $this->set_code("property");
        parent::__construct();
        $this->log = $this->getMonoLog($this->get_code());

        return $this;
    }

    public function getindex()
    {
        $this->set_title("매물정보");

        $page = (int) ($this->request->getGet('page') ?? 1);
        if ($page < 1) $page = 1;
        $limit = 10;

        // 공개 웹에는 노출설정된, 비공개 상태가 아닌 매물만
        $where = ["display_yn = 1", "status != 5"];

        $service = new PropertyService($this->log, $this->session_id);
        $rows = $service->get_list($page,$limit,$where);
        $total = $rows['total'];
        $paging = $this->get_paging($total,$page,$limit);

        $data = array(
            'active'=> $this->get_code(),
            'rows'  => $rows['rows'],
            'paging'=> $paging
        );

        $this->view("",'Property/index',$data);
    }

    public function getview($property_no)
    {
        $property_no = (int) $property_no;

        $service = new PropertyService($this->log, $this->session_id);
        $rows = $service->getbyid($property_no);
        $row = $rows[0] ?? null;

        if (!$row || $row['display_yn']!=1 || $row['status']==5) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $service->viewcount($property_no);

        $slugs = $service->build_url_slug($row);
        $this->set_title($slugs);
        // summary(한줄소개)가 있으면 그걸 우선 쓰고, 없으면 주소 기반으로 간단히 생성
        $description = trim((string)($row['summary'] ?? ''));
        if ($description === '') {
            $description = trim(($row['addr_sido'] ?? '') . ' ' . ($row['addr_sigungu'] ?? '') . ' ' . ($row['addr_dong'] ?? '') . ' ' . ($row['property_name'] ?? ''));
        }
        $this->set_description($description);

        $data = array(
            'active'=> $this->get_code(),
            'row'   => $row
        );

        $this->view("",'Property/view',$data);
    }

    // 매물 상세페이지 "상담신청" 팝업이 ajax POST하는 저장 엔드포인트 (property/saveNote)
    public function savenote()
    {
        $post = $this->request->getPost();

        $visitorName  = trim((string) ($post['visitor_name'] ?? ''));
        $visitorPhone = trim((string) ($post['visitor_phone'] ?? ''));
        $memoText     = trim((string) ($post['memo'] ?? ''));
        $propertyNo   = (int) ($post['property_no'] ?? 0);

        if ($visitorName === '' || $visitorPhone === '') {
            echo AT_Result::toJson(AT_Result::toFailData('이름과 연락처를 입력해주세요.'));
            return;
        }

        $noteService = new NoteService($this->log, $this->session_id);
        $result = $noteService->exec([
            'visitor_name'  => $visitorName, //방문자명
            'visitor_phone' => $visitorPhone, //방문자 연락처
            'property_no'   => $propertyNo ?: null, //관심매물
            'inflow_type'   => 'web', // 유입경로 - 웹 상담신청 팝업을 통한 유입
            'visit_type'    => 'tenant' , //방문형태는 세입자
            'status'        => 'consult_request', //진행상태 - 상담요청
        ]);

        // 문의내용(선택)은 note 테이블 컬럼이 아니라 별도 memo로 붙인다 (BO 노트 상세의 memos와 동일 구조)
        $noteNo = (int) ($result['RECORD']['note_no'] ?? 0);
        if ($result['SUCCESS'] === 'TRUE' && $noteNo > 0 && $memoText !== '') {
            $memoService = new MemoService($this->log, $this->session_id);
            $memoService->add_memo('note', $noteNo, 0, $visitorName . '(웹신청)', $memoText);
        }

        echo AT_Result::toJson($result);
    }
}
