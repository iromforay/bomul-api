<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->setAutoRoute(true);
//$routes->get('/', 'Home::index');

$host = $_SERVER['HTTP_HOST'] ?? '';
if ($host !== 'api.bomul.co.kr') {

    // 🌐 WEB 전용: 모든 요청을 Web namespace로 자동 라우팅
    $routes->setDefaultNamespace('App\Controllers\Web');

    $routes->get('/',        'Home::getindex');
    $routes->get('around',   'Around::getindex');
    $routes->get('charge',   'Charge::getindex');
    $routes->get('map',      'Map::getindex');
    $routes->get('notice',   'Notice::getindex');
    $routes->get('review',   'Review::getindex');
    //상세보기
    $routes->get('notice/view/([0-9]+)(?:/[^/]*)?', 'Notice::getview/$1');
    $routes->get('review/view/([0-9]+)(?:/[^/]*)?', 'Review::getview/$1');
    $routes->get('charge/view/([0-9]+)(?:/[^/]*)?', 'Charge::getview/$1');

    // 방문자 트래킹 수집 (sendBeacon)
    $routes->post('track/collect', 'Track::postCollect');
    $routes->options('track/collect', 'Track::optionsCollect');

    // 기본 자동 라우터 활성화
    //$routes->setAutoRoute(true);

} else {

    // 🔥 API는 기존 그대로 (Api/Admin 자동 라우팅 유지)
    $routes->setDefaultNamespace('App\Controllers');
    $routes->setAutoRoute(true);
}

