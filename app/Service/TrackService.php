<?php

namespace App\Service;

use App\Entity\SiteAnalyticsEvent_Entity;
use App\Entity\SiteAnalyticsVisit_Entity;
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
 *   - Service->Model 전달은 다른 기능들과 동일하게 Entity 객체로 한다(배열 직접 전달 금지 룰).
 *   - region(GeoIP)/referrer_source(naver 분류)/device_type(web/mobile 분류) 같은 가공값은
 *     이 테이블에 없다 - 원본(ip/referrer_url/user_agent)만 저장하고 분류는 조회/배치 단계에서 한다.
 *   - 존재 검증 없음: 넘어온 visit_id를 그대로 신뢰하고 저장한다 (관대하게 수집 원칙,
 *     매 이벤트마다 SELECT로 확인하는 비용을 피함).
 *
 * 아직 안 만든 것 (다음 단계):
 *   - GeoIP 배치, 일/월 집계 테이블(site_analytics_daily_stats 등)
 *
 * client_key (신규방문 중복등록 방지):
 *   visit_id는 서버가 발급해서 응답으로 내려주는데, FE가 그 응답을 받기 전에 페이지를 이동하면
 *   (홈→목록 등) 두 번째 페이지도 여전히 visit_id가 없는 상태라 또 "신규 방문"으로 잡혀 visit_id가
 *   두 번 발급될 수 있다. 그래서 FE는 visit_id가 없는 동안 자신이 즉시 만든 client_key를 같이
 *   실어보낸다 (같은 세션이면 항상 같은 값). client_key에는 UNIQUE 인덱스가 걸려있어, 두 요청이
 *   경합해도 먼저 도착한 쪽만 등록되고 나중 요청은 resolveVisitId()가 기존 visit_id를 찾아 재사용한다.
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
        $this->visitModel->getLog($log);
        $this->eventModel = model('App\Models\SiteAnalyticsEventModel', true, $this->db);
        $this->eventModel->getLog($log);
        return $this;
    }

    public function collect($data)
    {
        $visit_id = $data['visit_id'] ?? '';
        $occurred_at = $data['occurred_at'] ?? date('Y-m-d H:i:s');

        if ($visit_id === '') {
            // FE에 visit_id가 없음 = 신규 방문(또는 아직 응답을 못 받은 신규 방문의 후속 요청).
            $visit_id = $this->resolveVisitId($data, $occurred_at);
        }
        // visit_id가 이미 있으면(=같은 세션의 이후 이벤트) site_analytics_visit은 건드리지 않는다.

        $eventEntity = new SiteAnalyticsEvent_Entity();
        $eventEntity->fill($this->filterEventData(array_merge($data, [
            'visit_id'    => $visit_id,
            'event_type'  => $data['event_type'] ?? 'pageview',
            'occurred_at' => $occurred_at,
        ])));
        $this->eventModel->register($eventEntity);

        return AT_Result::toSuccessData("", ["visit_id" => $visit_id]);
    }

    // visit_id 없는 요청에 대해 신규 등록을 시도하고, 최종적으로 사용할 visit_id를 반환한다.
    // client_key 중복(먼저 도착한 요청이 이미 등록됨)이면 새로 발급한 visit_id는 버리고
    // 기존 visit_id를 재사용한다.
    private function resolveVisitId($data, $occurred_at)
    {
        $client_key = $data['client_key'] ?? '';
        $new_visit_id = bin2hex(random_bytes(16));

        $visitEntity = new SiteAnalyticsVisit_Entity();
        $visitEntity->fill($this->filterVisitData(array_merge($data, [
            'visit_id'    => $new_visit_id,
            'client_key'  => $client_key,
            'occurred_at' => $occurred_at,
        ])));
        $inserted = $this->visitModel->register($visitEntity);

        if ($inserted) {
            $this->log->info("[REV] 신규 방문 생성 visit_id: " . $new_visit_id);
            return $new_visit_id;
        }

        // client_key가 이미 등록돼 있었음 - 먼저 도착한 요청이 발급한 visit_id를 재사용
        $existing_visit_id = $client_key !== '' ? $this->visitModel->getVisitIdByClientKey($client_key) : null;
        $visit_id = $existing_visit_id ?? $new_visit_id; // 조회 실패 시에도 진행은 되도록 방금 만든 값으로 폴백
        $this->log->info("[REV] client_key 중복, 기존 visit_id 재사용: " . $visit_id);
        return $visit_id;
    }

    // SiteAnalyticsVisit_Entity에 실제 정의된 필드만 통과 (오타/미정의 필드가 SQL로 새는 것 방지)
    private function filterVisitData($data)
    {
        $entity = new SiteAnalyticsVisit_Entity();
        return array_intersect_key($data, $entity->toRawArray());
    }

    // SiteAnalyticsEvent_Entity에 실제 정의된 필드만 통과
    private function filterEventData($data)
    {
        $entity = new SiteAnalyticsEvent_Entity();
        return array_intersect_key($data, $entity->toRawArray());
    }
}
