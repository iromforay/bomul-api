# 서비스 & 라이브 구조
이문서는 서비스 서버 , 라이브 서버의 DATABASE 구조에 대한 설명을 정리한 문서이다.

## 1. 서비스 구조
```
database : bomul
```

|TABLE명 | 설명                 |
|---|--------------------|
|`customer`| 사용자 정보             |
|`manager` | 서비스 관리자 정보         |
|`payment` | 결제정보               |
|`product` | 상품                 |
|`queue` | live 서버로 push할 큐   |
|`server_list`| 서버관리               |
|`service_apply`| 서비스 신청             |
|`service_live`| 운영중인 사이트 관리        |
|`batch_schedule`| 범용배치 진행 모니터(통계,세팅)|


## 2. 라이브 서버 구조
```
database : bomul (공통)
```

|TABLE명 | 설명 |
|---|---|
|setting_queue| 사이트 세팅 큐|
|member| live에서 등록된 관리자 id-password|
|login_session|로그인 세션 정보|

```
database : {fid}(사용자 subdomain)
```

|TABLE명 | 설명                        |
|---|---------------------------|
|add_menu | 메뉴|
|basic| 사이즈 기본정보|
|board| 게시판설정|
|build||
|button_menu| 버튼 설정|
|function_menu| 매물 등록 설정|
|img| 이미지 등록 정보|
|login_session||
|memo| 메모|
|menu| 기본메뉴|
|note| 관리자 노트(방문일지)|
|pager_detail||
|pager_list||
|post| 게시물|
|profile| 프로필|
|property| 매물|
|property_analytics_daily| 매물 통계|
|site_analytics_daily_stats| 방문통계||
|site_analytics_event| 방문자 트래킹 - 행동 이벤트|
|site_analytics_visit | 방문자 트래킹 - 방문(세션) 단위 원본 수집 |


## 3. API