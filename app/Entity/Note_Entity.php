<?php

namespace App\Entity;

class Note_Entity extends CI_Entity
{
    /**
     * Entity Attributes
     */
    protected $attributes = [

        'note_no' => null,                 // 노트번호
        'agent_no' => null,                // 접수자(담당 중개사, member.member_no)
        'property_no' => null,             // 관심매물 (property.property_no)

        'visitor_name' => null,            // 방문자명
        'visitor_phone' => null,           // 방문자 연락처

        'visit_type' => null,              // 방문형태 (tenant:세입자, landlord:임대인)
        'transaction_type' => null,        // 거래방식 (sale:매매, jeonse:전세, monthly:월세)

        'budget' => null,                  // 예산(만원단위)
        'preferred_area' => null,          // 희망지역

        'inflow_type' => 'visit',          // 유입경로 (visit/phone/web/naver/referral/etc)
        'status' => 'consulting',          // 진행상태 (consulting/proposed/contracted/lost)

        'visit_date' => null,              // 방문예정일
        'next_contact_date' => null,       // 다음연락예정일
    ];

    /**
     * Attribute Type Casting
     */
    protected $casts = [

        'note_no' => 'integer',
        'agent_no' => 'integer',
        'property_no' => 'integer',

        'visitor_name' => 'string',
        'visitor_phone' => 'string',

        'visit_type' => 'string',
        'transaction_type' => 'string',

        'budget' => 'integer',
        'preferred_area' => 'string',

        'inflow_type' => 'string',
        'status' => 'string',

        'visit_date' => 'datetime',
        'next_contact_date' => 'datetime',
    ];

}
