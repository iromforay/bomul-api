<?php

namespace App\Controllers;

use App\Libraries\AT_Result;
use App\Models\BatchScheduleModel;
use CodeIgniter\Controller;

/*
 * api.bomul.ai.kr/callback/complete - 각 배치 워커(Cron\Analytics 등)가 중개사별 처리를 끝낸 뒤
 * 호출하는 범용 완료 콜백. bomul.batch_schedule의 해당 job_type+fid 행을 done/failed로 갱신한다.
 *
 * analy.php(마더 쪽)는 이 콜백을 기다리지 않는다 - Cron\Analytics가 직접 여기로 다시 호출해서
 * "끝났다"를 알리는 구조 (app/Controllers/Cron/Analytics.php 주석 참고).
 *
 * 인증: {fid}.bomul.ai.kr(web) 쪽과 다르게, 여기는 api.bomul.ai.kr이라 기존 관례
 * (App\Controllers\API\BaseController - Authorization 헤더의 Bearer 토큰) 그대로 따른다.
 * IP 체크(CRON_ALLOWED_IPS)도 같이 걸어서 이중으로 막는다 - 진짜 API 소비자가 아니라
 * 내부 배치 전용 엔드포인트라 굳이 완화할 이유가 없다.
 */
class Callback extends Controller
{
    public function __construct()
    {
        if (!$this->checkAllowedIp()) {
            http_response_code(403);
            exit;
        }
        return $this;
    }

    public function getComplete()
    {
        if (!$this->checkToken()) {
            echo AT_Result::toJson(AT_Result::toFailData('unauthorized'));
            return;
        }

        $fid = (string)$this->request->getVar('fid');
        $jobType = (string)$this->request->getVar('job_type');
        $ok = $this->request->getVar('ok') === '1';
        $runDate = date('Y-m-d', strtotime('-1 day')); // AnalyticsAggregateService 기본값과 동일 (어제)

        if ($fid === '' || $jobType === '') {
            echo AT_Result::toJson(AT_Result::toFailData('fid and job_type required'));
            return;
        }

        $db = db_connect('default');
        $scheduleModel = model(BatchScheduleModel::class, true, $db);
        $result = $scheduleModel->markDone($jobType, $runDate, $fid, $ok);

        echo AT_Result::toJson(AT_Result::toSuccessData("", ["updated" => $result]));
    }

    // .env의 CRON_ALLOWED_IPS(콤마구분)에 있는 IP만 허용. 미설정 시 127.0.0.1/::1만 기본 허용.
    // 생성자 시점엔 아직 $this->request가 없어서(initController()가 __construct() 이후에 호출됨)
    // $_SERVER['REMOTE_ADDR']을 직접 읽는다.
    private function checkAllowedIp()
    {
        $configured = env('CRON_ALLOWED_IPS', '127.0.0.1,::1');
        $allowed = array_map('trim', explode(',', $configured));
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        return in_array($remoteIp, $allowed, true);
    }

    // api.bomul.ai.kr의 기존 관례(App\Controllers\API\BaseController)와 동일하게 Authorization
    // 헤더의 Bearer 토큰으로 인증한다.
    private function checkToken()
    {
        $secret = env('CRON_SECRET', '');
        if ($secret === '' || $secret === null) {
            return false;
        }
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $auth);
        return hash_equals($secret, $token);
    }
}
