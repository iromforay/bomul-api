<?php

namespace App\Controllers;

use App\Libraries\AT_Result;
use App\Models\SettingQueueModel;
use CodeIgniter\Controller;

/*
 * bomul-bo(중앙)의 QueuePush 커맨드가 fire-and-forget으로 찔러주는 수신 엔드포인트.
 * 받은 걸 그대로 로컬 setting_queue에 적재만 하고 바로 응답한다 - 실제 처리(설치)는 여기서 안 하고
 * 콜백 cron이 비동기로 처리 후 중앙에 보고한다 (app/batch/setting_callback.php 참고).
 *
 * 인증: .env의 RECEIVER_API_KEY와 Authorization Bearer 토큰이 일치하는지만 확인한다.
 * 중앙의 server_list.api_key에 이 서버용으로 발급해둔 값과 짝을 이뤄야 한다 - Callback.php처럼
 * "어느 서버가 보냈는지" 식별할 필요가 없다(이 서버 자기 자신에게 온 요청이라 신원이 이미 정해져
 * 있음), 그래서 server_code 조회 없이 고정 토큰 하나만 비교하면 된다.
 * Controller::__construct() 시점엔 아직 $this->request가 없어서(initController()가 이후 호출됨 -
 * Cron\Analytics.php와 동일한 이유) Authorization 헤더는 $_SERVER에서 직접 읽는다.
 */
class Receiver extends Controller
{
    public function __construct()
    {
        if (!$this->checkToken()) {
            http_response_code(401);
            echo AT_Result::toJson(AT_Result::toFailData('unauthorized'));
            exit;
        }
        return $this;
    }

    public function postIndex()
    {
        $body = $this->request->getJSON(true) ?? [];

        $queueNo = (int) ($body['queue_no'] ?? 0);
        $fid     = (string) ($body['fid'] ?? '');
        $action  = (string) ($body['action'] ?? 'new');

        if ($queueNo <= 0 || $fid === '') {
            echo AT_Result::toJson(AT_Result::toFailData('queue_no, fid는 필수입니다.'));
            return;
        }

        $db = db_connect('default');
        $model = model(SettingQueueModel::class, true, $db);
        $setting_no = $model->sql_save($queueNo, $fid, $action, $body);

        if (!$setting_no) {
            echo AT_Result::toJson(AT_Result::toFailData('저장에 실패했습니다.'));
            return;
        }

        echo AT_Result::toJson(AT_Result::toSuccessData('', ['setting_no' => $setting_no]));
    }

    private function checkToken(): bool
    {
        $expected = env('RECEIVER_API_KEY', '');
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $auth);
        return $expected !== '' && hash_equals($expected, $token);
    }
}
