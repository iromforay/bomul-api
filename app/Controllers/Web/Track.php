<?php

namespace App\Controllers\Web;

use App\Libraries\AT_Result;
use App\Service\TrackService;
use CodeIgniter\Controller;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
 * 방문자 트래킹 수집 컨트롤러.
 *
 * ── 지금까지 합의된 설계 요약 (다른 세션에서 이어서 진행할 때 여기부터 읽으면 됨) ──
 *
 * 목적: 네이버 검색유입 증명, 사이트 활성화 시각화. 봇 필터링/엄격한 검증 없이
 * 관대하게 전량 수집한다 (네이버 유입 판별만 정확해야 함 - 단, 판별 로직은
 * "수집" 단계가 아니라 이후 조회/배치 단계 몫. 이 테이블엔 원본 referrer_url만 저장).
 *
 * 테이블 (app/sql/site_analytics.sql):
 *   - site_analytics_visit : 방문(세션) 1회 = 1행. PK = visit_id. 세션 시작 시 딱 1번만 INSERT.
 *   - site_analytics_event : 방문 중 발생한 pageview/dwell/click 각각 1행. PK = event_no(auto),
 *                             visit_id는 그냥 인덱스 컬럼 - 같은 visit_id로 여러 행 허용.
 *
 * visit_id는 "페이지 1회 로드" 단위가 아니라 "세션(방문)" 단위다.
 * FE가 발급받은 visit_id를 sessionStorage에 저장해두고, 같은 세션 동안(홈→목록→상세 이동해도)
 * 계속 재사용해서 매 요청에 실어보낸다. 그래야 방문 여정(퍼널) 추적이 가능하다.
 *
 * 수집 흐름 (footer.php + track.js):
 *   1. 페이지 로드 시 sessionStorage에 visit_id 있는지 확인
 *   2. 없으면(신규 방문) visit_id 없이 pageview 이벤트 전송 → 이 컨트롤러가 새로 발급해서 응답으로 내려줌
 *      → FE는 응답받은 visit_id를 sessionStorage에 저장
 *   3. 있으면 그 visit_id를 그대로 실어서 pageview(페이지 이동)/dwell(이탈시)/click(클릭시) 전송
 *   4. dwell은 폴링(setInterval) 없이 `visibilitychange`(hidden)/`pagehide` 이벤트 시점에만 전송
 *      (dwell_ms는 FE가 진입~이탈 시각차를 직접 계산해서 실어보냄 - 서버는 그냥 저장만 함)
 *   5. 모든 전송은 `navigator.sendBeacon()` (unload 중에도 유실 없이 fire-and-forget).
 *      단, 신규 방문의 첫 pageview만 응답(visit_id)을 읽어야 해서 fetch를 쓴다 (아래 client_key 참고)
 *
 * client_key - visit_id 응답 전 페이지 이동 대응:
 *   2번 응답을 받기 전에 FE가 페이지를 이동하면, 다음 페이지도 여전히 visit_id가 없어 또 신규로
 *   잡힐 수 있다. 그래서 FE는 visit_id가 없는 동안 자신이 즉시 생성해 sessionStorage에 박아둔
 *   client_key를 매 요청에 같이 실어보낸다(같은 세션이면 항상 같은 값). 서버는 client_key에 건
 *   UNIQUE 인덱스로 중복 신규등록을 걸러내고 기존 visit_id를 재사용한다 (TrackService::collect()
 *   / resolveVisitId() 참고). visit_id가 응답으로 확정되면 그 뒤로 client_key는 더 보내지 않는다.
 *
 * referrer_url / user_agent / ip 를 누가 채우는가:
 *   - ip, user_agent: 이 컨트롤러가 이 요청 자체에서 직접 읽는다 (아래 postCollect 참고).
 *     User-Agent는 매 요청 동일한 값이 실리는 헤더라 relay가 필요 없다.
 *   - referrer_url: 반드시 "랜딩 페이지"의 PHP가 그 페이지 요청의 $_SERVER['HTTP_REFERER']를
 *     읽어서 JS 변수로 심어두고, JS가 그 값을 그대로 이 엔드포인트에 relay해야 한다.
 *     (이 트래킹 요청 자체의 Referer 헤더는 "트래킹 스크립트를 호출한 현재 페이지" 주소라
 *     쓸모없음 - document.referrer도 브라우저 정책상 비어있는 경우가 있어 신뢰도가 낮음)
 *   - utm_source/medium/campaign: 랜딩 페이지 URL 쿼리스트링에서 JS가 직접 읽으면 됨
 *     (location.search 파싱 - PHP 개입 불필요)
 *
 * region(GeoIP)/referrer_source(naver 분류)/device_type(web/mobile 분류) 같은 가공값은
 * 저장하지 않는다 - 원본만 저장, 분류는 이후 조회/배치 단계 몫 (site_analytics.sql 헤더 참고).
 *
 * 완료된 것:
 *   - JS 수집 스크립트: public/resources/js/common/track.js (정적 파일) - visit_id 발급/보관,
 *     pagehide/visibilitychange 기반 dwell, sendBeacon 전송까지 구현됨.
 *     common/footer.php가 페이지별 값(page_type/property_no/referrer)을 BOMUL_TRACK_CONFIG로
 *     심어준 뒤 track.js를 include한다 (정적 파일이라 PHP 값을 직접 embed할 수 없어서 분리).
 *     click 수집은 data-track-click 속성 위임 방식 - 현재는 footer의 예약하기/카톡채널추가
 *     버튼에만 붙어있음. 다른 클릭 포인트(매물 전화/찜 등) 생기면 같은 속성만 추가하면 됨.
 *   - Entity: SiteAnalyticsVisit_Entity / SiteAnalyticsEvent_Entity. Service->Model 전달은
 *     다른 기능들과 동일하게 배열이 아닌 Entity 객체로 한다(TrackService::collect() 참고).
 *     단, visit_id는 auto_increment PK가 아닌 클라이언트 발급 값이라 SiteAnalyticsVisitModel::
 *     register()는 toSqlSet()에서 primary_key를 제외하지 않는다 - 다른 register()들과 다른 점.
 *
 * 아직 안 만든 것:
 *   - GeoIP 배치, 일/월 집계 테이블(site_analytics_daily_stats 등) - 수집과는 별개 작업
 *
 * Web\BaseController를 상속하지 않은 이유:
 *   그 생성자가 body()/listing()까지 항상 실행해서(메뉴/기본설정 조회 쿼리 포함) 트래킹처럼
 *   가벼워야 하는 엔드포인트엔 불필요하게 무거움. SHOP_CODE 해석만 최소한으로 복제했다.
 */
