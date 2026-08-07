<?php

/*
 * 마더 배치 - crontab이 매분 이 파일 하나만 호출한다 (* * * * * php app/batch/batch.php).
 * 새 배치가 늘어나도 crontab은 안 건드리고 여기에 시간대별 블록만 추가하면 된다.
 *
 * 의도적으로 CI4 프레임웍을 부팅하지 않는다 - 매분 실행되니 가볍게 유지하고, 실제 작업은
 * curl로 각 중개사 서브도메인을 호출해서 프레임웍을 100% 태운 컨트롤러가 처리한다
 * (app/batch/analy.php, app/Controllers/Cron/* 참고. analy.php 자신은 스케줄링 테이블을
 * 다뤄야 해서 거기서만 CI4를 가볍게 부팅함 - 여기 batch.php는 여전히 순수 스케줄 체크만).
 *
 * analytics_daily가 시(02시) 안에서 "한 번만"이 아니라 매분 계속 require되는 이유: 한 번에
 * 몇 개씩만(MAX_CONCURRENT, analy.php 참고) 처리하고 콜백으로 완료를 확인하는 구조라, 단발성
 * 트리거로는 남은 대상을 마저 못 보낸다. analy.php가 매분 호출돼도 시딩은 최초 1회만, 남은
 * 작업 없으면 바로 종료하니 안전하다.
 */

$h = date('H');
$i = date('i');

if ($h === '02') {
    // 02시 내내(매분) 호출 - 남은 중개사가 있는 동안 계속 재시도하도록
    require __DIR__ . '/analy.php';
}

// 서비스 신청 승인건 세팅 처리 + 중앙 콜백 - analytics와 달리 특정 시간대에 몰리는 배치가 아니라
// (승인은 아무 때나 일어남) 시간대 조건 없이 항상 매분 호출. pending 없으면 바로 종료하니 부담 없음.
require __DIR__ . '/setting_callback.php';

// 단발성 배치 예시 (시:분까지 정확히 매칭될 때 딱 한 번만):
// if ($h === '03' && $i === '00') {
//     require __DIR__ . '/example.php';
// }

return;
