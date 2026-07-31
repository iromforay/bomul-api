<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\NoteService;

class Note extends BaseController
{
    private $log;
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("note_api");
        $this->service = new NoteService($this->log, $this->session_id);

        $this->set_debug(0);
        return $this;
    }

    public function getList()
    {
        $this->log->info("[REV] note listing : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $page   = (int) ($this->request->getGet('page') ?? 1);
        $limit  = (int) ($this->request->getGet('limit') ?? 50); // 현재 전체를 한 페이지에 보여주기 위해 50으로 설정
        $status = $this->request->getGet('status') ?? ""; // 상단 상태 탭(all/consulting/proposed/contracted/lost)

        // 상세검색
        $search = array(
            'visit_type'       => $this->request->getGet('visit_type') ?? "",
            'transaction_type' => $this->request->getGet('transaction_type') ?? "",
            'visitor_name'     => $this->request->getGet('visitor_name') ?? "",
            'visitor_phone'    => $this->request->getGet('visitor_phone') ?? "",
        );

        $counts = $this->service->status_total_count(); //진행상태별 전체 노트수.

        $rows = $this->service->get_list($page, $limit, $status, $search);
        $total = $rows['total'];
        $pager_links = $this->calPaging($page, $total, 10, $limit);

        $data = array(
            'rows'   => $rows['rows'],
            'pager'  => $pager_links,
            'counts' => $counts
        );

        $oResult = AT_Result::toSuccessData("", $data);
        $this->view($oResult);
    }

    public function getDetail()
    {
        $this->log->info("[REV] note detail : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $note_no = $this->request->getGet('note_no');

        $rows = $this->service->getbyid($note_no);

        $oResult = AT_Result::toSuccessData("", $rows);
        $this->view($oResult);
    }

    public function PostSave() //저장 , 수정 통합 - note_no 유무로 자동 판단
    {
        $post = $this->request->getPost();
        $this->log->info("note save > data : ".serialize($post));

        // 신규 등록(note_no 없음)일 때만 필수값 검증 - 수정은 부분 업데이트라 매번 강제하지 않음
        if (empty($post['note_no'])) {
            $missing = $this->validate_required($post);
            if (!empty($missing)) {
                $this->view(AT_Result::toFailData("필수 입력값이 없습니다: ".implode(', ', $missing)));
                exit;
            }
        }

        $oResult = $this->service->exec($post);

        $this->view($oResult);
        exit;
    }

    public function PostDelete() // Board::Postboard_del() 참고 - soft delete
    {
        $this->log->info("[REV] note delete : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $note_no = $this->request->getVar('note_no');

        if ($note_no != "" && $note_no > 0) {
            $oResult = $this->service->del($note_no);
        } else {
            $oResult = AT_Result::toFailData("삭제할 노트가 없습니다.");
        }

        $this->view($oResult);
        exit;
    }

    // agent_no/property_no만 선택, 나머지는 전부 필수
    private function validate_required($post)
    {
        $required = [
            'visitor_name'      => '방문자명',
            'visitor_phone'     => '방문자 연락처',
            'visit_type'        => '방문형태',
            'transaction_type'  => '거래방식',
            'budget'            => '예산',
            'preferred_area'    => '희망지역',
            'inflow_type'       => '유입경로',
            'status'            => '진행상태',
            'visit_date'        => '방문예정일',
            'next_contact_date' => '다음연락예정일',
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
    public function optionsList()
    {
        return $this;
    }
    public function optionsDetail()
    {
        return $this;
    }
    public function optionsSave()
    {
        return $this;
    }
    public function optionsDelete()
    {
        return $this;
    }
}
