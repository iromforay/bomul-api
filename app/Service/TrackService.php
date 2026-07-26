<?php

namespace App\Service;

use App\Libraries\AT_Result;

/*
 * 방문자 트래킹 - 수집 서비스.
 *
 * 설계 배경(다른 세션에서 이어받을 때 참고):
 *   - site_analytics_visit : 방문(세션) 1회 = 1행. referrer/utm/ip/user_agent 등
 *     세션당 한 번만 정해지는 값. visit_id가 PK이고, 이 행은 딱 한 번만 INSERT된다.
 *   - site_analytics_event : 방문 중 발생하는 pageview/dwell/click 각각 1행.
 *     event_no가 PK, visit_id는 그냥 인덱스 컬럼(FK 역할) - 같은 visit_id로 여러 행이 쌓인다.
 *   - visit_id는 "페이지 1회 로드" 단위가 아니라 "세션(방문)" 단위다. FE가 sessionStorage에
 *     들고 있다가, 페이지를 이동해도(홈→목록→상세) 같은 visit_id를 계속 실어보낸다.
 *   - 엔티티(Entity)는 아직 만들지 않았다 - "지시받은 것만 생성" 합의에 따라 배열을 그대로 쓴다.
 *   - region(GeoIP)/referrer_source(naver 분류)/device_type(web/mobile 분류) 같은 가공값은
 *     이 테이블에 없다 - 원본(ip/referrer_url/user_agent)만 저장하고 분류는 조회/배치 단계에서 한다.
 *   - 존재 검증 없음: 넘어온 visit_id를 그대로 신뢰하고 저장한다 (관대하게 수집 원칙,
 *     매 이벤트마다 SELECT로 확인하는 비용을 피함).
 *
 * 아직 안 만든 것 (다음 단계):
 *   - footer.php에 들어갈 JS 수집 스크립트 (visit_id 발급/보관, referrer PHP embedding,
 *     pagehide/visibilitychange 기반 dwell 계산, sendBeacon 전송)
 *   - GeoIP 배치, 일/월 집계 테이블(site_analytics_daily_stats 등)
 */
class TrackService extends CI_Service
{
    private $visitModel;
    private $eventModel;
    private $log;

    public function __construct($log = "", $session_id = "")
    {
        $this->log = $log;
        parent::__construct($session_id);
        $this->visitModel = model('App\Models\SiteAnalyticsVisitModel', true, $this->db);
        $this->eventModel = model('App\Models\SiteAnalyticsEventModel', true, $this->db);
        return $this;
    }

    public function collect($data)
    {
        $visit_id = $data['visit_id'] ?? '';
        $occurred_at = $data['occurred_at'] ?? date('Y-m-d H:i:s');

        if ($visit_id === '') {
            // FE에 visit_id가 없음 = 신규 방문. 새로 발급하고 site_analytics_visit에 1행만 남긴다.
            $visit_id = bin2hex(random_bytes(16));

            $this->visitModel->sql_save([
                'visit_id'     => $visit_id,
                'occurred_at'  => $occurred_at,
                'ip'           => $data['ip'] ?? null,
                'referrer_url' => $data['referrer_url'] ?? null,
                'utm_source'   => $data['utm_source'] ?? null,
                'utm_medium'   => $data['utm_medium'] ?? null,
                'utm_campaign' => $data['utm_campaign'] ?? null,
                'user_agent'   => $data['user_agent'] ?? null,
                'viewport_w'   => $data['viewport_w'] ?? null,
                'viewport_h'   => $data['viewport_h'] ?? null,
                'is_returning' => $data['is_returning'] ?? null,
            ]);

            $this->log->info("[REV] 신규 방문 생성 visit_id: " . $visit_id);
        }
        // visit_id가 이미 있으면(=같은 세션의 이후 이벤트) site_analytics_visit은 건드리지 않는다.

        $this->eventModel->sql_save([
            'visit_id'      => $visit_id,
            'event_type'    => $data['event_type'] ?? 'pageview',
            'occurred_at'   => $occurred_at,
            'page_type'     => $data['page_type'] ?? null,
            'property_no'   => $data['property_no'] ?? null,
            'request_url'   => $data['request_url'] ?? null,
            'dwell_ms'      => $data['dwell_ms'] ?? null,
            'click_action'  => $data['click_action'] ?? null,
            'search_filter' => $data['search_filter'] ?? null,
        ]);

        return AT_Result::toSuccessData("", ["visit_id" => $visit_id]);
    }
}
