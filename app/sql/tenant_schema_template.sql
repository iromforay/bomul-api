/*
 * 신규 상점(테넌트) DB 스키마 템플릿 - full_backup.sql(데모 상점 foray 백업)에서 구조(CREATE TABLE)만
 * 추출하고 데이터(INSERT)는 전부 제거했다. DB명이 하드코딩된 CREATE DATABASE/USE 문도 뺐다 -
 * receiver가 `CREATE DATABASE {fid}` 후 그 DB를 선택한 상태에서 이 파일을 그대로 실행한다.
 *
 * add_menu/menu/function_menu의 기본 데이터는 여기 없다 - tenant_default_data.sql 참고
 * (구조와 필수 기본데이터를 파일로 분리해서, 스키마만 바뀌는 경우와 기본데이터만 바뀌는 경우를
 * 서로 안 건드리고 관리할 수 있게).
 */

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


/*Table structure for table `add_menu` */

DROP TABLE IF EXISTS `add_menu`;

CREATE TABLE `add_menu` (
  `add_menu_no` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) DEFAULT NULL,
  `key` varchar(100) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `show` varchar(10) DEFAULT NULL,
  `img_dir` varchar(255) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`add_menu_no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `basic`;

CREATE TABLE `basic` (
  `basic_no` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) DEFAULT NULL,
  `logo_dir` varchar(255) DEFAULT NULL,
  `logo_name` varchar(255) DEFAULT NULL,
  `logo_show` varchar(10) DEFAULT NULL,
  `favicon_dir` varchar(255) DEFAULT NULL COMMENT '파비콘등록디렉토리',
  `favicon_name` varchar(255) DEFAULT NULL COMMENT '파비콘등록 이미지명',
  `favicon_show` varchar(4) DEFAULT NULL COMMENT '파비콘 사용유무 (N:기본파비콘)',
  `menu_back_color` varchar(10) DEFAULT NULL,
  `menu_back_transparent` varchar(10) DEFAULT NULL,
  `menu_bg_color` varchar(20) DEFAULT NULL,
  `menu_bg_transparent` varchar(10) DEFAULT NULL,
  `main_menu_color` varchar(20) DEFAULT NULL,
  `sub_menu_focus_color` varchar(20) DEFAULT NULL,
  `property_show` varchar(10) DEFAULT 'Y' COMMENT '매물보기',
  `notice_show` varchar(10) DEFAULT 'Y' COMMENT '공지,이벤트보기',
  `around_show` varchar(10) DEFAULT 'Y' COMMENT '둘러보기',
  `charge_show` varchar(10) DEFAULT 'Y' COMMENT '보기',
  `review_show` varchar(10) DEFAULT 'Y' COMMENT '이용후기',
  `vod_show` varchar(10) DEFAULT 'Y' COMMENT '홍보영상',
  `map_show` varchar(10) DEFAULT 'Y' COMMENT '오시는길',
  `link_menu_show` varchar(10) DEFAULT NULL COMMENT '타사이트 링크 설정',
  `naver_reserve` varchar(255) DEFAULT NULL COMMENT '네이버예약',
  `naver_reserve_show` varchar(10) DEFAULT NULL COMMENT '네이버예약 설정',
  `kakao_channel` varchar(255) DEFAULT NULL COMMENT '카카오톡 채널',
  `kakao_channel_show` varchar(10) DEFAULT NULL COMMENT '카카오톡 채널 설정',
  `company_name` varchar(150) DEFAULT NULL COMMENT '',
  `broker_office_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '중개사무소 명칭',
  `broker_address` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '중개사무소 소재지',
  `broker_phone` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '중개사무소 연락처',
  `broker_reg_no` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '중개사무소 등록번호',
  `broker_ceo_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '개업공인중개사 성명',
  `broker_agent_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '소속공인중개사 성명 (있는 경우만)',
  `broker_agent_phone` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '소속공인중개사 연락처 (있는 경우만)',
  `footer_copy` varchar(255) DEFAULT NULL,
  `naver_site_verification` varchar(255) DEFAULT NULL COMMENT '네이버 서치어드바이저 사이트 소유권 확인 코드',
  PRIMARY KEY (`basic_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `board`;

CREATE TABLE `board` (
  `board_no` int(11) NOT NULL AUTO_INCREMENT,
  `bname` varchar(150) DEFAULT NULL,
  `bcode` varchar(100) DEFAULT NULL,
  `header_title` varchar(255) DEFAULT NULL,
  `header_upload` varchar(255) DEFAULT NULL,
  `header_dir` varchar(255) DEFAULT NULL,
  `footer_title` varchar(255) DEFAULT NULL,
  `footer_upload` varchar(255) DEFAULT NULL,
  `footer_dir` varchar(255) DEFAULT NULL,
  `style` varchar(100) DEFAULT NULL,
  `writer` varchar(50) DEFAULT NULL,
  `reader` varchar(50) DEFAULT NULL,
  `lister` varchar(50) DEFAULT NULL,
  `reply` varchar(10) DEFAULT NULL,
  `secret` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`board_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `build`;

CREATE TABLE `build` (
  `build_no` int(11) NOT NULL AUTO_INCREMENT,
  `uses` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `build_title` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `bg_color` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `link_type` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `show_count` int(11) DEFAULT 0,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`build_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `button_menu`;

CREATE TABLE `button_menu` (
  `button_menu_no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(150) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `img_dir` varchar(255) DEFAULT NULL,
  `bg_color` varchar(20) DEFAULT NULL,
  `show` varchar(10) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`button_menu_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `function_menu`;

CREATE TABLE `function_menu` (
  `function_menu_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `function_menu_key` varchar(32) DEFAULT NULL,
  `function_menu_name` varchar(32) DEFAULT NULL,
  `function_menu_value` varchar(4) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`function_menu_no`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `img`;

CREATE TABLE `img` (
  `img_no` int(11) NOT NULL AUTO_INCREMENT,
  `parent_table` varchar(100) DEFAULT NULL,
  `parent_no` int(11) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `img_dir` varchar(255) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`img_no`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `login_session`;

CREATE TABLE `login_session` (
  `login_session_no` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `fid` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`login_session_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `memo`;

CREATE TABLE `memo` (
  `memo_no` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '메모번호',
  `parent_table` varchar(100) DEFAULT NULL COMMENT '연결대상 테이블명 (예: note)',
  `parent_no` int(10) unsigned DEFAULT NULL COMMENT '연결대상 PK',
  `writer_no` int(10) unsigned DEFAULT NULL COMMENT '작성자 (member.member_no)',
  `writer_name` varchar(32) DEFAULT NULL COMMENT '작성자명',
  `memo_text` text DEFAULT NULL COMMENT '메모 내용',
  `write_date` datetime DEFAULT NULL COMMENT '메모등록일',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '삭제여부(0:정상,1:삭제)',
  PRIMARY KEY (`memo_no`),
  KEY `idx_parent` (`parent_table`,`parent_no`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='메모 (1:N, 상담노트 등에서 사용)';

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `menu_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `bgcolor` varchar(20) DEFAULT NULL,
  `transparent` varchar(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title_show` varchar(10) DEFAULT NULL,
  `bgcolor_show` varchar(10) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`menu_no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `note`;

CREATE TABLE `note` (
  `note_no` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '노트번호',
  `agent_no` int(10) unsigned DEFAULT NULL COMMENT '접수자(담당 중개사, member.member_no)',
  `property_no` int(10) unsigned DEFAULT NULL COMMENT '관심매물 (property.property_no)',
  `visitor_name` varchar(50) NOT NULL COMMENT '방문자명',
  `visitor_phone` varchar(20) NOT NULL COMMENT '방문자 연락처',
  `visit_type` varchar(16) DEFAULT NULL COMMENT '방문형태 (tenant:세입자, landlord:임대인)',
  `transaction_type` varchar(16) DEFAULT NULL COMMENT '거래방식 (sale:매매, jeonse:전세, monthly:월세)',
  `budget` bigint(20) unsigned DEFAULT NULL COMMENT '예산(만원단위)',
  `preferred_area` varchar(100) DEFAULT NULL COMMENT '희망지역',
  `inflow_type` varchar(16) NOT NULL DEFAULT 'visit' COMMENT '유입경로 (visit/phone/web/naver/referral/etc)',
  `status` varchar(16) NOT NULL DEFAULT 'consulting' COMMENT '진행상태 (consult_request/consulting/proposed/contracted/lost)',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '삭제여부(0:정상,1:삭제)',
  `visit_date` datetime DEFAULT NULL COMMENT '방문예정일',
  `next_contact_date` date DEFAULT NULL COMMENT '다음연락예정일',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '등록일',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`note_no`),
  KEY `idx_agent` (`agent_no`),
  KEY `idx_property` (`property_no`),
  KEY `idx_status` (`status`),
  KEY `idx_visit_date` (`visit_date`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='고객노트(방문일지)';

DROP TABLE IF EXISTS `pager_detail`;

CREATE TABLE `pager_detail` (
  `pager_detail_no` int(11) NOT NULL AUTO_INCREMENT,
  `pager_no` int(11) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `sorty` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT 'T',
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`pager_detail_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `pager_list`;

CREATE TABLE `pager_list` (
  `pager_list_no` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `style` varchar(100) DEFAULT NULL,
  `reader` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `writedate` datetime DEFAULT NULL,
  PRIMARY KEY (`pager_list_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `post_no` int(11) NOT NULL AUTO_INCREMENT,
  `bcode` varchar(50) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `secret` varchar(10) DEFAULT 'F',
  `tel` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT '1',
  `subject` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `orgin_name` varchar(255) DEFAULT NULL,
  `file_dir` varchar(255) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `add01` varchar(255) DEFAULT NULL,
  `add02` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`post_no`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `profile_no` int(11) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(255) DEFAULT NULL,
  `img_dir` varchar(255) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`profile_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

DROP TABLE IF EXISTS `property`;

CREATE TABLE `property` (
  `property_no` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '매물코드',
  `property_type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '매물종류',
  `transaction_type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '거래방식(sale : 매매 , jeonse : 전세 ,  monthly : 월세)',
  `property_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '매물명',
  `building_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '건물명',
  `addr_sido` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '시도',
  `addr_sigungu` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '시군구',
  `addr_dong` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '읍면동',
  `addr_road` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '도로명',
  `latitude` varchar(20) DEFAULT NULL COMMENT '위도',
  `longitude` varchar(20) DEFAULT NULL COMMENT '경도',
  `supply_area` int(10) unsigned DEFAULT NULL COMMENT '공급면적(㎡)',
  `exclusive_area` int(10) unsigned DEFAULT NULL COMMENT '전용면적(㎡)',
  `total_floor` smallint(5) unsigned DEFAULT NULL COMMENT '총층수',
  `current_floor` smallint(6) DEFAULT NULL COMMENT '해당층',
  `room_count` tinyint(3) unsigned DEFAULT NULL COMMENT '방개수',
  `bathroom_count` tinyint(3) unsigned DEFAULT NULL COMMENT '화장실수',
  `parking_available` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Y' COMMENT '주차가능여부(Y/N)',
  `parking_count` smallint(5) unsigned DEFAULT NULL COMMENT '총주차가능대수',
  `completion_date` datetime DEFAULT NULL COMMENT '준공일',
  `move_in_date` datetime DEFAULT NULL COMMENT '입주가능일',
  `direction` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '방향',
  `heating_fuel` tinyint(3) unsigned DEFAULT NULL COMMENT '난방연료',
  `heating_type` tinyint(3) unsigned DEFAULT NULL COMMENT '난방방식',
  `sale_price` int(10) unsigned DEFAULT NULL COMMENT '매매가(만원)',
  `jeonse_price` int(10) unsigned DEFAULT NULL COMMENT '전세가(만원)',
  `deposit_price` int(10) unsigned DEFAULT NULL COMMENT '보증금(만원)',
  `monthly_rent` int(10) unsigned DEFAULT NULL COMMENT '월세(만원)',
  `loan_amount` int(10) unsigned DEFAULT NULL COMMENT '융자금(만원)',
  `maintenance_fee` int(10) unsigned DEFAULT NULL COMMENT '관리비(만원)',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '거래상태',
  `display_yn` tinyint(1) NOT NULL DEFAULT 1 COMMENT '홈페이지노출',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '삭제여부(0:정상,1:삭제)',
  `summary` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '한줄소개',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '상세설명',
  `main_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '대표이미지',
  `main_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '대표이미지 path',
  `view_count` int(10) unsigned DEFAULT 0 COMMENT '조회수',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`property_no`),
  KEY `idx_status` (`status`),
  KEY `idx_sale_price` (`sale_price`),
  KEY `idx_jeonse_price` (`jeonse_price`),
  KEY `idx_monthly_rent` (`monthly_rent`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_nopad_ci COMMENT='매물 정보';

DROP TABLE IF EXISTS `property_analytics_daily`;

CREATE TABLE `property_analytics_daily` (
  `stat_date` date NOT NULL COMMENT '집계 대상 날짜',
  `property_no` int(10) unsigned NOT NULL COMMENT 'property.property_no',
  `view_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '그날 조회수 (pageview 이벤트 수)',
  `avg_dwell_ms` int(10) unsigned DEFAULT NULL COMMENT '그날 평균 체류시간(ms), dwell 이벤트 기준',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '배치 적재시각',
  PRIMARY KEY (`stat_date`,`property_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 매물별 일별 집계 (새벽 배치 생성)';

DROP TABLE IF EXISTS `site_analytics_daily_stats`;

CREATE TABLE `site_analytics_daily_stats` (
  `stat_date` date NOT NULL COMMENT '집계 대상 날짜',
  `visitor_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '고유 방문자수 (그날 visit_id 수)',
  `pageview_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '페이지뷰수 (pageview 이벤트 수)',
  `new_visitor_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '신규 방문자수 (is_returning=N)',
  `returning_visitor_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '재방문자수 (is_returning=Y)',
  `naver_referral_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '네이버 유입 방문자수 (판별 규칙은 위 주석 참고)',
  `direct_referral_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '직접 유입 (referrer_url 없음)',
  `other_referral_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '기타 유입 (네이버/직접 외)',
  `pc_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'PC 방문자수 (user_agent 기준)',
  `mobile_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '모바일 방문자수 (user_agent 기준)',
  `phone_click_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '전화클릭 수 (click_action=tel)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '배치 적재시각',
  PRIMARY KEY (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 사이트 종합 일별 집계 (새벽 배치 생성)';

DROP TABLE IF EXISTS `site_analytics_event`;

CREATE TABLE `site_analytics_event` (
  `event_no` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '이벤트번호',
  `visit_id` varchar(64) NOT NULL COMMENT '소속 방문 (site_analytics_visit.visit_id)',
  `event_type` varchar(16) NOT NULL COMMENT '이벤트종류 (pageview/dwell/click)',
  `occurred_at` datetime NOT NULL COMMENT '발생시각 (클라이언트 기준)',
  `page_type` varchar(32) DEFAULT NULL COMMENT '요청 처리한 페이지종류 (home/list/detail/notice 등, 코드상 직접 결정되는 값)',
  `property_no` int(10) unsigned DEFAULT NULL COMMENT '매물 상세 조회시 property.property_no',
  `request_url` varchar(500) DEFAULT NULL COMMENT '요청 URL 원본 (쿼리스트링 포함)',
  `dwell_ms` int(10) unsigned DEFAULT NULL COMMENT '체류시간(ms), event_type=dwell 일 때만 사용',
  `click_action` varchar(32) DEFAULT NULL COMMENT '클릭행동 (tel/favorite/share 등), event_type=click 일 때만 사용',
  `search_filter` text DEFAULT NULL COMMENT '검색 필터 입력값 원본(JSON), 검색/목록 페이지에서만 사용',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '수집(적재)시각',
  PRIMARY KEY (`event_no`),
  KEY `idx_visit_id` (`visit_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_occurred_at` (`occurred_at`),
  KEY `idx_property_no` (`property_no`)
) ENGINE=InnoDB AUTO_INCREMENT=636 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 행동 이벤트 원본 (append-only)';

DROP TABLE IF EXISTS `site_analytics_visit`;

CREATE TABLE `site_analytics_visit` (
  `visit_id` varchar(64) NOT NULL COMMENT '클라이언트 발급 방문 식별키 (UUID)',
  `client_key` varchar(64) DEFAULT NULL COMMENT 'FE 자체 발급 임시키. visit_id 응답 전 페이지 이동 시 중복 신규등록 방지용',
  `occurred_at` datetime NOT NULL COMMENT '최초 진입시각',
  `ip` varchar(45) DEFAULT NULL COMMENT '접속 IP 원본 (IPv4/IPv6). region 변환은 별도 단계',
  `referrer_url` varchar(500) DEFAULT NULL COMMENT 'HTTP Referer 원본. 유입경로 분류는 별도 단계',
  `utm_source` varchar(100) DEFAULT NULL COMMENT 'UTM 파라미터 원본 - 유입채널 (예: naver, kakao)',
  `utm_medium` varchar(100) DEFAULT NULL COMMENT 'UTM 파라미터 원본 - 매체 (예: cpc, banner)',
  `utm_campaign` varchar(100) DEFAULT NULL COMMENT 'UTM 파라미터 원본 - 캠페인명',
  `user_agent` varchar(255) DEFAULT NULL COMMENT 'User-Agent 원본. 기기/브라우저 판별은 별도 단계',
  `viewport_w` smallint(5) unsigned DEFAULT NULL COMMENT '뷰포트 가로(px)',
  `viewport_h` smallint(5) unsigned DEFAULT NULL COMMENT '뷰포트 세로(px)',
  `is_returning` varchar(1) DEFAULT NULL COMMENT '재방문여부 Y/N (쿠키/localStorage 존재 여부 그대로)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '수집(적재)시각',
  PRIMARY KEY (`visit_id`),
  UNIQUE KEY `uk_client_key` (`client_key`),
  KEY `idx_occurred_at` (`occurred_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 방문(세션) 단위 원본 수집';

