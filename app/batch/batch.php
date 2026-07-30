<?php

/*
 * 마더 배치 - crontab이 매분 이 파일 하나만 호출한다 (* * * * * php app/batch/batch.php).
 * 새 배치가 늘어나도 crontab은 안 건드리고 여기 $jobs 배열에만 등록하면 된다.
 *
 * 의도적으로 CI4 프레임웍을 부팅하지 않는다 - 매분 실행되니 가볍게 유지하고, 실제 작업은
 * curl로 각 중개사 서브도메인을 호출해서 프레임웍을 100% 태운 컨트롤러가 처리한다
 * (app/batch/analy.php, app/Controllers/Cron/* 참고. analy.php 자신은 스케줄링 테이블을
 * 다뤄야 해서 거기서만 CI4를 가볍게 부팅함 - 여기 batch.php는 여전히 순수 스케줄 체크만).
 *
 * analytics_daily는 한 번에 몇 개씩만(MAX_CONCURRENT, analy.php 참고) 처리하고 콜백으로
 * 완료를 확인하는 구조라 단발성 트리거가 아니라 "활성 시간대" 동안 매분 계속 불러야 한다 -
 * analy.php가 매분 호출돼도 시딩은 최초 1회만, 남은 작업 없으면 바로 종료하니 안전하다.
 */

$now = date('H:i');

$jobs = [
    ['name' => 'analytics_daily', 'window_start' => '02:00', 'window_end' => '02:30', 'script' => __DIR__ . '/analy.php'],
];

foreach ($jobs as $job) {
    if ($now >= $job['window_start'] && $now <= $job['window_end']) {
        error_log("[batch.php] {$job['name']} 체크 ({$now})");
        require $job['script'];
    }
}
