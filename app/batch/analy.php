<?php

/*
 * 새벽 배치 - 중개사별 일별 통계 집계 dispatcher.
 * app/batch/batch.php(마더)가 스케줄 시각에 이 파일을 require한다.
 *
 * 프레임웍을 부팅하지 않고 순수 curl만 쓴다 - 실제 집계 로직은 app/Controllers/Cron/*가
 * CI 프레임웍을 100% 태워 처리하고, 이 파일은 "누구를 부를지"만 담당한다 (member 목록도
 * DB 직결이 아니라 Cron\Member 엔드포인트를 curl로 불러서 얻는다 - 그래야 이 파일이 진짜로
 * 가벼운 스크립트로 남는다).
 */

$BASE_DOMAIN = 'bomul.co.kr';
$TIMEOUT_SEC = 30;

// .env는 프레임웍이 쓰는 것과 동일한 파일을 그대로 읽는다 (CRON_SECRET만 필요)
$envPath = __DIR__ . '/../../.env';
$cronSecret = '';
if (is_file($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        if (trim($k) === 'CRON_SECRET') {
            $cronSecret = trim($v);
            break;
        }
    }
}
if ($cronSecret === '') {
    error_log('[analy.php] CRON_SECRET 미설정 - 배치 중단');
    return;
}

function analy_curl_get($url, $timeout)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $timeout,
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    return $body;
}

// 1. 활성 중개사(fid) 목록 조회 - 중앙(bomul) DB는 api.bomul.co.kr을 통해서만 접근
$listUrl = "http://api.{$BASE_DOMAIN}/cron/member/activefids?token=" . urlencode($cronSecret);
$listJson = json_decode(analy_curl_get($listUrl, $TIMEOUT_SEC), true);
$fids = $listJson['RECORD']['fids'] ?? [];

if (empty($fids)) {
    error_log('[analy.php] 활성 중개사 목록이 비어있음 - 배치 중단');
    return;
}

// 2. 중개사별 병렬 curl - 서로 다른 DB만 건드리므로 병렬로 쏴도 서로 부하를 안 줌
$mh = curl_multi_init();
$handles = [];
foreach ($fids as $fid) {
    $url = "http://{$fid}.{$BASE_DOMAIN}/cron/analytics/daily-aggregate?token=" . urlencode($cronSecret);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $TIMEOUT_SEC,
    ]);
    curl_multi_add_handle($mh, $ch);
    $handles[$fid] = $ch;
}

$running = null;
do {
    curl_multi_exec($mh, $running);
    curl_multi_select($mh);
} while ($running > 0);

foreach ($handles as $fid => $ch) {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $result = curl_multi_getcontent($ch);
    error_log("[analy.php] fid={$fid} http={$httpCode} result=" . substr((string)$result, 0, 300));
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}
curl_multi_close($mh);
