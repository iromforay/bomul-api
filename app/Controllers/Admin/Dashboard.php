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

    // GET /Admin/Dashboard/summary?period=202607 (YYYYMM, 생략 시 이번 달)
    // 숨은 기능(현재 FE 미사용, html 시안 문서에도 미노출): ?year=2026&month=7&day=15 로 하루 단위 통계 조회 가능.
    // day가 오면 period는 무시되고 그 하루(전일 대비)만 조회됨.
    public function getSummary()
    {
        $period = $this->request->getVar('period');
        $day = $this->request->getVar('day');

        if ($day && preg_match('/^\d{1,2}$/', $day)) {
            $year = (int)($this->request->getVar('year') ?: date('Y'));
            $month = (int)($this->request->getVar('month') ?: date('n'));
            $day = (int)$day;
        } elseif ($period && preg_match('/^(\d{4})(\d{2})$/', $period, $m)) {
            $year = (int)$m[1];
            $month = (int)$m[2];
            $day = null;
        } else {
            $year = (int)date('Y');
            $month = (int)date('n');
            $day = null;
        }

        $this->log->info("[REV] dashboard summary period={$period} year={$year} month={$month} day=".var_export($day, true));

        $result = $this->service->getSummary($year, $month, $day);
        $this->view(AT_Result::toSuccessData("", $result));
    }

    /*preflight*/
    public function optionsSummary()
    {
        return $this;
    }
}
