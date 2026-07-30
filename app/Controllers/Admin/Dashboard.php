<?php

namespace App\Controllers\Admin;

use App\Libraries\AT_Result;
use App\Service\DashboardService;

/*
 * html/bo_dashboard.html 시안이 호출하는 API. api.bomul.co.kr 오토라우팅으로 잡히므로
 * (App\Controllers 기본 네임스페이스 + Admin 서브디렉토리) 별도 라우트 등록 불필요.
 */
class Dashboard extends BaseController
{
    private $log;
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->is_auth();
        $this->log = $this->getMonoLog("dashboard_api");
        $this->service = new DashboardService($this->log, $this->session_id);
        $this->set_debug(0);
        return $this;
    }

    // GET /Admin/Dashboard/summary?year=2026&month=7 (생략 시 이번 달)
    public function getSummary()
    {
        $year = (int)($this->request->getVar('year') ?: date('Y'));
        $month = (int)($this->request->getVar('month') ?: date('n'));

        $this->log->info("[REV] dashboard summary year={$year} month={$month}");

        $result = $this->service->getSummary($year, $month);
        $this->view(AT_Result::toSuccessData("", $result));
    }

    /*preflight*/
    public function optionsSummary()
    {
        return $this;
    }
}
