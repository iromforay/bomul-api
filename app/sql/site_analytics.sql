-- ============================================================
-- 보물(BOMUL) 방문자 트래킹 - 수집 데이터 테이블 (가공/통계 데이터 아님)
--
-- 설계 원칙:
--   - 이 테이블들엔 BE 요청/클라이언트 이벤트 발생 "그 순간" 넘어온 값만
--     그대로 저장한다. 해석/분류(가공)는 이후 단계(배치/조회 로직)의
--     역할이지 수집 테이블의 역할이 아니다.
--     예) web/mobile 같은 기기분류값은 저장하지 않고, 분류의 재료가 되는
--         원본 user_agent 문자열만 저장한다. naver/google 같은 유입경로
--         분류값도 저장하지 않고, 원본 referrer_url만 저장한다.
--     동일한 이유로 region(광역시도)도 제외했다 - IP를 GeoIP로 변환한
--     가공값이고, 요청 시점이 아니라 이후 배치가 채워넣는 값이라
--     "요청 순간 저장" 원칙에 맞지 않는다. ip 원본만 저장해두고,
--     region 매핑은 별도 테이블/단계에서 다룬다.
--   - 방문(세션) 단위로 한 번만 정해지는 값과, 방문 중 여러 번 발생하는
--     행동(pageview/dwell/click) 값을 분리했다:
--       site_analytics_visit : 방문 1회 = 1행 (원본 referrer/utm/ip/user_agent 등)
--       site_analytics_event : 방문 중 발생한 행동 N회 = N행 (visit_id로 연결)
--   - office_id/shop_code 컬럼 없음: SHOP_CODE별로 DB 자체가 분리되어 불필요
--     (note.sql과 동일 원칙)
--   - 전량 수집 원칙: 필수값 최소화, 나머지는 NULL 허용 (봇 필터링/엄격한
--     검증 없이 관대하게 적재)
--   - FK 제약조건 없음: 참조무결성은 PHP 애플리케이션 레벨에서 처리
--     (이 스키마 다른 테이블과 동일 컨벤션)
-- ============================================================

