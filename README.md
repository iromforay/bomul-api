# 보물(BOMUL) API

부동산 중개사무소를 위한 멀티테넌트 매물 관리 SaaS 백엔드. CodeIgniter 4(PHP) 기반이며, 하나의 코드베이스가 **관리자용 API 서버**와 **중개사별 공개 웹사이트**를 동시에 서빙한다.

## 기술 스택

- PHP 8.2+ / CodeIgniter 4.7
- MySQL (MySQLi 드라이버)
- Monolog (로깅)
- nginx + PHP-FPM
- Let's Encrypt(certbot) SSL

## 멀티테넌트 아키텍처

- **fid(shop_code)** = 중개사 코드 = 서브도메인 = 물리 DB 이름. 중개사 1곳당 DB 1개(`{fid}` 스키마)를 쓰고, `bomul`이라는 중앙 DB에 테넌트 간 공유 데이터(`member`, `login_session`, `batch_schedule`)를 둔다.
- `CI_Service` 베이스 클래스가 `db_connect('default')` 후 `setDatabase(SHOP_CODE)`로 요청마다 해당 중개사 DB로 전환한다.
- **호스트 기반 라우팅 분기** (`app/Config/Routes.php`):
  - `api.bomul.ai.kr` → 오토라우팅, `App\Controllers` 네임스페이스, 관리자 FE와 통신하며 `Authorization: Bearer` 토큰 필수.
  - 그 외 전부(`{fid}.bomul.ai.kr`) → 명시적 라우트, `App\Controllers\Web` 네임스페이스, 공개 사용자 웹페이지(MVC), 토큰 불필요.

## 디렉토리 구조

```
app/
  Controllers/
    Admin/      관리자(BO) API - Bearer 토큰 인증
    API/        구 관리자 API (일부 레거시)
    Web/        중개사 공개 웹사이트 (서브도메인 기준)
    Cron/       내부 배치 전용 엔드포인트 (IP 화이트리스트)
    Callback.php  배치 완료 콜백 수신
  Service/      비즈니스 로직 (CI_Service 상속 - DB 자동 전환)
  Models/       DB 접근 계층
  Entity/       CI4 엔티티 (Property_Entity 등)
  Libraries/    NaverGeocoder, ImageWatermark 등 커스텀 라이브러리
  batch/        crontab이 직접 호출하는 프레임워크 밖 배치 스크립트
public/
  resources/    css/js/img (RS_CSS, RS_JS, RS_IMG 상수로 참조)
html/           BO 화면 시안(mockup) - 신규 목업은 전부 이 디렉토리에 생성
nginx.conf      80포트(http) 서버블록
ssl.conf        443포트(https) 서버블록
```

## 주요 컨벤션

- API 응답: `App\Libraries\AT_Result::toSuccessData()` / `toFailData()` / `toJson()`
- 관리자 인증: `Admin\BaseController::is_auth()` — `Authorization: Bearer` → `login_session` 조회 → `SHOP_CODE` 확정
- 자체 인증이 필요한 경량 컨트롤러(`Track`, `Sitemap`, `Robots`, `Cron\Analytics`, `Admin\Map` 등)는 무거운 `BaseController` 대신 CI4 `Controller`를 직접 상속하고 필요한 인증 로직만 자체 구현
- PHP 전역 상수: `APPPATH`, `APP_PDS`, `LOG_DIR`, `RS_CSS`, `RS_JS`, `RS_IMG`, `RS_TH`, `SITE_PDS`, `COMMON_PDS`, `FCPATH`, `VIEW`, `FRESH_TIME`, `SHOP_CODE` (`app/Config/Boot/development.php`에서 정의)

## 로컬 개발 환경

1. `composer install`
2. `.env.example`(또는 기존 `.env` 템플릿)을 참고해 `.env` 생성 후 아래 값 채우기:
   - `database.default.*`, `database.login.*` — DB 접속 정보
   - `CRON_SECRET`, `CRON_ALLOWED_IPS` — 배치 콜백 인증/IP 화이트리스트
   - `map.ClientID`, `map.Secret` — 네이버 지도 Geocoding/Static Map API 키
   - `LOG_DIR` — 로그 저장 경로
   - **실제 키/비밀번호 값은 절대 커밋하지 않는다.**
3. PHP 내장 서버 또는 로컬 nginx로 구동

## 배포

수동 SFTP 배포 (CI/CD 파이프라인 없음). 운영 서버: Ubuntu, `/home/bomul/git/bomul-api`.

- `nginx.conf` → 80포트 서버블록, 서버의 `/etc/nginx/sites-enabled/`에 해당
- `ssl.conf` → 443포트 서버블록. `bo`/`api`는 개별 인증서(certbot `--nginx`, 자동 갱신), `*.bomul.ai.kr`(신규 매장 서브도메인 포함)는 와일드카드 인증서(certbot DNS-01 수동, 90일 주기 - `ssl.conf` 상단 주석에 최근 작업일/만료일/다음 작업 기한 기록)

## 배치(크론) 파이프라인

방문자 통계 일별 집계용 마더-워커 구조.

```
crontab → app/batch/batch.php (매분 실행, 새벽 2시에만 동작)
        → app/batch/analy.php (활성 fid 전체를 순회하며 fire-and-forget curl)
        → {fid}.bomul.ai.kr/cron/analytics/daily-aggregate  (Cron\Analytics, IP 화이트리스트 인증)
        → 집계 완료 후 api.bomul.ai.kr/callback/complete 호출 (Callback, Bearer 토큰 인증)
        → bomul.batch_schedule 테이블에 pending → sent → done 갱신
```

- 수동 재실행(백필): `curl 'https://{fid}.bomul.ai.kr/cron/analytics/daily-aggregate?date=YYYY-MM-DD'` (허용 IP에서만 가능, 같은 날짜로 재실행해도 안전 - UPSERT 기반 멱등 처리)
- 원본 이벤트(`site_analytics_visit/event`)를 매번 실시간 집계하지 않고 `site_analytics_daily_stats` / `property_analytics_daily`에 하루 단위로 미리 적재 — 대시보드 조회 비용이 원본 누적량과 무관하게 조회 기간에만 비례하도록 하기 위함
