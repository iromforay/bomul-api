<?php

namespace App\Entity;

class SiteAnalyticsDailyStats_Entity extends CI_Entity
{
    /**
     * Entity Attributes
     */
    protected $attributes = [

        'stat_date' => null,                    // 집계 대상 날짜 (PK)

        'visitor_count' => null,                // 고유 방문자수 (그날 visit_id 수)
        'pageview_count' => null,               // 페이지뷰수 (pageview 이벤트 수)
        'new_visitor_count' => null,            // 신규 방문자수 (is_returning=N)
        'returning_visitor_count' => null,      // 재방문자수 (is_returning=Y)

        'naver_referral_count' => null,         // 네이버 유입 방문자수
        'direct_referral_count' => null,        // 직접 유입 (referrer_url 없음)
        'other_referral_count' => null,         // 기타 유입 (네이버/직접 외)

        'pc_count' => null,                     // PC 방문자수 (user_agent 기준)
        'mobile_count' => null,                 // 모바일 방문자수 (user_agent 기준)

        'phone_click_count' => null,            // 전화클릭 수 (click_action=tel)
    ];

    /**
     * Attribute Type Casting
     */
    protected $casts = [

        'stat_date' => 'string',

        'visitor_count' => 'integer',
        'pageview_count' => 'integer',
        'new_visitor_count' => 'integer',
        'returning_visitor_count' => 'integer',

        'naver_referral_count' => 'integer',
        'direct_referral_count' => 'integer',
        'other_referral_count' => 'integer',

        'pc_count' => 'integer',
        'mobile_count' => 'integer',

        'phone_click_count' => 'integer',
    ];

}
