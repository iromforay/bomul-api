<?php

/*
 * 세팅 처리 + 콜백 cron - Receiver가 로컬 setting_queue에 적재해둔 걸 처리하고, 중앙(bomul-bo)에
 * 결과를 보고한다. batch.php(마더)가 매분 require한다.
 *
 * 실제 설치(DB 생성/스키마/기본데이터/디렉토리)는 App\Libraries\TenantProvisioner가 담당한다.
 *
 * CI4 부팅 방식은 analy.php와 동일 (bootConsole() - spark보다 가벼움, ENVIRONMENT 상수를
 * DotEnv 로드 후 직접 정의해서 프레임웍 버그 우회). 자세한 이유는 analy.php 주석 참고.
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

const CALLBACK_TIMEOUT_MS = 3000; // 콜백은 fire-and-forget이 아니라 성공/실패를 확인해야 markCallbackSent 여부를 정함

$db = db_connect('default');
$settingModel = model('App\Models\SettingQueueModel', true, $db);
$provisioner = new \App\Libraries\TenantProvisioner();

// 1. 처리 - action='new'는 DB 생성+스키마/기본데이터+member 등록, action='update'는
// member.service만 Y/N로 바꾸는 가벼운 처리 (DB/스키마는 안 건드림)
$pending = $settingModel->get_pending();
foreach ($pending as $row) {
    $payload = json_decode($row['payload'], true) ?? [];
    $action  = $row['action'] ?? 'new';

    $result = $action === 'update'
        ? $provisioner->updateMemberStatus($row['fid'], $payload)
        : $provisioner->provision($row['fid'], $payload);

    $settingModel->markResult($row['setting_no'], $result['ok'] ? 'DONE' : 'FAILED', $result['message']);
    log_message($result['ok'] ? 'info' : 'error', "[setting_callback] setting_no={$row['setting_no']} fid={$row['fid']} action={$action} - {$result['message']}");
}

// 2. 콜백 - 처리는 끝났는데 아직 중앙에 못 알린 것들 (콜백 실패로 재시도되는 것도 이 조건에 자연히 다시 걸림)
$needCallback = $settingModel->get_pending_callback();

$serverCode = env('SERVER_CODE', '');
$apiKey = env('RECEIVER_API_KEY', '');
$callbackUrl = env('CENTRAL_CALLBACK_URL', '');

foreach ($needCallback as $row) {
    $payload = json_decode($row['payload'], true) ?? [];
    $queueNo = (int) ($payload['queue_no'] ?? $row['queue_no']);

    $ch = curl_init($callbackUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'server_code'   => $serverCode,
            'queue_no'      => $queueNo,
            'status'        => $row['status'], // DONE | FAILED
            'error_message' => $row['result_message'],
        ]),
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
        CURLOPT_TIMEOUT_MS     => CALLBACK_TIMEOUT_MS,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $settingModel->markCallbackSent($row['setting_no']);
        log_message('info', "[setting_callback] setting_no={$row['setting_no']} 콜백 완료");
    } else {
        log_message('error', "[setting_callback] setting_no={$row['setting_no']} 콜백 실패 (http={$httpCode}) - 다음 주기에 재시도됨");
    }
}
