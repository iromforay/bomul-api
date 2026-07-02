<?php

namespace App\Entity;

class Property_Entity
{
    //property_image
    //property_option

private $property_no;         //매물번호

    /* 기본 */
    private $property_type;         // 매물종류 (
    private $transaction_type;      // 거래방식 (1-매매 , 2-전세 , 3-월세)

    private $property_name;         // 매물명
    private $building_name;         // 건물명

    /* 주소 */
    private $zipcode;              //우편번호
    private $address;              //기본주소
    private $address_detail;       //상세주소
    private $sido;                 //시도
    private $sigungu;               //시군구
    private $dong;                  //동

    /* 위치 */
    private $latitude;             // 좌표
    private $longitude;            // 좌표

    /* 면적 */
    private $supply_area;           // 공급면적(㎡)
    private $exclusive_area;        // 전용면적(㎡)

    /* 층 */
    private $total_floor;           //총층수
    private $current_floor;         //해당층수

    /* 가격 */
    private $sale_price;            // 매매가
    private $jeonse_price;          // 전세가
    private $deposit_price;         // 보증금
    private $monthly_rent;          // 월세
    private $loan_amount;           // 융자금
    private $maintenance_fee;       // 관리비

    /* 방 정보 */
    private $room_count;            // 방개수
    private $bathroom_count;        // 화장실수

    /* 주차 */
    private $parking_available;     // 주차가능여부 Y/N
    private $parking_count;        //총주차가능대수
//엘리베이터
    /* 입주 */
    private $move_in_date ;         //입주가능일
    private $move_in_type;          //입주 가능 형태 - 즉시입주, 협의, 날짜지정

    /* 건물 */
    private $completion_date;       // 준공일
    private $direction;             // 향 (E 동 , W 서 , S 남 , N 북 ,SE 남동 SW 남서 , NE 북동 NW 북서)
    private $heating_fuel;          // 난방 연료 (1:도시가스 , 2:전기 , 3 : LPG, 4:기름 , 9:기타)
    private $heating_type;           // 난방 방식(1:개별난방 , 2:중앙난방 , 3: 지역난방)
    /* 상태 */
    private $transaction_status;    // 거래상태 ( 1:거래가능, 2:계약중, 3: 거래완료, 4:거래보류, 5:비공개)
    private $display_yn;            // 홈페이지 노출 (1:노출 , 0: 숨김)
    private $recommend_yn;          // 추천매물

    /* 내용 */
    private $summary;               // 한줄소개
    private $description;           // 상세설명

    /* 통계 */
    private $view_count;           // 뷰

    /* 관리 */
    private $sort_order;            // 정렬
    private $created_at;            //등록일
    private $updated_at;            //수정일
    private $deleted_at ;           //삭제일

}

/*
테이블	필수 여부	설명
property	✅	매물 기본 정보
property_image	✅	사진 여러 장 관리
property_option	✅	옵션 정보

 * */

//월 15만원
//매물 등록 요청기능 - 단순 메모 (이름,주소,전화번호, 매매방식)
