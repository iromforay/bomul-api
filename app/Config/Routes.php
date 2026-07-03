<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->setAutoRoute(true);
//$routes->get('/', 'Home::index');

$host = $_SERVER['HTTP_HOST'] ?? '';
if ($host !== 'api.bomul.co.kr') {

    // 🌐 WEB 전용: 모든 요청을 Web namespace로 자동 라우팅
    $routes->setDefaultNamespace('App\Controllers\Web');

    // 기본 자동 라우터 활성화
    $routes->setAutoRoute(true);

} else {

    // 🔥 API는 기존 그대로 (Api/Admin 자동 라우팅 유지)
    $routes->setDefaultNamespace('App\Controllers');
    $routes->setAutoRoute(true);
}

