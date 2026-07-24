-- ============================================================
-- 보물(BOMUL) 고객노트(방문일지) 테이블
-- bomul-api(CI4) 컨벤션에 맞게 조정:
--   - office_id 제거 (SHOP_CODE별로 DB 자체가 분리되어 불필요)
--   - id -> note_no, agent_id -> agent_no, interested_property_id -> property_no
--   - members/properties -> member/property (실제 테이블명)
--   - FK 제약조건 제거 (참조무결성은 PHP 애플리케이션 레벨에서 처리)
--   - ENUM -> VARCHAR (이 스키마의 다른 테이블과 동일하게 PHP 매핑 메서드로 라벨 처리)
--   - memo는 1:N이라 note 테이블에서 분리, img 테이블과 동일한 parent_table/parent_no 패턴으로 별도 테이블화
-- ============================================================

CREATE TABLE `note` (
  `note_no` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '노트번호',
  `agent_no` INT UNSIGNED NOT NULL COMMENT '접수자(담당 중개사, member.member_no)',
  `property_no` INT UNSIGNED DEFAULT NULL COMMENT '관심매물 (property.property_no)',

  `visitor_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '방문자명',
  `visitor_phone` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '방문자 연락처',

  `visit_type` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '방문형태 (tenant:세입자, landlord:임대인)',
  `transaction_type` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '거래방식 (sale:매매, jeonse:전세, monthly:월세)',

  `budget` BIGINT UNSIGNED DEFAULT NULL COMMENT '예산(만원단위)',
  `preferred_area` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '희망지역',

  `inflow_type` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visit' COMMENT '유입경로 (visit/phone/web/naver/referral/etc)',
  `status` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'consulting' COMMENT '진행상태 (consulting/proposed/contracted/lost)',

  `visit_date` DATETIME DEFAULT NULL COMMENT '방문예정일',
  `next_contact_date` DATE DEFAULT NULL COMMENT '다음연락예정일',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`note_no`),
  KEY `idx_agent` (`agent_no`),
  KEY `idx_property` (`property_no`),
  KEY `idx_status` (`status`),
  KEY `idx_visit_date` (`visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='고객노트(방문일지)';


-- ============================================================
-- 메모 (1:N, note 등 여러 대상에 재사용 가능하도록 img 테이블과 동일한 패턴)
-- ============================================================
CREATE TABLE `memo` (
  `memo_no` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '메모번호',
  `parent_table` VARCHAR(100) DEFAULT NULL COMMENT '연결대상 테이블명 (예: note)',
  `parent_no` INT UNSIGNED DEFAULT NULL COMMENT '연결대상 PK',
  `writer_no` INT UNSIGNED DEFAULT NULL COMMENT '작성자 (member.member_no)',
  `memo_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '메모 내용',
  `write_date` DATETIME DEFAULT NULL COMMENT '메모등록일',
  PRIMARY KEY (`memo_no`),
  KEY `idx_parent` (`parent_table`,`parent_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='메모 (1:N, 상담노트 등에서 사용)';
