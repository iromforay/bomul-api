<?php

/*
 * 새벽 배치 - 중개사별 일별 통계 집계 dispatcher.
 * app/batch/batch.php(마더)가 활성 시간대(예: 02:00~02:30) 동안 매분 이 파일을 require한다.
 *
 * 완료 확인은 콜백 기반이라(api.bomul.ai.kr/callback/complete, Cron\Analytics가 집계를 끝내면
 * 직접 호출) 이 스크립트는 curl 응답을 기다리지 않는다 - 그 순간 "몇 개가 아직 처리중인지"는
 * bomul.batch_schedule 테이블을 보고 판단한다 (BatchScheduleModel 참고 - job_type='analytics_daily'
 * 로 다른 배치 종류와 구분되는 범용 테이블이라, 나중에 다른 배치가 생기면 같은 테이블/로직을
 * job_type만 바꿔서 재사용하면 된다).
 * 매분 재실행돼도 안전하도록 설계됨:
 *   - 오늘자(run_date+job_type) 시딩은 최초 1회만 (existsForDate로 가드)
 *   - 동시 처리 개수를 MAX_CONCURRENT로 제한 (in-flight 개수만큼 다음 배치에서 빼고 보냄)
 *
 * Model/DB 접근이 필요해서 CI4를 CLI 모드로 부팅한다 - spark가 쓰는 bootSpark()보다 가벼운
 * bootConsole()만 사용 (커맨드 라우팅 없이 서비스/설정/오토로더만 필요).
 *
 * bootConsole()엔 bootWeb()/bootSpark()에 있는 defineEnvironment() 호출이 빠져있어서, ENVIRONMENT
 * 상수가 정의되기 전에 loadEnvironmentBootstrap()이 그 상수를 참조해 죽는 프레임웍 버그가 있다
 * (system/util_bootstrap.php의 bootConsole() 사용 예시도 호출 전에 ENVIRONMENT를 직접 정의해둠 -
 * 같은 이유). 그래서 .env를 먼저 직접 로드해 CI_ENVIRONMENT 기준으로 상수를 미리 정의해서
 * bootConsole() 내부에서 빠진 그 단계를 대신 채워준다.
 */

define('FCPATH', __DIR__ . '/../../public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';

require $paths->systemDirectory . '/Config/DotEnv.php';
(new \CodeIgniter\Config\DotEnv($paths->envDirectory ?? $paths->appDirectory . '/../'))->load();
if (!defined('ENVIRONMENT')) {
    $env = $_ENV['CI_ENVIRONMENT'] ?? $_SERVER['CI_ENVIRONMENT'] ?? getenv('CI_ENVIRONMENT') ?: 'production';
    define('ENVIRONMENT', $env);
}

\CodeIgniter\Boot::bootConsole($paths);

const JOB_TYPE = 'analytics_daily'; // batch_schedule에서 이 job을 구분하는 값 (Cron\Analytics의 콜백과 일치해야 함)
const MAX_CONCURRENT = 3;
const BASE_DOMAIN = 'bomul.ai.kr';
const FIRE_TIMEOUT_MS = 300; // fire-and-forget - 요청이 서버에 도달할 정도만 기다리고 응답은 안 기다림

$runDate = date('Y-m-d', strtotime('-1 day')); // AnalyticsAggregateService 기본값과 동일 (어제)

$db = db_connect('default');
$scheduleModel = model('App\Models\BatchScheduleModel', true, $db);
$helpModel = model('App\Models\HelpModel', true, $db);

// 1. 오늘자 시딩 (최초 1회만)
if (!$scheduleModel->existsForDate(JOB_TYPE, $runDate)) {
    $fids = $helpModel->get_active_fids();
    $scheduleModel->seed(JOB_TYPE, $runDate, $fids);
}

// 2. 남은 작업 없으면 조용히 종료 (batch.php가 활성창 내내 매분 불러도 여기서 바로 빠짐)
if (!$scheduleModel->hasRemainingWork(JOB_TYPE, $runDate)) {
    return;
}

// 3. 동시처리 한도만큼만 다음 배치를 뽑아서 fire-and-forget
$inFlight = $scheduleModel->countInFlight(JOB_TYPE, $runDate);
$capacity = MAX_CONCURRENT - $inFlight;
$fids = $scheduleModel->claimNextBatch(JOB_TYPE, $runDate, $capacity);

foreach ($fids as $fid) {
    $url = "https://{$fid}." . BASE_DOMAIN . "/cron/analytics/daily-aggregate?date=" . urlencode($runDate);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_TIMEOUT_MS => FIRE_TIMEOUT_MS,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    curl_exec($ch); // 타임아웃으로 끊겨도 무방 - 서버 쪽은 ignore_user_abort(true)로 계속 실행됨
    curl_close($ch);
    log_message('info', "[analy.php] fid={$fid} 발송 (run_date={$runDate})");
}
