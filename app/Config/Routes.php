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
    $routes->get('property', 'Property::getindex');
    //상세보기
    $routes->get('notice/view/([0-9]+)(?:/[^/]*)?', 'Notice::getview/$1');
    $routes->get('review/view/([0-9]+)(?:/[^/]*)?', 'Review::getview/$1');
    $routes->get('charge/view/([0-9]+)(?:/[^/]*)?', 'Charge::getview/$1');
    $routes->get('property/view/([0-9]+)(?:/[^/]*)?', 'Property::getview/$1');

    // 방문자 트래킹 수집 (sendBeacon)
    $routes->post('track/collect', 'Track::postCollect');
    $routes->options('track/collect', 'Track::optionsCollect');

    // 기본 자동 라우터 활성화
    //$routes->setAutoRoute(true);

} else {

    // OPTIONS(preflight)는 어떤 경로든 여기서 바로 처리 - 컨트롤러마다 optionsXxx() 안 만들어도 됨
    $routes->options('(:any)', static function () {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');
        return service('response')->setStatusCode(200);
    });

    // 🔥 API는 기존 그대로 (Api/Admin 자동 라우팅 유지)
    $routes->setDefaultNamespace('App\Controllers');
    $routes->setAutoRoute(true);
}