-- ============================================================
-- 방문(세션) - 방문 1회당 1행, 이후 UPDATE 없음
-- ============================================================
CREATE TABLE `site_analytics_visit` (
  `visit_id` VARCHAR(64) NOT NULL COMMENT '서버 발급 방문 식별키',
  `client_key` VARCHAR(64) DEFAULT NULL COMMENT 'FE 자체 발급 임시키. visit_id 응답 전 페이지 이동 시 중복 신규등록 방지용',

  `occurred_at` DATETIME NOT NULL COMMENT '최초 진입시각',

  `ip` VARCHAR(45) DEFAULT NULL COMMENT '접속 IP 원본 (IPv4/IPv6). region 변환은 별도 단계',

  `referrer_url` VARCHAR(500) DEFAULT NULL COMMENT 'HTTP Referer 원본. 유입경로 분류는 별도 단계',
  `utm_source` VARCHAR(100) DEFAULT NULL COMMENT 'UTM 파라미터 원본 - 유입채널 (예: naver, kakao)',
  `utm_medium` VARCHAR(100) DEFAULT NULL COMMENT 'UTM 파라미터 원본 - 매체 (예: cpc, banner)',
  `utm_campaign` VARCHAR(100) DEFAULT NULL COMMENT 'UTM 파라미터 원본 - 캠페인명',

  `user_agent` VARCHAR(255) DEFAULT NULL COMMENT 'User-Agent 원본. 기기/브라우저 판별은 별도 단계',
  `viewport_w` SMALLINT UNSIGNED DEFAULT NULL COMMENT '뷰포트 가로(px)',
  `viewport_h` SMALLINT UNSIGNED DEFAULT NULL COMMENT '뷰포트 세로(px)',
  `is_returning` VARCHAR(1) DEFAULT NULL COMMENT '재방문여부 Y/N (쿠키/localStorage 존재 여부 그대로)',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수집(적재)시각',

  PRIMARY KEY (`visit_id`),
  UNIQUE KEY `uk_client_key` (`client_key`),
  KEY `idx_occurred_at` (`occurred_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 방문(세션) 단위 원본 수집';

-- ============================================================
-- 행동 이벤트 - 방문 중 발생한 pageview/dwell/click 각각 1행, append-only
-- ============================================================
CREATE TABLE `site_analytics_event` (
  `event_no` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '이벤트번호',
  `visit_id` VARCHAR(64) NOT NULL COMMENT '소속 방문 (site_analytics_visit.visit_id)',

  `event_type` VARCHAR(16) NOT NULL COMMENT '이벤트종류 (pageview/dwell/click)',
  `occurred_at` DATETIME NOT NULL COMMENT '발생시각 (클라이언트 기준)',

  `page_type` VARCHAR(32) DEFAULT NULL COMMENT '요청 처리한 페이지종류 (home/list/detail/notice 등, 코드상 직접 결정되는 값)',
  `property_no` INT UNSIGNED DEFAULT NULL COMMENT '매물 상세 조회시 property.property_no',
  `request_url` VARCHAR(500) DEFAULT NULL COMMENT '요청 URL 원본 (쿼리스트링 포함)',

  `dwell_ms` INT UNSIGNED DEFAULT NULL COMMENT '체류시간(ms), event_type=dwell 일 때만 사용',
  `click_action` VARCHAR(32) DEFAULT NULL COMMENT '클릭행동 (tel/favorite/share 등), event_type=click 일 때만 사용',
  `search_filter` TEXT DEFAULT NULL COMMENT '검색 필터 입력값 원본(JSON), 검색/목록 페이지에서만 사용',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수집(적재)시각',

  PRIMARY KEY (`event_no`),
  KEY `idx_visit_id` (`visit_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_occurred_at` (`occurred_at`),
  KEY `idx_property_no` (`property_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 행동 이벤트 원본 (append-only)';

-- ============================================================
-- 아래부터는 위 원본 수집 테이블과 반대로 "가공된 일별 집계" 테이블이다.
--
-- 왜 필요한가: site_analytics_event는 계속 쌓이기만 하는 시계열 테이블이라
-- "이번 달 추이" 같은 실시간 GROUP BY 쿼리는 시간이 지날수록 스캔 범위가
-- 계속 늘어난다. 새벽 배치가 원본을 하루 단위로 미리 집계해서 여기 넣어두면,
-- 대시보드는 원본을 조회하지 않고 이 테이블만 날짜 범위로 읽는다 - 조회 비용이
-- 원본 누적량과 무관하게 "조회하는 날짜 수"에만 비례하게 된다.
--
-- 네이버 유입 판별 규칙(배치 스크립트에서 사용, 넓게 잡는 원칙):
--   referrer_url LIKE '%naver.com%' OR referrer_url LIKE '%naver.me%' OR utm_source = 'naver'
--   (검색/블로그/카페/지도/부동산/예약 등 naver.com 서브도메인 전부 포함,
--    naver.me는 카카오톡/문자 공유용 네이버 단축링크)
-- ============================================================

-- ============================================================
-- 사이트 종합 일별 통계 - 1행/일, 새벽 배치가 매일 INSERT
-- ============================================================
CREATE TABLE `site_analytics_daily_stats` (
  `stat_date` DATE NOT NULL COMMENT '집계 대상 날짜',

  `visitor_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '고유 방문자수 (그날 visit_id 수)',
  `pageview_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '페이지뷰수 (pageview 이벤트 수)',
  `new_visitor_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '신규 방문자수 (is_returning=N)',
  `returning_visitor_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '재방문자수 (is_returning=Y)',

  `naver_referral_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '네이버 유입 방문자수 (판별 규칙은 위 주석 참고)',
  `direct_referral_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '직접 유입 (referrer_url 없음)',
  `other_referral_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '기타 유입 (네이버/직접 외)',

  `pc_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'PC 방문자수 (user_agent 기준)',
  `mobile_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '모바일 방문자수 (user_agent 기준)',

  `phone_click_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '전화클릭 수 (click_action=tel)',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '배치 적재시각',

  PRIMARY KEY (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 사이트 종합 일별 집계 (새벽 배치 생성)';

-- ============================================================
-- 매물별 일별 통계 - 1행/매물/일, 새벽 배치가 매일 INSERT
-- ============================================================
CREATE TABLE `property_analytics_daily` (
  `stat_date` DATE NOT NULL COMMENT '집계 대상 날짜',
  `property_no` INT UNSIGNED NOT NULL COMMENT 'property.property_no',

  `view_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '그날 조회수 (pageview 이벤트 수)',
  `avg_dwell_ms` INT UNSIGNED DEFAULT NULL COMMENT '그날 평균 체류시간(ms), dwell 이벤트 기준',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '배치 적재시각',

  PRIMARY KEY (`stat_date`, `property_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 매물별 일별 집계 (새벽 배치 생성)';
