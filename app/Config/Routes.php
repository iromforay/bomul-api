<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->setAutoRoute(true);
//$routes->get('/', 'Home::index');

$host = $_SERVER['HTTP_HOST'] ?? '';
if ($host !== 'api.bomul.ai.kr') {

    // setDefaultNamespace('App\Controllers\Web') 호출 *전에* 등록 - RouteCollection::create()가
    // 라우트 등록 시점의 defaultNamespace를 그 자리에서 핸들러 문자열에 구워넣기 때문에, 이 한 줄만
    // 앱 전역 기본값(Config\Routing::$defaultNamespace = 'App\Controllers')으로 풀리고 아래
    // setDefaultNamespace('App\Controllers\Web')의 영향을 안 받는다. App\Controllers\Cron
    // 네임스페이스로 맞추기 위한 의도적 배치 순서.
    // 새벽 배치(app/batch/analy.php)가 중개사 서브도메인({fid}.bomul.co.kr)으로 curl 호출.
    $routes->get('cron/analytics/daily-aggregate', 'Cron\Analytics::getDailyAggregate');

    // 🌐 WEB 전용: 모든 요청을 Web namespace로 자동 라우팅
    $routes->setDefaultNamespace('App\Controllers\Web');

    $routes->get('mapsearchtest', 'MapSearchTest::getIndex');
    $routes->get('/',        'Home::getindex');
    $routes->get('around',   'Around::getindex');
    $routes->get('charge',   'Charge::getindex');
    $routes->get('map',      'Map::getindex');
    $routes->get('notice',   'Notice::getindex');
    $routes->get('review',   'Review::getindex');
    $routes->get('sitemap',   'Sitemap::getIndex');
    $routes->get('roboots',   'Robots::getIndex');
    $routes->get('property', 'Property::getindex');
    //상세보기
    $routes->get('notice/view/([0-9]+)(?:/[^/]*)?', 'Notice::getview/$1');
    $routes->get('review/view/([0-9]+)(?:/[^/]*)?', 'Review::getview/$1');
    $routes->get('charge/view/([0-9]+)(?:/[^/]*)?', 'Charge::getview/$1');
    $routes->get('property/view/([0-9]+)(?:/[^/]*)?', 'Property::getview/$1');

    // 방문자 트래킹 수집 (sendBeacon)
    $routes->post('track/collect', 'Track::postCollect');
    $routes->options('track/collect', 'Track::optionsCollect');

    // Admin\Map(네이버 지도 주소검색 API) 개발용 테스트 페이지 - 정식 FE 생기면 제거
    $routes->get('map-search-test', 'MapSearchTest::getIndex');

    // SEO - 검색엔진 크롤링용. 중개사마다 내용이 달라서 정적 파일이 아니라 동적 생성
    // (public/robots.txt는 삭제함 - 있으면 nginx가 그걸 먼저 서빙해서 이 라우트까지 안 옴)
    $routes->get('robots.txt', 'Robots::getIndex');
    $routes->get('sitemap.xml', 'Sitemap::getIndex');

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

