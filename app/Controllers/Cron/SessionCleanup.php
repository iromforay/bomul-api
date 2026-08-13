<?php

namespace App\Controllers\Cron;

use App\Libraries\AT_Result;
use App\Models\LoginModel;
use CodeIgniter\Controller;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
 * 오래된 login_session row를 주기적으로 정리하는 엔드포인트.
 * write_date 기준 .env의 SESSION_EXPIRE_DAYS(기본 7일)가 지난 row를 물리 삭제한다.
 * status 컬럼은 로그아웃 등에서 갱신되지 않아 사실상 의미가 없으므로 판단 기준으로 쓰지 않는다
 * (LoginModel::get_session_id도 같은 write_date 기준을 씀 - 인증 쪽 만료 판단과 여기 정리
 * 기준이 어긋나면 안 되므로 SESSION_EXPIRE_DAYS 하나로 공유한다).
 *
 * 인증: Cron\Analytics와 동일 - .env의 CRON_ALLOWED_IPS 화이트리스트만 확인.
 */
class SessionCleanup extends Controller
{
    private $log;

    public function __construct()
    {
        if (!$this->checkAllowedIp()) {
            http_response_code(403);
            exit;
        }
        $this->log = $this->getMonoLog('cron_session_cleanup');
        return $this;
    }

    private function checkAllowedIp()
    {
        $configured = env('CRON_ALLOWED_IPS', '127.0.0.1,::1');
        $allowed = array_map('trim', explode(',', $configured));
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        return in_array($remoteIp, $allowed, true);
    }

    public function getCleanup()
    {
        $days = (int) env('SESSION_EXPIRE_DAYS', 7);
        $db = db_connect('default');
        $model = model(LoginModel::class, true, $db);

        $deleted = $model->deleteExpired($days);

        $this->log->info('[REV] session cleanup 실행 days=' . $days . ' deleted=' . $deleted);

        echo AT_Result::toJson(AT_Result::toSuccessData('', ['deleted' => $deleted]));
    }

    public function getMonoLog($log_name = 'cron')
    {
        $file_name = date('Ymd') . '_' . $log_name . '.log';

        $log = new Logger($log_name);
        $log->pushHandler(new StreamHandler(LOG_DIR . '/' . date('Ym') . '/' . $file_name, Logger::DEBUG));

        return $log;
    }
}
