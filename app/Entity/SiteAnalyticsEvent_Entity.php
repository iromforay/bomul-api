<?php

namespace App\Entity;

class SiteAnalyticsEvent_Entity extends CI_Entity
{
    /**
     * Entity Attributes
     */
    protected $attributes = [

        'event_no' => null,                // 이벤트번호 (PK, auto_increment)

        'visit_id' => null,                 // 소속 방문 (site_analytics_visit.visit_id)
        'event_type' => null,               // 이벤트종류 (pageview/dwell/click)
        'occurred_at' => null,              // 발생시각 (클라이언트 기준)

        'page_type' => null,                // 요청 처리한 페이지종류 (home/list/detail/notice 등)
        'property_no' => null,              // 매물 상세 조회시 property.property_no
        'request_url' => null,              // 요청 URL 원본 (쿼리스트링 포함)

        'dwell_ms' => null,                 // 체류시간(ms), event_type=dwell 일 때만 사용
        'click_action' => null,             // 클릭행동 (tel/favorite/share 등), event_type=click 일 때만 사용
        'search_filter' => null,            // 검색 필터 입력값 원본(JSON), 검색/목록 페이지에서만 사용
    ];

    /**
     * Attribute Type Casting
     */
    protected $casts = [

        'event_no' => 'integer',

        'visit_id' => 'string',
        'event_type' => 'string',
        'occurred_at' => 'datetime',

        'page_type' => 'string',
        'property_no' => 'integer',
        'request_url' => 'string',

        'dwell_ms' => 'integer',
        'click_action' => 'string',
        'search_filter' => 'string',
    ];

}
