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
  `visit_id` VARCHAR(64) NOT NULL COMMENT '클라이언트 발급 방문 식별키 (UUID)',

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
