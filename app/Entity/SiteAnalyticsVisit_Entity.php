<?php

namespace App\Entity;

class SiteAnalyticsVisit_Entity extends CI_Entity
{
    /**
     * Entity Attributes
     */
    protected $attributes = [

        'visit_id' => null,                // 서버 발급 방문 식별키 (PK - auto_increment 아님)
        'client_key' => null,              // FE 자체 발급 임시키. visit_id 응답 전 페이지 이동 시
                                            // 중복 신규등록 방지용 (UNIQUE) - visit_id 확정되면 더는 안 씀

        'occurred_at' => null,             // 최초 진입시각

        'ip' => null,                      // 접속 IP 원본. region 변환은 별도 단계

        'referrer_url' => null,            // HTTP Referer 원본. 유입경로 분류는 별도 단계
        'utm_source' => null,              // UTM 파라미터 원본 - 유입채널 (예: naver, kakao)
        'utm_medium' => null,              // UTM 파라미터 원본 - 매체 (예: cpc, banner)
        'utm_campaign' => null,            // UTM 파라미터 원본 - 캠페인명

        'user_agent' => null,              // User-Agent 원본. 기기/브라우저 판별은 별도 단계
        'viewport_w' => null,              // 뷰포트 가로(px)
        'viewport_h' => null,              // 뷰포트 세로(px)
        'is_returning' => null,            // 재방문여부 Y/N
    ];

    /**
     * Attribute Type Casting
     */
    protected $casts = [

        'visit_id' => 'string',
        'client_key' => 'string',

        'occurred_at' => 'datetime',

        'ip' => 'string',

        'referrer_url' => 'string',
        'utm_source' => 'string',
        'utm_medium' => 'string',
        'utm_campaign' => 'string',

        'user_agent' => 'string',
        'viewport_w' => 'integer',
        'viewport_h' => 'integer',
        'is_returning' => 'string',
    ];

}
