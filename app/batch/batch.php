<?php

/*
 * 마더 배치 - crontab이 매분 이 파일 하나만 호출한다 (* * * * * php app/batch/batch.php).
 * 새 배치가 늘어나도 crontab은 안 건드리고 여기 $jobs 배열에만 등록하면 된다.
 *
 * 의도적으로 CI4 프레임웍을 부팅하지 않는다 - 매분 실행되니 가볍게 유지하고, 실제 작업은
 * curl로 각 중개사 서브도메인을 호출해서 프레임웍을 100% 태운 컨트롤러가 처리한다
 * (app/batch/analy.php, app/Controllers/Cron/* 참고).
 */

$now = date('H:i');

$jobs = [
    ['name' => 'analytics_daily', 'time' => '02:00', 'script' => __DIR__ . '/analy.php'],
];

foreach ($jobs as $job) {
    if ($job['time'] === $now) {
        error_log("[batch.php] {$job['name']} 실행 ({$now})");
        require $job['script'];
    }
}
