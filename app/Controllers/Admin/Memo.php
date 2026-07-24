<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\MemoService;

class Memo extends BaseController
{
    private $log;
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("memo_api");
        $this->service = new MemoService($this->log, $this->session_id);

        $this->set_debug(0);
        return $this;
    }

    public function getList()
    {
        $this->log->info("[REV] memo listing : ".json_encode($_GET, JSON_UNESCAPED_UNICODE));
        $parent_table = $this->request->getGet('parent_table');
        $parent_no    = $this->request->getGet('parent_no');

        $rows = $this->service->get_memos($parent_table, $parent_no);

        $oResult = AT_Result::toSuccessData("", $rows);
        $this->view($oResult);
    }

    public function PostSave() // memo_no 유무로 등록/수정 자동 판단
    {
        $post = $this->request->getPost();
        $this->log->info("memo save > data : ".serialize($post));

        // FE는 note_no/name/memo 값을 전송 - 여기서 add_memo()가 기대하는 형태로 매핑
        if (!empty($post['memo_no'])) {
            $oResult = $this->service->edit_memo($post['memo_no'], $post['memo'] ?? '');
        } else {
            $oResult = $this->service->add_memo('note', $post['note_no'] ?? 0, null, $post['name'] ?? null, $post['memo'] ?? '');
        }

        $this->view($oResult);
        exit;
    }

    public function PostDelete()
    {
        $post = $this->request->getPost();
        $this->log->info("memo delete > data : ".serialize($post));

        $oResult = $this->service->del_memo($post['memo_no'] ?? 0);

        $this->view($oResult);
        exit;
    }

    /*preflight*/
    public function optionsList()
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
