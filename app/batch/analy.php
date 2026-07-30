<?php

/*
 * 새벽 배치 - 중개사별 일별 통계 집계 dispatcher.
 * app/batch/batch.php(마더)가 활성 시간대(예: 02:00~02:30) 동안 매분 이 파일을 require한다.
 *
 * 완료 확인은 콜백 기반이라(api.bomul.co.kr/callback/complete, Cron\Analytics가 집계를 끝내면
 * 직접 호출) 이 스크립트는 curl 응답을 기다리지 않는다 - 그 순간 "몇 개가 아직 처리중인지"는
 * bomul.analytics_batch_schedule 테이블을 보고 판단한다 (AnalyticsBatchScheduleModel 참고).
 * 매분 재실행돼도 안전하도록 설계됨:
 *   - 오늘자(run_date) 시딩은 최초 1회만 (existsForDate로 가드)
 *   - 동시 처리 개수를 MAX_CONCURRENT로 제한 (in-flight 개수만큼 다음 배치에서 빼고 보냄)
 *
 * Model/DB 접근이 필요해서 CI4를 CLI 모드로 부팅한다 - spark가 쓰는 bootSpark()보다 가벼운
 * bootConsole()만 사용 (커맨드 라우팅 없이 서비스/설정/오토로더만 필요).
 */

define('FCPATH', __DIR__ . '/../../public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

const MAX_CONCURRENT = 3;
const BASE_DOMAIN = 'bomul.co.kr';
const FIRE_TIMEOUT_MS = 300; // fire-and-forget - 요청이 서버에 도달할 정도만 기다리고 응답은 안 기다림

$runDate = date('Y-m-d', strtotime('-1 day')); // AnalyticsAggregateService 기본값과 동일 (어제)

$db = db_connect('default');
$scheduleModel = model('App\Models\AnalyticsBatchScheduleModel', true, $db);
$helpModel = model('App\Models\HelpModel', true, $db);

// 1. 오늘자 시딩 (최초 1회만)
if (!$scheduleModel->existsForDate($runDate)) {
    $fids = $helpModel->get_active_fids();
    $scheduleModel->seed($runDate, $fids);
}

// 2. 남은 작업 없으면 조용히 종료 (batch.php가 활성창 내내 매분 불러도 여기서 바로 빠짐)
if (!$scheduleModel->hasRemainingWork($runDate)) {
    return;
}

// 3. 동시처리 한도만큼만 다음 배치를 뽑아서 fire-and-forget
$inFlight = $scheduleModel->countInFlight($runDate);
$capacity = MAX_CONCURRENT - $inFlight;
$fids = $scheduleModel->claimNextBatch($runDate, $capacity);

foreach ($fids as $fid) {
    $url = "http://{$fid}." . BASE_DOMAIN . "/cron/analytics/daily-aggregate?date=" . urlencode($runDate);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_TIMEOUT_MS => FIRE_TIMEOUT_MS,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    curl_exec($ch); // 타임아웃으로 끊겨도 무방 - 서버 쪽은 ignore_user_abort(true)로 계속 실행됨
    curl_close($ch);
    log_message('info', "[analy.php] fid={$fid} 발송 (run_date={$runDate})");
}
