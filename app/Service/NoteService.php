<?php

namespace App\Service;

use App\Entity\Note_Entity;
use App\Libraries\AT_Result;

class NoteService extends CI_Service
{
    private $model;
    private $log;
    private $memoService;

    public function __construct($log="",$session_id)
    {
        parent::__construct($session_id);

        $this->log = $log;
        $this->model = model('App\Models\NoteModel',true,$this->db);
        $this->model->getLog($log);
        $this->memoService = new MemoService($log, $session_id);
        return $this;
    }

    public function exec($data)
    {
        if ( isset($data['note_no']) && $data['note_no']>0) {
            $this->log->info("[note service] exec > type : edit");
            $note_no = $data['note_no'];
            $oResult = $this->edit($data);
        }else{
            $this->log->info("[note service] exec > type : save");
            $note_no = $this->save($data); // 성공 시 새 note_no, 실패 시 0
            $oResult = $note_no > 0;
        }
        if($oResult){
            return AT_Result::toSuccessData("", ['note_no' => $note_no]);
        }else{
            return AT_Result::toFailData();
        }
    }

    public function save($data)
    {
        $entity = new Note_Entity();
        $entity->fill($this->filterEntityData($data));
        $result = $this->model->register($entity);
        $this->log->info("[Query] ".$this->model->getQuery());
        return $result;
    }

    public function edit($data)
    {
        $entity = new Note_Entity();
        $entity->fill($this->filterEntityData($data));
        $result = $this->model->modify($entity);
        $this->log->info("[Query] ".$this->model->getQuery());
        return $result;
    }

    // Note_Entity에 실제 정의된 필드만 통과 (오타/미정의 필드가 SQL로 새는 것 방지)
    private function filterEntityData($data)
    {
        $entity = new Note_Entity();
        return array_intersect_key($data, $entity->toRawArray());
    }

    public function get_list($page=1,$perPage=10,$status="",$search=array())
    {
        $start = ($page -1)*$perPage;

        $where = $this->build_where($status,$search);

        $total_count = $this->model->get_count($where);
        $row = $this->model->get_list($start,$perPage,$where);

        $this->log->info("note list Query : ".$this->model->getQuery());

        // 내림차순 넘버링: 이 페이지 첫 행이 total_count-start번, 아래로 갈수록 1씩 감소
        $item_num_start = $total_count - $start;
        $row = $this->after_process($row, $item_num_start);

        return array("total"=>$total_count, "rows"=>$row);
    }

    // status 그룹 탭 + 상세검색(방문형태/거래방식/방문자명/연락처) 조건 조립
    private function build_where($status="",$search=array())
    {
        $visit_type       = $search['visit_type'] ?? "";
        $transaction_type = $search['transaction_type'] ?? "";
        $visitor_name     = $search['visitor_name'] ?? "";
        $visitor_phone    = $search['visitor_phone'] ?? "";

        $where = array();

        if ($status !== "" && $status !== "all" && in_array($status, $this->model->get_all_status())) {
            $where[] = "`status` = '".$status."'";
        }
        if ($visit_type !== "" && array_key_exists($visit_type, $this->model->get_visit_type("all"))) {
            $where[] = "`visit_type` = '".$visit_type."'";
        }
        if ($transaction_type !== "" && array_key_exists($transaction_type, $this->model->get_transaction_type("all"))) {
            $where[] = "`transaction_type` = '".$transaction_type."'";
        }
        if ($visitor_name !== "") {
            $where[] = "`visitor_name` LIKE '%".$this->db->escapeLikeString($visitor_name)."%'";
        }
        if ($visitor_phone !== "") {
            $phone_digits = str_replace('-', '', $visitor_phone);
            $where[] = "REPLACE(`visitor_phone`,'-','') LIKE '%".$this->db->escapeLikeString($phone_digits)."%'";
        }

        return $where;
    }

    // 상단 상태 탭(상담중/매물제안/계약완료/이탈 + 전체)별 카운트. board_total_count()와 동일한 방식.
    // $where로 기간 등 조건을 걸 수 있음(예: 대시보드에서 이번달 등록건만 집계). 기본은 전체.
    public function status_total_count($where=array())
    {
        $rows = $this->model->status_total_count($where);
        $_status = array();
        foreach ($rows as $v) {
            $_status[$v['status']] = $v['total_count'];
        }

        $total = 0;
        foreach ($this->model->get_all_status() as $v) {
            $status[$v]['title'] = $this->model->get_status($v);
            $status[$v]['total_count'] = $_status[$v] ?? 0;
            $total += $status[$v]['total_count'];
        }
        $status['all']['title'] = "전체";
        $status['all']['total_count'] = $total;

        return $status;
    }

    public function getbyid($note_no)
    {
        $row = $this->model->findbyid($note_no);
        $row = $this->after_process($row);

        // 관심매물(property_no)이 있으면 매물명도 같이 내려준다 (목록에선 N+1 부담이라 상세에서만)
        // 형식: "[property_no]property_name" - BO 화면에서 매물번호/이름을 한 번에 식별하기 위함
        if (isset($row[0]) && (int) ($row[0]['property_no'] ?? 0) > 0) {
            $propertyNo = (int) $row[0]['property_no'];
            $row[0]['property_name'] = '[' . $propertyNo . ']' . $this->getPropertyName($propertyNo);
        }

        return $row;
    }

    private function getPropertyName($property_no)
    {
        $propertyModel = model('App\Models\PropertyModel', true, $this->db);
        $row = $propertyModel->findbyid($property_no);
        return $row[0]['property_name'] ?? null;
    }

    // Board::Postboard_del() 참고 - soft delete (is_deleted=1로만 세우고 row는 남김)
    public function del($note_no)
    {
        $entity = new Note_Entity();
        $entity->fill(['note_no' => $note_no]);

        $result = $this->model->sql_del($entity);
        $this->log->info("[Query] ".$this->model->getQuery());

        if ($result) {
            // 노트가 삭제되면 딸린 메모도 같이 안 보여야 하니 일괄 soft delete
            $this->memoService->del_memos_by_parent('note', $note_no);
            return AT_Result::toSuccessData();
        }
        return AT_Result::toFailData();
    }

    public function after_process($data, $item_num_start=null)
    {
        $property_list = [];
        $num = $item_num_start;
        foreach($data as $k=>$v){
            $value = $v;
            $value['visit_type_txt'] = $this->model->get_visit_type($v['visit_type']);
            $value['transaction_type_txt'] = $this->model->get_transaction_type($v['transaction_type']);
            $value['status_txt'] = $this->model->get_status($v['status']);
            $value['inflow_type_txt'] = $this->model->get_inflow_type($v['inflow_type']);
            $value['num'] = $num !== null ? $num-- : null;
            $value['memos'] = $this->build_memos($v['note_no']);

            $property_list[$k] = $value;
        }
        return $property_list;
    }

    // memo 원본(memo_no/parent_table/parent_no/writer_no/writer_name/memo_text/write_date)에서
    // 노트에서 쓰는 memo_no/name/memo 3개 필드만 추려서 반환
    private function build_memos($note_no)
    {
        $memos = $this->memoService->get_memos('note', $note_no);
        $result = [];
        foreach ($memos as $memo) {
            $result[] = [
                'memo_no' => $memo['memo_no'],
                'name'    => $memo['writer_name'],
                'memo'    => $memo['memo_text'],
            ];
        }
        return $result;
    }
}
