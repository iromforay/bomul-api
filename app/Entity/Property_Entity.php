<?php

namespace App\Entity;


/*
class Property_Entity extends Entity
{

private $property_no;         //매물번호

    // 기본 
    private $property_type;  // 매물종류
    // (apt : 아파트, officetel : 오피스텔, villa : 빌라/연립, house : 단독주택, land : 토지,
    // commercial : 상가, office : 사무실, factory : 공장, warehouse : 창고, knowledge : 지식산업센터,
    // building : 건물, motel : 숙박시설, etc : 기타)
    private $transaction_type;      // 거래방식 (sale : 매매 , jeonse : 전세 ,  monthly : 월세)

    private $property_name;         // 매물명
    private $building_name;         // 건물명

    // 주소 
    private $zipcode;              //우편번호
    private $address;              //기본주소
    private $address_detail;       //상세주소
    private $sido;                 //시도
    private $sigungu;               //시군구
    private $dong;                  //동

    // 위치 
    private $latitude;             // 좌표
    private $longitude;            // 좌표

    // 면적 
    private $supply_area;           // 공급면적(㎡)
    private $exclusive_area;        // 전용면적(㎡)

    // 층 
    private $total_floor;           //총층수
    private $current_floor;         //해당층수

    // 가격 
    private $sale_price;            // 매매가
    private $jeonse_price;          // 전세가
    private $deposit_price;         // 보증금
    private $monthly_rent;          // 월세
    private $loan_amount;           // 융자금
    private $maintenance_fee;       // 관리비

    // 방 정보 
    private $room_count;            // 방개수
    private $bathroom_count;        // 화장실수

    // 주차 
    private $parking_available;     // 주차가능여부 Y/N
    private $parking_count;        //총주차가능대수
//엘리베이터
    // 입주 
    private $move_in_date ;         //입주가능일
    private $move_in_type;          //입주 가능 형태 - 즉시입주, 협의, 날짜지정

    // 건물 
    private $completion_date;       // 준공일
    private $direction;             // 향 (E 동 , W 서 , S 남 , N 북 ,SE 남동 SW 남서 , NE 북동 NW 북서)
    private $heating_fuel;          // 난방 연료 (1:도시가스 , 2:전기 , 3 : LPG, 4:기름 , 9:기타)
    private $heating_type;           // 난방 방식(1:개별난방 , 2:중앙난방 , 3: 지역난방)
    // 상태 
    private $transaction_status;    // 거래상태 ( 1:거래가능, 2:계약중, 3: 거래완료, 4:거래보류, 5:비공개)
    private $display_yn;            // 홈페이지 노출 (1:노출 , 0: 숨김)
    private $recommend_yn;          // 추천매물

    // 내용 
    private $summary;               // 한줄소개
    private $description;           // 상세설명

    // 통계 
    private $view_count;           // 뷰

    // 관리 
    private $sort_order;            // 정렬
    private $created_at;            //등록일
    private $updated_at;            //수정일
    private $deleted_at ;
    

}

테이블	필수 여부	설명
property	✅	매물 기본 정보
property_image	✅	사진 여러 장 관리
property_option	✅	옵션 정보



//월 15만원
//매물 등록 요청기능 - 단순 메모 (이름,주소,전화번호, 매매방식)
 */
class Property_Entity extends CI_Entity
{
    /**
     * Entity Attributes
     */
    /**
     * Entity Attributes
     */
    protected $attributes = [

        'property_no' => null,              // 매물코드
        'property_type'=> null,             // 매물종류
        'transaction_type'=> null,          // 거래방식(sale : 매매 , jeonse : 전세 , monthly : 월세)
        'property_name' => null,            // 매물명
        'building_name' => null,            // 건물명

        'addr_sido' => null,                // 시도
        'addr_sigungu' => null,             // 시군구
        'addr_dong' => null,                // 읍면동
        'addr_road' => null,                // 도로명

        'latitude' => null,                 // 위도 - 주소 저장/수정 시 지오코딩으로 채워짐
        'longitude' => null,                // 경도

        'supply_area' => null,              // 공급면적(㎡)
        'exclusive_area' => null,           // 전용면적(㎡)

        'total_floor' => null,              // 총층수
        'current_floor' => null,            // 해당층

        'room_count' => null,               // 방개수
        'bathroom_count' => null,           // 화장실수

        'parking_available' => 'Y',         // 주차가능여부(Y/N)
        'parking_count' => null,            // 총주차가능대수

        'completion_date' => null,          // 준공일
        'move_in_date' => null,             // 입주가능일

        'direction' => null,                // 방향

        'heating_fuel' => null,             // 난방연료
        'heating_type' => null,             // 난방방식

        'sale_price' => null,               // 매매가(만원)
        'jeonse_price' => null,             // 전세가(만원)
        'deposit_price' => null,            // 보증금(만원)
        'monthly_rent' => null,             // 월세(만원)
        'loan_amount' => null,              // 융자금(만원)
        'maintenance_fee' => null,          // 관리비(만원)

        'status' => 1,                      // 거래상태
        'display_yn' => true,               // 홈페이지노출

        'summary' => null,                  // 한줄소개
        'description' => null,              // 상세설명

        'main_image' => null,               // 대표이미지
        'main_path' => null,                // 대표이미지 path

        'view_count' => null,               // 조회수 - admin 폼에서 안 보내는 값이라 null 유지 (0이면 수정할 때마다 초기화됨)
    ];

    /**
     * Attribute Type Casting
     */
    protected $casts = [

        'property_no' => 'integer',

        'property_type'=> 'string',
        'transaction_type'=> 'string',
        'property_name' => 'string',
        'building_name' => 'string',

        'addr_sido' => 'string',
        'addr_sigungu' => 'string',
        'addr_dong' => 'string',
        'addr_road' => 'string',

        'latitude' => 'string',
        'longitude' => 'string',

        'supply_area' => 'integer',
        'exclusive_area' => 'integer',

        'total_floor' => 'integer',
        'current_floor' => 'integer',

        'room_count' => 'integer',
        'bathroom_count' => 'integer',

        'parking_available' => 'string',
        'parking_count' => 'integer',

        'completion_date' => 'datetime',
        'move_in_date' => 'datetime',

        'direction' => 'string',

        'heating_fuel' => 'integer',
        'heating_type' => 'integer',

        'sale_price' => 'integer',
        'jeonse_price' => 'integer',
        'deposit_price' => 'integer',
        'monthly_rent' => 'integer',
        'loan_amount' => 'integer',
        'maintenance_fee' => 'integer',

        'status' => 'integer',
        'display_yn' => 'boolean',

        'summary' => 'string',
        'description' => 'string',

        'main_image' => 'string',
        'main_path' => 'string',

        'view_count' => 'integer',
    ];

}