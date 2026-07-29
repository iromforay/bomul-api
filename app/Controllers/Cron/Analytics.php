<?php

namespace App\Controllers\Cron;

use App\Libraries\AT_Result;
use App\Service\AnalyticsAggregateService;
use CodeIgniter\Controller;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
 * 새벽 배치가 curl로 호출하는 중개사별 일별 집계 실행 엔드포인트.
 *
 * 호출 경로: app/batch/batch.php(마더) → analy.php → 중개사 fid마다
 *   curl http://{fid}.bomul.co.kr/cron/analytics/daily-aggregate
 * 이 컨트롤러는 Web 네임스페이스가 아닌 별도 네임스페이스라 app/Config/Routes.php에
 * 풀네임스페이스로 명시 라우트를 등록해뒀다 (web 브랜치는 오토라우팅이 꺼져있음).
 *
 * SHOP_CODE 해석: Web\BaseController::set_domain()을 쓰지 않고 Track.php와 동일하게
 * Host 헤더에서 직접 최소 해석한다 - 서브도메인 = fid = shop_code = DB이름 이라는 대원칙에
 * 따라 요청 Host의 첫 세그먼트를 그대로 SHOP_CODE로 쓴다. (커스텀 도메인 → fid 매핑은
 * set_domain() 쪽의 더 큰 과제라 이 배치 호출 경로에선 다루지 않는다 - analy.php가 curl
 * 대상 URL을 항상 {fid}.bomul.co.kr로 직접 구성하기 때문에 이 컨트롤러 입장에선 항상
 * 서브도메인 규칙만 맞으면 된다)
 *
 * 인증: 외부에서 이 URL을 알면 누구나 반복 호출해 배치를 재실행시킬 수 있으므로
 * (멱등이라 데이터가 깨지진 않지만 불필요한 부하), .env의 CRON_SECRET과 요청의 token
 * 파라미터가 일치해야만 실행한다. CRON_SECRET 미설정 시엔 안전하게 거부(fail-closed).
 */
class Analytics extends Controller
{
    private $log;

    public function __construct()
    {
        $this->log = $this->getMonoLog('cron_analytics');
        return $this;
    }

    public function getDailyAggregate()
    {
        if (!$this->checkToken()) {
            $this->log->warning('[REV] cron analytics 인증 실패');
            $result = AT_Result::toFailData('unauthorized');
            echo AT_Result::toJson($result);
            return;
        }

        $shopCode = $this->resolveShopCodeFromHost();
        if ($shopCode === '') {
            $result = AT_Result::toFailData('cannot resolve shop_code from host');
            echo AT_Result::toJson($result);
            return;
        }
        if (!defined('SHOP_CODE')) {
            define('SHOP_CODE', $shopCode);
        }

        $date = $this->request->getVar('date') ?: null; // 수동 재실행/백필용, 생략 시 서비스가 "어제"로 처리

        $service = new AnalyticsAggregateService($this->log, SHOP_CODE);
        $summary = $service->aggregateDaily($date);

        $this->log->info('[REV] cron analytics 실행 shop_code=' . $shopCode . ' summary=' . json_encode($summary, JSON_UNESCAPED_UNICODE));

        echo AT_Result::toJson(AT_Result::toSuccessData("", $summary));
    }

    private function checkToken()
    {
        $secret = env('CRON_SECRET', '');
        if ($secret === '' || $secret === null) {
            return false;
        }
        return hash_equals($secret, (string)$this->request->getVar('token'));
    }

    // Host 헤더의 첫 세그먼트 = fid = shop_code (예: foray.bomul.co.kr → foray)
    private function resolveShopCodeFromHost()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $parts = explode('.', $host);
        return $parts[0] ?? '';
    }

    public function getMonoLog($log_name = 'cron')
    {
        $file_name = date('Ymd') . '_' . $log_name . '.log';

        $log = new Logger($log_name);
        $log->pushHandler(new StreamHandler(LOG_DIR . '/' . date('Ym') . '/' . $file_name, Logger::DEBUG));

        return $log;
    }
}
