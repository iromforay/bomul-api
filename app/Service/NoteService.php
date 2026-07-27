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

    public function get_list($page=1,$perPage=10,$where="")
    {
        $start = ($page -1)*$perPage;

        $total_count = $this->model->get_count();
        $row = $this->model->get_list($start,$perPage,$where);

        $this->log->info("note list Query : ".$this->model->getQuery());
        $row = $this->after_process($row);

        return array("total"=>$total_count, "rows"=>$row);
    }

    public function getbyid($note_no)
    {
        $row = $this->model->findbyid($note_no);
        $row = $this->after_process($row);
        return $row;
    }

    public function after_process($data)
    {
        $property_list = [];
        foreach($data as $k=>$v){
            $value = $v;
            $value['visit_type_txt'] = $this->model->get_visit_type($v['visit_type']);
            $value['transaction_type_txt'] = $this->model->get_transaction_type($v['transaction_type']);
            $value['status_txt'] = $this->model->get_status($v['status']);
            $value['inflow_type_txt'] = $this->model->get_inflow_type($v['inflow_type']);

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
