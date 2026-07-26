<?php

namespace App\Entity;

class SiteAnalyticsEvent_Entity extends CI_Entity
{
    /**
     * Entity Attributes
     */
    protected $attributes = [

        'event_no' => null,                // 이벤트번호

        'visit_id' => null,                // 페이지 진입 단위 그룹핑 키 (클라이언트 발급 UUID)
        'event_type' => null,              // 이벤트종류 (pageview/dwell/click)
        'occurred_at' => null,             // 발생시각 (클라이언트 기준)

        'ip' => null,                      // 접속 IP
        'region' => null,                  // 광역 시/도 (GeoIP 배치가 채움)
        'referrer_url' => null,            // HTTP Referer 원본
        'referrer_source' => null,         // 유입경로 (naver/google/direct/sns/other)
        'utm_source' => null,
        'utm_medium' => null,
        'utm_campaign' => null,

        'device_type' => null,             // 접속기기 (web/mobile)
        'page_type' => null,               // 페이지종류 (home/list/detail/notice 등)
        'property_no' => null,             // 매물 상세 조회시 property.property_no
        'request_url' => null,             // 요청 URL

        'dwell_ms' => null,                // 체류시간(ms), event_type=dwell
        'click_action' => null,            // 클릭행동 (tel/favorite/share 등), event_type=click
        'viewport_w' => null,              // 뷰포트 가로(px)
        'viewport_h' => null,              // 뷰포트 세로(px)
        'is_returning' => null,            // 재방문여부 Y/N
        'search_filter' => null,           // 검색 필터 사용값 (JSON)
    ];

    /**
     * Attribute Type Casting
     */
    protected $casts = [

        'event_no' => 'integer',

        'visit_id' => 'string',
        'event_type' => 'string',
        'occurred_at' => 'datetime',

        'ip' => 'string',
        'region' => 'string',
        'referrer_url' => 'string',
        'referrer_source' => 'string',
        'utm_source' => 'string',
        'utm_medium' => 'string',
        'utm_campaign' => 'string',

        'device_type' => 'string',
        'page_type' => 'string',
        'property_no' => 'integer',
        'request_url' => 'string',

        'dwell_ms' => 'integer',
        'click_action' => 'string',
        'viewport_w' => 'integer',
        'viewport_h' => 'integer',
        'is_returning' => 'string',
        'search_filter' => 'string',
    ];

}