class Track extends Controller
{
    private $log;
    private $service;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');
        header('Access-Control-Allow-Credentials: true');

        // Web\BaseController::set_domain()과 동일한 방식. 지금은 공개 웹사이트가
        // "foray" 하나뿐이라 고정값이다. 여러 도메인을 지원하게 되면 Host 헤더 기반으로
        // SHOP_CODE를 resolve하는 로직이 필요한데, 그건 이 트래킹 기능과 별개로
        // Web\BaseController::set_domain() 쪽에서 먼저 풀어야 할 선행 과제다.
        if (!defined('SHOP_CODE')) {
            define('SHOP_CODE', 'foray');
        }

        $this->log = $this->getMonoLog('track_api');
        $this->service = new TrackService($this->log, SHOP_CODE);

        return $this;
    }

    public function postCollect()
    {
        $data = [
            'visit_id'    => $this->request->getVar('visit_id'),
            'client_key'  => $this->request->getVar('client_key'), // visit_id 없을 때만 사용 (중복 신규등록 방지)
            'event_type'  => $this->request->getVar('event_type'), // pageview / dwell / click
            'occurred_at' => $this->request->getVar('occurred_at'),

            // 신규 방문일 때만 site_analytics_visit에 저장됨 (visit_id 없을 때)
            'referrer_url' => $this->request->getVar('referrer_url'),
            'utm_source'   => $this->request->getVar('utm_source'),
            'utm_medium'   => $this->request->getVar('utm_medium'),
            'utm_campaign' => $this->request->getVar('utm_campaign'),
            'viewport_w'   => $this->request->getVar('viewport_w'),
            'viewport_h'   => $this->request->getVar('viewport_h'),
            'is_returning' => $this->request->getVar('is_returning'),

            // 매 이벤트마다 site_analytics_event에 저장됨
            'page_type'     => $this->request->getVar('page_type'),
            'property_no'   => $this->request->getVar('property_no'),
            'request_url'   => $this->request->getVar('request_url'),
            'dwell_ms'      => $this->request->getVar('dwell_ms'),      // event_type=dwell
            'click_action'  => $this->request->getVar('click_action'), // event_type=click
            'search_filter' => $this->request->getVar('search_filter'),

            // relay 아님 - 이 요청 자체에서 직접 읽음
            'ip'         => $this->request->getIPAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ];

        $this->log->info('[REV] track collect : ' . json_encode($data, JSON_UNESCAPED_UNICODE));

        $oResult = $this->service->collect($data);

        echo AT_Result::toJson($oResult);
        exit;
    }

    /* preflight */
    public function optionsCollect()
    {
        return $this;
    }

    public function getMonoLog($log_name = 'track')
    {
        $file_name = SHOP_CODE . '_log_' . date('Ymd') . '.log';

        $log = new Logger($log_name);
        $log->pushHandler(new StreamHandler(LOG_DIR . '/' . date('Ym') . '/' . $file_name, Logger::DEBUG));

        return $log;
    }
}
