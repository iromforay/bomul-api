<?php

namespace App\Service;

use App\Entity\Memo_Entity;
use App\Libraries\AT_Result;

class MemoService extends CI_Service
{
    private $model;
    private $log;

    public function __construct($log="",$session_id="")
    {
        parent::__construct($session_id);
        $this->log = $log;
        $this->model = model('App\Models\MemoModel',true,$this->db);
        return $this;
    }

    public function add_memo($parent_table, $parent_no, $writer_no, $writer_name, $memo_text)
    {
        if (trim($memo_text ?? '') == "") {
            return AT_Result::toFailData("메모 내용이 없습니다.");
        }

        $entity = new Memo_Entity();
        $entity->setParentTable($parent_table);
        $entity->setParentNo($parent_no);
        $entity->setWriterNo($writer_no);
        $entity->setWriterName($writer_name);
        $entity->setMemoText($memo_text);

        $memo_no = $this->model->save_memo($entity);

        if ($memo_no > 0) {
            return AT_Result::toSuccessData("", ['memo_no' => $memo_no]);
        }
        return AT_Result::toFailData("메모 저장에 실패했습니다.");
    }

    // parent_table/parent_no로 등록된 메모 목록 조회 (오래된순)
    public function get_memos($parent_table, $parent_no)
    {
        return $this->model->get_memo($parent_no, $parent_table);
    }

    public function edit_memo($memo_no, $memo_text)
    {
        if (trim($memo_text ?? '') == "") {
            return AT_Result::toFailData("메모 내용이 없습니다.");
        }

        $entity = new Memo_Entity();
        $entity->setMemoNo($memo_no);
        $entity->setMemoText($memo_text);

        if ($this->model->update_memo($entity)) {
            return AT_Result::toSuccessData();
        }
        return AT_Result::toFailData();
    }

    public function del_memo($memo_no)
    {
        $entity = new Memo_Entity();
        $entity->setMemoNo($memo_no);

        if ($this->model->delete_memo($entity)) {
            return AT_Result::toSuccessData();
        }
        return AT_Result::toFailData();
    }

    // 부모(note 등)가 삭제될 때 딸린 메모를 일괄 soft delete
    public function del_memos_by_parent($parent_table, $parent_no)
    {
        return $this->model->delete_by_parent($parent_table, $parent_no);
    }
}
