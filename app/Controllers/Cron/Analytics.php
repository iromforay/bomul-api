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
 *   curl http://{fid}.bomul.ai.kr/cron/analytics/daily-aggregate?date=...
 * (app/Config/Routes.php에서 이 라우트를 setDefaultNamespace('App\Controllers\Web') 호출
 * *전에* 등록해뒀다 - RouteCollection::create()가 등록 시점의 defaultNamespace를 그 자리에서
 * 구워넣기 때문에, 이 라우트만 앱 전역 기본값(App\Controllers)으로 풀려서 App\Controllers\Web
 * 브랜치 규칙에 안 걸리고 App\Controllers\Cron\Analytics로 정확히 잡힌다. 자세한 설명은
 * Routes.php의 해당 라우트 주석 참고)
 *
 * SHOP_CODE 해석: Host 헤더의 첫 세그먼트 = fid = shop_code (예: foray.bomul.ai.kr → foray).
 * Track.php와 동일하게 Web\BaseController::set_domain()을 거치지 않고 직접 최소 해석한다.
 *
 * 완료 콜백: analy.php는 이 요청의 응답을 기다리지 않는 fire-and-forget이라(짧은 타임아웃),
 * 완료 여부는 bomul.batch_schedule을 봐야 안다 (job_type='analytics_daily') - 그래서 집계가
 * 끝나면 이 컨트롤러가 직접 api.bomul.ai.kr/callback/complete를 호출해서 그 테이블을 갱신시킨다.
 * analy.php가 먼저 연결을 끊어도 이 요청 자체(및 콜백 발신)는 끝까지 실행되도록
 * ignore_user_abort(true) 필수.
 *
 * 인증: 생성자에서 호출 IP가 .env의 CRON_ALLOWED_IPS(내부 서버 IP만) 목록에 없으면 즉시 차단
 * (analy.php가 아닌 곳에서 이 요청이 올 수 없게 문 앞에서 막음). 이 서브도메인의 나머지
 * 경로는 전부 사용자 공개페이지라 토큰 인증 관례가 없다 - 같은 서버에서 셀프 curl하는 구조라
 * IP 스푸핑이 사실상 불가능하므로(TCP 핸드셰이크를 완성 못 함) 토큰 없이 IP 체크만으로 막는다
 * (앞단에 리버스프록시/로드밸런서가 생기면 REMOTE_ADDR이 프록시 IP로 보일 수 있으니 그때 재검토).
 * 컨트롤러 생성 시점엔 아직 $this->request가 없어서(initController()가 __construct() 이후에
 * 호출됨 - CodeIgniter::createController() 참고) IP는 $_SERVER['REMOTE_ADDR']을 직접 읽는다.
 */
class Analytics extends Controller
{
    // bomul.batch_schedule에서 이 job을 구분하는 값 (app/batch/analy.php의 JOB_TYPE과 일치해야 함)
    private const JOB_TYPE = 'analytics_daily';

    private $log;

    public function __construct()
    {
        if (!$this->checkAllowedIp()) {
            http_response_code(403);
            exit;
        }
        ignore_user_abort(true); // analy.php가 응답 안 기다리고 먼저 끊어도 집계+콜백은 끝까지 실행
        $this->log = $this->getMonoLog('cron_analytics');
        return $this;
    }

    // .env의 CRON_ALLOWED_IPS(콤마구분)에 있는 IP만 허용. 미설정 시 127.0.0.1/::1만 기본 허용
    // - 배포 후 실제로 analy.php가 어떤 IP로 찍히는지 로그로 확인해서 .env에 채워넣어야 한다.
    private function checkAllowedIp()
    {
        $configured = env('CRON_ALLOWED_IPS', '127.0.0.1,::1');
        $allowed = array_map('trim', explode(',', $configured));
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        return in_array($remoteIp, $allowed, true);
    }

    public function getDailyAggregate()
    {
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

        $ok = true;
        try {
            $service = new AnalyticsAggregateService($this->log, SHOP_CODE);
            $summary = $service->aggregateDaily($date);
            $ok = (bool)($summary['site_ok'] ?? false) && (bool)($summary['property_ok'] ?? false);
        } catch (\Throwable $e) {
            $ok = false;
            $summary = ['error' => $e->getMessage()];
            $this->log->error('[REV] cron analytics 집계 실패 shop_code=' . $shopCode . ' error=' . $e->getMessage());
        }

        $this->log->info('[REV] cron analytics 실행 shop_code=' . $shopCode . ' summary=' . json_encode($summary, JSON_UNESCAPED_UNICODE));

        $this->sendCallback($shopCode, $ok);

        echo AT_Result::toJson(AT_Result::toSuccessData("", $summary));
    }

    // 집계 완료(성공/실패)를 중앙 스케줄링 테이블에 반영하도록 콜백 전송.
    // 콜백 대상이 api.bomul.co.kr이라 그쪽 관례대로 Authorization 헤더 토큰을 실어야 한다
    // (여기 Cron\Analytics 자신은 {fid}.bomul.co.kr=web이라 IP체크만 하는 것과 대비됨).
    private function sendCallback($fid, $ok)
    {
        $secret = env('CRON_SECRET', '');
        $url = 'http://api.bomul.ai.kr/callback/complete?ok=' . ($ok ? '1' : '0')
            . '&fid=' . urlencode($fid) . '&job_type=' . urlencode(self::JOB_TYPE);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $secret],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->log->info('[REV] callback 전송 fid=' . $fid . ' ok=' . var_export($ok, true) . ' http=' . $httpCode);
    }

    // Host 헤더의 첫 세그먼트 = fid = shop_code (예: foray.bomul.ai.kr → foray)
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
