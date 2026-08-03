/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 11.8.6-MariaDB-5ubuntu0.1 from Ubuntu : Database - foray
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`foray` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;

USE `foray`;

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

/*Data for the table `add_menu` */

insert  into `add_menu`(`add_menu_no`,`code`,`key`,`text`,`show`,`img_dir`,`img_name`,`write_date`) values 
(1,'map','tel','연락처1','Y','','',NULL),
(2,'map','addr','주소2','Y','','',NULL),
(3,'map','subway','지하철 정보','Y','','',NULL),
(4,'map','bus','버스 정보','Y','','',NULL),
(5,'map','google','maps.google.com/?','Y','','',NULL),
(7,'map','file2',NULL,NULL,'foray/menu/','d7b4c92d1685e2e8c1ab7ab02a7919fb.PNG',NULL),
(8,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `basic` */

DROP TABLE IF EXISTS `basic`;

CREATE TABLE `basic` (
  `basic_no` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) DEFAULT NULL,
  `logo_dir` varchar(255) DEFAULT NULL,
  `logo_name` varchar(255) DEFAULT NULL,
  `logo_show` varchar(10) DEFAULT NULL,
  `menu_back_color` varchar(10) DEFAULT NULL,
  `menu_back_transparent` varchar(10) DEFAULT NULL,
  `menu_bg_color` varchar(20) DEFAULT NULL,
  `menu_bg_transparent` varchar(10) DEFAULT NULL,
  `main_menu_color` varchar(20) DEFAULT NULL,
  `sub_menu_focus_color` varchar(20) DEFAULT NULL,
  `property_show` varchar(10) DEFAULT 'Y' COMMENT '매물보기',
  `notice_show` varchar(10) DEFAULT 'Y',
  `around_show` varchar(10) DEFAULT 'Y',
  `charge_show` varchar(10) DEFAULT 'Y',
  `review_show` varchar(10) DEFAULT 'Y',
  `vod_show` varchar(10) DEFAULT 'Y',
  `map_show` varchar(10) DEFAULT 'Y',
  `link_menu_show` varchar(10) DEFAULT NULL,
  `naver_reserve` varchar(255) DEFAULT NULL,
  `naver_reserve_show` varchar(10) DEFAULT NULL,
  `kakao_channel` varchar(255) DEFAULT NULL,
  `kakao_channel_show` varchar(10) DEFAULT NULL,
  `company_name` varchar(150) DEFAULT NULL,
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

/*Data for the table `basic` */

insert  into `basic`(`basic_no`,`site_name`,`logo_dir`,`logo_name`,`logo_show`,`menu_back_color`,`menu_back_transparent`,`menu_bg_color`,`menu_bg_transparent`,`main_menu_color`,`sub_menu_focus_color`,`property_show`,`notice_show`,`around_show`,`charge_show`,`review_show`,`vod_show`,`map_show`,`link_menu_show`,`naver_reserve`,`naver_reserve_show`,`kakao_channel`,`kakao_channel_show`,`company_name`,`broker_office_name`,`broker_address`,`broker_phone`,`broker_reg_no`,`broker_ceo_name`,`broker_agent_name`,`broker_agent_phone`,`footer_copy`,`naver_site_verification`) values 
(1,'25시 부동산','','로고','N','#123a5e','50','#F4F5F7',NULL,'#ffffff','#c0392b','Y','Y','N','Y','Y','Y','N','N','','N','','N','25시 부동산','25시 부동산 공인중개사사무소','서울 동작구 노량진로 100','02-815-2500','제11590-2024-00042호','나병연','김민수','02-815-2501','대표이사 : 나병연 | 개인정보관리책임자 : 나병연 | 사업자등록번호 : 204-81-12345 | 주소 서울 동작구 노량진로 100 | TEL 02-815-2500','test2');

/*Table structure for table `board` */

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

/*Data for the table `board` */

/*Table structure for table `build` */

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

/*Data for the table `build` */

/*Table structure for table `button_menu` */

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

/*Data for the table `button_menu` */

/*Table structure for table `function_menu` */

DROP TABLE IF EXISTS `function_menu`;

CREATE TABLE `function_menu` (
  `function_menu_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `function_menu_key` varchar(32) DEFAULT NULL,
  `function_menu_name` varchar(32) DEFAULT NULL,
  `function_menu_value` varchar(4) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`function_menu_no`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

/*Data for the table `function_menu` */

insert  into `function_menu`(`function_menu_no`,`function_menu_key`,`function_menu_name`,`function_menu_value`,`write_date`) values 
(1,'property_name','매물명','N','2026-07-08 04:56:44'),
(2,'building_name','건물명','N','2026-07-08 04:56:44'),
(3,'addr_sido','시도','N','2026-07-08 04:56:44'),
(4,'addr_sigungu','시군구','N','2026-07-08 04:56:44'),
(5,'addr_dong','읍면동','N','2026-07-08 04:56:44'),
(6,'addr_road','도로명','N','2026-07-08 04:56:44'),
(7,'supply_area','공급면적','N','2026-07-08 04:56:44'),
(8,'exclusive_area','전용면적','N','2026-07-08 04:56:44'),
(9,'total_floors','총층수','N','2026-07-08 04:56:44'),
(10,'current_floor','해당층','N','2026-07-08 04:56:44'),
(11,'room_count','방개수','N','2026-07-08 04:56:44'),
(12,'bathroom_count','화장실수','N','2026-07-08 04:56:44'),
(13,'parking_available','주차가능여부','N','2026-07-08 04:56:44'),
(14,'parking_count','총주차가능대수','N','2026-07-08 04:56:44'),
(15,'completion_date','준공일','N','2026-07-08 04:56:44'),
(16,'move_in_date','입주가능일','N','2026-07-08 04:56:44'),
(17,'direction','방향','N','2026-07-08 04:56:44'),
(18,'heating_fuel','난방연료','N','2026-07-08 04:56:44'),
(19,'heating_type','난방방식','N','2026-07-08 04:56:44'),
(20,'sale_price','매매가','N','2026-07-08 04:56:44'),
(21,'jeonse_price','전세가','N','2026-07-08 04:56:44'),
(22,'deposit_price','보증금','N','2026-07-08 04:56:44'),
(23,'monthly_rent','월세','N','2026-07-08 04:56:44'),
(24,'loan_amount','융자금','N','2026-07-08 04:56:44'),
(25,'maintenance_fee','관리비','N','2026-07-08 04:56:44'),
(26,'status','거래상태','Y','2026-07-08 04:56:44'),
(27,'display_yn','홈페이지노출','Y','2026-07-08 04:56:44'),
(28,'summary','한줄소개','Y','2026-07-08 04:56:44'),
(29,'description','상세설명','N','2026-07-08 04:56:44'),
(30,'thumbnail_image','대표이미지','N','2026-07-08 04:56:44'),
(31,'detail_images','추가이미지','N','2026-07-08 04:56:44');

/*Table structure for table `img` */

DROP TABLE IF EXISTS `img`;

CREATE TABLE `img` (
  `img_no` int(11) NOT NULL AUTO_INCREMENT,
  `parent_table` varchar(100) DEFAULT NULL,
  `parent_no` int(11) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `img_dir` varchar(255) DEFAULT NULL,
  `write_date` datetime DEFAULT NULL,
  PRIMARY KEY (`img_no`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

/*Data for the table `img` */

insert  into `img`(`img_no`,`parent_table`,`parent_no`,`img_name`,`img_dir`,`write_date`) values 
(3,'property',17,'9ad37ee9301db5ac9fd56817332256c7.png','foray/property/17/','2026-07-23 07:09:47'),
(4,'property',17,'dc1cef4c8acd604b4ad800b88269dff7.png','foray/property/17/','2026-07-23 07:09:47'),
(5,'property',17,'fc5c09f9b4ea7356a0623e88f2ef86c3.png','foray/property/17/','2026-07-23 07:09:47'),
(6,'property',19,'d81121ac4d4f5115237d9f14e944e9aa.png','foray/property/19/','2026-07-27 13:23:13'),
(7,'property',19,'103bd7944070f8b01cea76f66dbc8e7b.png','foray/property/19/','2026-07-27 13:23:13'),
(8,'property',19,'63051b66c1824fac2c3d5b8d19fc94f9.png','foray/property/19/','2026-07-27 13:23:13'),
(9,'property',19,'d12d9c81821ff1762561607a43b2539a.png','foray/property/19/','2026-07-27 13:23:13'),
(10,'property',19,'d5bd3f22748d79f49b10c34bbc169431.png','foray/property/19/','2026-07-27 13:23:13'),
(11,'property',19,'9e352f52484d090dba6993564141ee82.png','foray/property/19/','2026-07-27 13:23:13'),
(12,'property',19,'89bddaaf01f85f38e55c946355175879.png','foray/property/19/','2026-07-27 13:23:13'),
(13,'property',19,'ad764b684ce0e502dbcd5b3d9d9e64ef.png','foray/property/19/','2026-07-27 13:23:13'),
(14,'property',19,'87cb56fd5eb512b10cbb74c5cd697eef.png','foray/property/19/','2026-07-27 13:23:13'),
(15,'property',19,'c35c90a57a7ee3968c1530b85d14d159.png','foray/property/19/','2026-07-27 13:23:13');

/*Table structure for table `login_session` */

DROP TABLE IF EXISTS `login_session`;

CREATE TABLE `login_session` (
  `login_session_no` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `fid` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`login_session_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

/*Data for the table `login_session` */

/*Table structure for table `memo` */

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='메모 (1:N, 상담노트 등에서 사용)';

/*Data for the table `memo` */

insert  into `memo`(`memo_no`,`parent_table`,`parent_no`,`writer_no`,`writer_name`,`memo_text`,`write_date`,`is_deleted`) values 
(1,'note',1,NULL,NULL,'foray','2026-07-24 08:25:12',0),
(2,'note',1,NULL,'','메모 수정 버튼 테스트','2026-07-29 13:18:13',0),
(3,'note',1,NULL,'','3','2026-07-29 13:22:12',0),
(4,'note',1,NULL,'','4','2026-07-29 13:22:21',0),
(5,'note',1,NULL,'','5','2026-07-29 13:23:34',0),
(6,'note',1,NULL,'','6','2026-07-29 13:23:56',0),
(7,'note',1,NULL,'','노트관리>팝업 메모 등록 test\r\n-','2026-07-29 13:45:29',0),
(8,'note',4,NULL,NULL,'12','2026-07-29 16:25:40',0),
(9,'note',4,NULL,'','검색','2026-07-31 05:24:02',0),
(10,'note',4,NULL,'','갱신','2026-07-31 05:32:45',0),
(11,'note',4,NULL,'','검색 갱신','2026-07-31 05:32:57',0);

/*Table structure for table `menu` */

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

/*Data for the table `menu` */

insert  into `menu`(`menu_no`,`name`,`code`,`bgcolor`,`transparent`,`title`,`title_show`,`bgcolor_show`,`write_date`) values 
(1,'메인','main','#123A5E','46','<h5>서울·경기 실매물 정보, 25시 부동산에서 확인하세요</h5>','Y','N',NULL),
(2,'공지사항','notice','#123A5E','0',NULL,NULL,NULL,NULL),
(3,'둘러보기','around','#123A5E','50',NULL,'N',NULL,NULL),
(4,'이용요금','charge','#123A5E','60',NULL,'N',NULL,NULL),
(5,'후기','review','#123A5E','62',NULL,'N',NULL,NULL),
(6,'오시는길','map','#123A5E','36',NULL,NULL,NULL,NULL),
(7,'홍보영상','vod','#123A5E','0',NULL,'N','N',NULL),
(8,'매물관리','property','#C0392B','94',NULL,'N','Y',NULL);

/*Table structure for table `note` */

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
  `status` varchar(16) NOT NULL DEFAULT 'consulting' COMMENT '진행상태 (consulting/proposed/contracted/lost)',
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='고객노트(방문일지)';

/*Data for the table `note` */

insert  into `note`(`note_no`,`agent_no`,`property_no`,`visitor_name`,`visitor_phone`,`visit_type`,`transaction_type`,`budget`,`preferred_area`,`inflow_type`,`status`,`is_deleted`,`visit_date`,`next_contact_date`,`created_at`,`updated_at`) values 
(1,0,0,'foray','010-1234-5678','tenant','sale',3000,'서울 동작구','visit','consulting',1,'2026-07-24 00:00:00','2026-07-27','2026-07-24 08:13:18','2026-07-31 07:25:25'),
(2,1,0,'예린','010-1234-5678','landlord','jeonse',3000,'서울 동작구','visit','consulting',0,'2026-07-24 00:00:00','2026-07-30','2026-07-24 08:14:35','2026-07-29 15:45:01'),
(3,0,0,'노트등록','010-3949-3434','tenant','sale',100000,'서울 동작구','visit','consulting',0,'2026-07-30 12:44:00','2026-07-10','2026-07-29 15:44:18','2026-07-29 15:44:18'),
(4,0,0,'옵션','010-1234-5678','tenant','sale',23,'서울 동작구','visit','consulting',0,'2026-07-30 00:51:00','2026-07-30','2026-07-29 15:50:18','2026-07-29 15:50:18'),
(5,0,0,'예린','010-1234-5678','tenant','sale',25,'서울 동작구','visit','proposed',0,'2026-07-31 14:57:00','2026-07-31','2026-07-31 05:56:22','2026-07-31 05:56:31'),
(6,0,0,'예린','010-1234-5678','tenant','sale',4,'서울 동작구','visit','lost',0,'2026-07-31 14:57:00','2026-07-31','2026-07-31 05:56:53','2026-07-31 05:56:53'),
(7,0,0,'예린','010-1234-5678','tenant','sale',6,'서울 동작구','visit','consulting',0,'2026-07-31 14:59:00','2026-07-10','2026-07-31 05:57:11','2026-07-31 05:57:11'),
(8,0,0,'예린','010-1234-5678','tenant','sale',66,'서울 동작구','visit','consulting',0,'2026-07-31 02:57:00','2026-07-02','2026-07-31 05:57:26','2026-07-31 05:57:26'),
(9,0,0,'예린','010-3949-3434','tenant','sale',5,'서울 동작구','visit','consulting',0,'2026-07-31 14:58:00','2026-07-31','2026-07-31 05:57:45','2026-07-31 05:57:45');

/*Table structure for table `pager_detail` */

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

/*Data for the table `pager_detail` */

/*Table structure for table `pager_list` */

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

/*Data for the table `pager_list` */

/*Table structure for table `post` */

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

/*Data for the table `post` */

insert  into `post`(`post_no`,`bcode`,`user_id`,`name`,`password`,`secret`,`tel`,`status`,`subject`,`content`,`file_name`,`orgin_name`,`file_dir`,`write_date`,`modify_date`,`view_count`,`add01`,`add02`) values 
(1,'notice',NULL,'foray',NULL,'F',NULL,'1','test1','&lt;p&gt;test1&lt;/p&gt;','','','','2026-06-30 06:10:32',NULL,0,'',''),
(2,'notice',NULL,'foray',NULL,'F',NULL,'1','test2','&lt;p&gt;test2&lt;/p&gt;','','','','2026-06-30 06:10:57',NULL,0,'',''),
(3,'notice',NULL,'foray',NULL,'F',NULL,'1','test3','<p>test3</p>','','','','2026-06-30 06:14:43',NULL,0,'',''),
(4,'notice',NULL,'foray',NULL,'F',NULL,'1','돈많이 벌꺼야','&lt;p&gt;월 2000만원만&lt;/p&gt;','','','','2026-06-30 06:20:34',NULL,3,'',''),
(5,'review',NULL,'foray',NULL,'F',NULL,'1','이용후기1','&lt;p&gt;이용후기1&lt;/p&gt;','','','','2026-07-07 09:11:34',NULL,0,'5',''),
(6,'review',NULL,'foray',NULL,'F',NULL,'1','이용후기2','&lt;p&gt;이용후기2&lt;/p&gt;','','','','2026-07-07 09:12:14',NULL,6,'',''),
(7,'event',NULL,'foray',NULL,'F',NULL,'1','이벤트','&lt;p&gt;이벤트1&lt;/p&gt;','','','','2026-07-16 06:25:57',NULL,0,'',''),
(8,'notice',NULL,'테스트 운영자',NULL,'F',NULL,'1','테스트','&lt;p&gt;5&lt;/p&gt;','','','','2026-07-17 13:24:07','2026-07-17 13:24:54',0,'',''),
(9,'notice',NULL,'foray',NULL,'F',NULL,'0','테스트2','&lt;p&gt;테스트2&lt;/p&gt;','','','','2026-07-20 06:11:23',NULL,0,'','');

/*Table structure for table `profile` */

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `profile_no` int(11) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(255) DEFAULT NULL,
  `img_dir` varchar(255) DEFAULT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`profile_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

/*Data for the table `profile` */

insert  into `profile`(`profile_no`,`nick_name`,`img_dir`,`img_name`) values 
(1,'25시부동산','','');

/*Table structure for table `property` */

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_nopad_ci COMMENT='매물 정보';

/*Data for the table `property` */

insert  into `property`(`property_no`,`property_type`,`transaction_type`,`property_name`,`building_name`,`addr_sido`,`addr_sigungu`,`addr_dong`,`addr_road`,`latitude`,`longitude`,`supply_area`,`exclusive_area`,`total_floor`,`current_floor`,`room_count`,`bathroom_count`,`parking_available`,`parking_count`,`completion_date`,`move_in_date`,`direction`,`heating_fuel`,`heating_type`,`sale_price`,`jeonse_price`,`deposit_price`,`monthly_rent`,`loan_amount`,`maintenance_fee`,`status`,`display_yn`,`is_deleted`,`summary`,`description`,`main_image`,`main_path`,`view_count`,`created_at`,`updated_at`) values 
(21,'officetel','monthly','한강뷰 리버파크 오피스텔','여의도 리버파크타워','서울특별시','영등포구','여의도동','국제금융로8길 27-8','37.5219','126.9245',59,45,28,15,2,1,'Y',1,'2019-05-01 00:00:00','2026-08-15 00:00:00','SE',1,1,NULL,NULL,3000,95,0,13,1,1,0,'한강 조망 가능, 여의나루역 도보 5분, 즉시입주 가능','2019년 준공된 여의도 리버파크타워입니다. 전 세대 한강뷰 설계로 채광이 우수하며, 지하철 5호선 여의나루역과 9호선 샛강역이 도보 5분 거리에 있습니다. 풀옵션(에어컨, 냉장고, 세탁기, 인덕션) 완비, 지하주차장 및 24시간 보안 상주.',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 08:42:53'),
(22,'apt','sale','래미안 반포리버뷰 84타입','래미안퍼스티지','서울특별시','서초구','반포동','반포대로 235','37.5054','127.0125',112,84,32,18,3,2,'Y',2,'2015-03-01 00:00:00',NULL,'S',1,1,185000,NULL,NULL,NULL,0,35,1,1,0,'한강조망 로열층, 반포초 학군, 지하철 3분','반포동 래미안퍼스티지 84타입 로열층 매물입니다. 거실과 안방에서 한강 조망이 가능하며, 반포초/반포중 학군이 도보권입니다. 고속터미널역 및 반포역 도보 이용 가능, 커뮤니티시설(피트니스, 골프연습장) 완비.',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 08:42:53'),
(23,'apt','jeonse','잠원동 한신4차 24평','한신4차아파트','서울특별시','서초구','잠원동','잠원로 12','37.5145','127.0100',79,59,15,7,3,2,'Y',1,'1998-11-01 00:00:00',NULL,'N',2,2,NULL,55000,NULL,NULL,0,15,1,1,0,'신반포역 도보 3분, 로열동 로열층','신반포역과 가까운 한신4차아파트 전세 매물입니다. 남향 로열동으로 채광이 우수하고, 단지 내 초등학교가 있어 통학이 편리합니다.',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 08:42:53'),
(24,'villa','monthly','방배동 신축빌라 투룸','그린빌라','서울특별시','서초구','방배동','방배로 45','37.4816','126.9974',52,42,4,3,2,1,'Y',1,'2022-02-01 00:00:00','2026-08-01 00:00:00','SW',1,1,NULL,NULL,1000,65,0,8,1,1,0,'신축급, 주차가능, 반려동물 가능','2022년 준공 신축빌라 투룸입니다. 방배역 도보 10분, 세대별 주차 1대 가능하며 반려동물 동반 입주 가능합니다.',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 08:42:53'),
(25,'officetel','jeonse','신사동 가로수길 오피스텔','가로수타워','서울특별시','강남구','신사동','도산대로15길 8','37.5163','127.0201',46,33,20,11,1,1,'Y',1,'2020-09-01 00:00:00',NULL,'E',1,1,NULL,28000,NULL,NULL,0,11,1,1,0,'가로수길 도보 2분, 풀옵션','가로수길 상권 도보 2분 거리의 신축 오피스텔입니다. 풀옵션(빌트인 가전) 완비, 신사역 도보 8분.',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 08:42:53'),
(26,'house','sale','성북동 전망 좋은 단독주택',NULL,'서울특별시','성북구','성북동','성북로20길 5','37.5894','126.9997',165,132,2,2,4,3,'Y',2,'2005-06-01 00:00:00',NULL,'S',3,1,98000,NULL,NULL,NULL,0,0,1,1,0,'넓은 마당, 주차 2대 가능','성북동 조용한 주택가에 위치한 단독주택입니다. 넓은 마당과 주차 2대 가능한 공간이 있으며, 리모델링 이력이 있습니다.',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 08:42:53'),
(27,'commercial','monthly','노량진 상가 1층 점포',NULL,'서울특별시','동작구','노량진동','장승배기로 9','37.5130','126.9420',33,26,5,1,0,1,'Y',0,'2010-01-01 00:00:00','2026-08-05 00:00:00','N',2,2,0,0,2000,120,0,20,5,1,0,'노량진역 도보 1분, 유동인구 많음','<p>노량진역 인근 유동인구가 많은 1층 상가입니다. 학원가 밀집지역으로 업종 제한 없이 임대 가능합니다.</p>',NULL,NULL,0,'2026-07-30 08:42:53','2026-07-30 12:37:01'),
(28,'officetel','jeonse','목동 신시가지 5단지 24평','목동신시가지5단지','서울특별시','양천구','목동','목동로 189','37.5264','126.8748',79,59,15,9,3,1,'Y',1,'1988-04-01 00:00:00','2026-07-30 00:00:00','S',3,3,145000,0,0,0,0,25,4,1,0,'목동 학원가 인접, 재건축 이슈','<p>목동 신시가지 5단지, 목동 학원가 도보 5분 거리입니다. 재건축 추진 이슈가 있는 단지로 투자 문의도 많습니다.</p>',NULL,NULL,21,'2026-07-30 08:42:53','2026-07-31 08:15:52'),
(29,'apt','sale','노출여부 테스트',NULL,'서울','송파구','장지동','서울 송파구 위례광장로 120','37.4748656','127.1415892',0,0,0,0,0,0,'Y',0,'2026-07-31 00:00:00','2026-07-31 00:00:00','S',1,1,100,0,0,0,0,0,1,2,0,NULL,NULL,NULL,NULL,0,'2026-07-31 02:29:34','2026-07-31 08:44:42');

/*Table structure for table `property_analytics_daily` */

DROP TABLE IF EXISTS `property_analytics_daily`;

CREATE TABLE `property_analytics_daily` (
  `stat_date` date NOT NULL COMMENT '집계 대상 날짜',
  `property_no` int(10) unsigned NOT NULL COMMENT 'property.property_no',
  `view_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '그날 조회수 (pageview 이벤트 수)',
  `avg_dwell_ms` int(10) unsigned DEFAULT NULL COMMENT '그날 평균 체류시간(ms), dwell 이벤트 기준',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '배치 적재시각',
  PRIMARY KEY (`stat_date`,`property_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 매물별 일별 집계 (새벽 배치 생성)';

/*Data for the table `property_analytics_daily` */

insert  into `property_analytics_daily`(`stat_date`,`property_no`,`view_count`,`avg_dwell_ms`,`created_at`) values 
('2026-07-29',20,1,2821,'2026-07-30 01:36:17');

/*Table structure for table `site_analytics_daily_stats` */

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

/*Data for the table `site_analytics_daily_stats` */

insert  into `site_analytics_daily_stats`(`stat_date`,`visitor_count`,`pageview_count`,`new_visitor_count`,`returning_visitor_count`,`naver_referral_count`,`direct_referral_count`,`other_referral_count`,`pc_count`,`mobile_count`,`phone_click_count`,`created_at`) values 
('2026-07-29',1,1,1,0,0,0,1,1,0,0,'2026-07-30 01:36:17');

/*Table structure for table `site_analytics_event` */

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
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 행동 이벤트 원본 (append-only)';

/*Data for the table `site_analytics_event` */

insert  into `site_analytics_event`(`event_no`,`visit_id`,`event_type`,`occurred_at`,`page_type`,`property_no`,`request_url`,`dwell_ms`,`click_action`,`search_filter`,`created_at`) values 
(1,'10ffd2e6700487fca539e58f459821d9','pageview','2026-07-29 13:31:51','property',20,'/property/view/20/%EC%8B%A0%EC%82%AC%EB%8F%99%20%EC%9B%94%EC%84%B8',0,NULL,NULL,'2026-07-29 04:31:53'),
(2,'10ffd2e6700487fca539e58f459821d9','dwell','2026-07-29 13:31:54','property',20,'/property/view/20/%EC%8B%A0%EC%82%AC%EB%8F%99%20%EC%9B%94%EC%84%B8',2821,NULL,NULL,'2026-07-29 04:31:56'),
(3,'eb4fe97c8f851cdb4d28d70a3fbb5fbf','pageview','2026-07-30 10:29:05','main',0,'/',0,NULL,NULL,'2026-07-30 01:29:07'),
(4,'eb4fe97c8f851cdb4d28d70a3fbb5fbf','dwell','2026-07-30 10:29:06','main',0,'/',1076,NULL,NULL,'2026-07-30 01:29:08'),
(5,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 14:29:07','main',0,'/',0,NULL,NULL,'2026-07-30 05:29:08'),
(6,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 14:29:09','main',0,'/',2060,NULL,NULL,'2026-07-30 05:29:11'),
(7,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 14:29:09','property',0,'/property',0,NULL,NULL,'2026-07-30 05:29:11'),
(8,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 14:29:10','property',0,'/property',831,NULL,NULL,'2026-07-30 05:29:11'),
(9,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 14:30:36','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 05:30:37'),
(10,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 14:30:47','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',11492,NULL,NULL,'2026-07-30 05:30:49'),
(11,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 14:30:47','property',0,'/property',0,NULL,NULL,'2026-07-30 05:30:49'),
(12,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 14:30:51','property',0,'/property',3610,NULL,NULL,'2026-07-30 05:30:53'),
(13,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 14:30:51','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 05:30:53'),
(14,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 14:31:01','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',9817,NULL,NULL,'2026-07-30 05:31:02'),
(15,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:04:49','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:04:51'),
(16,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:04:54','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',5102,NULL,NULL,'2026-07-30 06:04:56'),
(17,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:08:15','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:08:17'),
(18,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:08:25','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',10058,NULL,NULL,'2026-07-30 06:08:27'),
(19,'bf8e20fbf1e18f76c5e5c57ec2f3919d','pageview','2026-07-30 15:09:28','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:09:30'),
(20,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:18:35','property',0,'/property',0,NULL,NULL,'2026-07-30 06:18:37'),
(21,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:18:40','property',0,'/property',4569,NULL,NULL,'2026-07-30 06:18:42'),
(22,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:18:40','property',0,'/property?page=2',0,NULL,NULL,'2026-07-30 06:18:42'),
(23,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:18:54','property',0,'/property?page=2',13887,NULL,NULL,'2026-07-30 06:18:55'),
(24,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:18:54','property',0,'/property?page=1',0,NULL,NULL,'2026-07-30 06:18:55'),
(25,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:19:00','property',0,'/property?page=1',6088,NULL,NULL,'2026-07-30 06:19:02'),
(26,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:19:00','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:19:02'),
(27,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:19:07','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',6964,NULL,NULL,'2026-07-30 06:19:09'),
(28,'bf8e20fbf1e18f76c5e5c57ec2f3919d','dwell','2026-07-30 15:20:09','property',13,'/property/view/13/%EC%97%AC%EC%9D%98%EB%8F%84%EB%8F%99%20%2045-3%20%EB%A7%A4%EB%A7%A4',640137,NULL,NULL,'2026-07-30 06:20:10'),
(29,'bf8e20fbf1e18f76c5e5c57ec2f3919d','pageview','2026-07-30 15:20:09','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:20:10'),
(30,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:27:34','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:27:36'),
(31,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:27:40','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',6081,NULL,NULL,'2026-07-30 06:27:42'),
(32,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:29:29','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:29:31'),
(33,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:30:02','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',32733,NULL,NULL,'2026-07-30 06:30:04'),
(34,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:30:02','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',0,NULL,NULL,'2026-07-30 06:30:04'),
(35,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:30:32','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4',30233,NULL,NULL,'2026-07-30 06:30:34'),
(36,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:30:32','property',0,'/property?page=1',0,NULL,NULL,'2026-07-30 06:30:34'),
(37,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:30:33','property',0,'/property?page=1',734,NULL,NULL,'2026-07-30 06:30:35'),
(38,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:37:03','property',0,'/property?page=1',0,NULL,NULL,'2026-07-30 06:37:05'),
(39,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:37:04','property',0,'/property?page=1',1182,NULL,NULL,'2026-07-30 06:37:06'),
(40,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:37:04','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4%203%EC%96%B5%EC%9B%90',0,NULL,NULL,'2026-07-30 06:37:06'),
(41,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:37:11','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4%203%EC%96%B5%EC%9B%90',6452,NULL,NULL,'2026-07-30 06:37:12'),
(42,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:47:06','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4%203%EC%96%B5%EC%9B%90',0,NULL,NULL,'2026-07-30 06:47:07'),
(43,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:47:07','property',19,'/property/view/19/%EB%B0%98%ED%8F%AC%EB%A6%AC%20%EB%A7%A4%EB%A7%A4%203%EC%96%B5%EC%9B%90',1278,NULL,NULL,'2026-07-30 06:47:09'),
(44,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:47:07','main',0,'/',0,NULL,NULL,'2026-07-30 06:47:09'),
(45,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:47:08','main',0,'/',918,NULL,NULL,'2026-07-30 06:47:10'),
(46,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:47:08','main',0,'/',0,NULL,NULL,'2026-07-30 06:47:10'),
(47,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:47:10','main',0,'/',2293,NULL,NULL,'2026-07-30 06:47:12'),
(48,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:54:20','main',0,'/',0,NULL,NULL,'2026-07-30 06:54:21'),
(49,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 15:57:41','main',0,'/',200962,NULL,NULL,'2026-07-30 06:57:41'),
(50,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 15:59:23','main',0,'/',0,NULL,NULL,'2026-07-30 06:59:23'),
(51,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:00:27','main',0,'/',63743,NULL,NULL,'2026-07-30 07:00:27'),
(52,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:28','main',0,'/',0,NULL,NULL,'2026-07-30 07:44:28'),
(53,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:29','main',0,'/',901,NULL,NULL,'2026-07-30 07:44:29'),
(54,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:37','property',0,'/property',0,NULL,NULL,'2026-07-30 07:44:37'),
(55,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:42','property',0,'/property',5282,NULL,NULL,'2026-07-30 07:44:42'),
(56,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:42','notice',0,'/notice',0,NULL,NULL,'2026-07-30 07:44:42'),
(57,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:46','notice',0,'/notice',3665,NULL,NULL,'2026-07-30 07:44:46'),
(58,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:46','property',0,'/property',0,NULL,NULL,'2026-07-30 07:44:46'),
(59,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:47','property',0,'/property',1425,NULL,NULL,'2026-07-30 07:44:47'),
(60,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:47','property',20,'/property/view/20/%EB%8F%84%EA%B3%A1%EB%8F%99%20%EC%9B%94%EC%84%B8%2020%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-30 07:44:47'),
(61,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:53','property',20,'/property/view/20/%EB%8F%84%EA%B3%A1%EB%8F%99%20%EC%9B%94%EC%84%B8%2020%EB%A7%8C%EC%9B%90',5234,NULL,NULL,'2026-07-30 07:44:53'),
(62,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:53','main',0,'/',0,NULL,NULL,'2026-07-30 07:44:53'),
(63,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:56','main',0,'/',3285,NULL,NULL,'2026-07-30 07:44:56'),
(64,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:56','notice',0,'/notice',0,NULL,NULL,'2026-07-30 07:44:56'),
(65,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:44:59','notice',0,'/notice',2799,NULL,NULL,'2026-07-30 07:44:59'),
(66,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 16:44:59','property',0,'/property',0,NULL,NULL,'2026-07-30 07:44:59'),
(67,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 16:45:13','property',0,'/property',14429,NULL,NULL,'2026-07-30 07:45:13'),
(68,'c5f5e45b36a83a6353fd472ed0a10f01','pageview','2026-07-30 16:48:55','main',0,'/',0,NULL,NULL,'2026-07-30 07:48:55'),
(69,'c5f5e45b36a83a6353fd472ed0a10f01','dwell','2026-07-30 16:49:05','main',0,'/',9488,NULL,NULL,'2026-07-30 07:49:05'),
(70,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:16:00','main',0,'/',0,NULL,NULL,'2026-07-30 08:16:00'),
(71,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:16:02','main',0,'/',2398,NULL,NULL,'2026-07-30 08:16:02'),
(72,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:19:41','main',0,'/',0,NULL,NULL,'2026-07-30 08:19:41'),
(73,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:19:42','main',0,'/',1025,NULL,NULL,'2026-07-30 08:19:42'),
(74,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:19:42','main',0,'/',0,NULL,NULL,'2026-07-30 08:19:42'),
(75,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:19:43','main',0,'/',373,NULL,NULL,'2026-07-30 08:19:43'),
(76,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:19:43','main',0,'/',0,NULL,NULL,'2026-07-30 08:19:43'),
(77,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:19:59','main',0,'/',15925,NULL,NULL,'2026-07-30 08:19:59'),
(78,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:19:59','main',0,'/',0,NULL,NULL,'2026-07-30 08:19:59'),
(79,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:20:01','main',0,'/',2446,NULL,NULL,'2026-07-30 08:20:01'),
(80,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:20:01','main',0,'/',0,NULL,NULL,'2026-07-30 08:20:01'),
(81,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:20:21','main',0,'/',19484,NULL,NULL,'2026-07-30 08:20:21'),
(82,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:20:21','main',0,'/',0,NULL,NULL,'2026-07-30 08:20:21'),
(83,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:20:21','main',0,'/',449,NULL,NULL,'2026-07-30 08:20:21'),
(84,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:20:21','main',0,'/',0,NULL,NULL,'2026-07-30 08:20:21'),
(85,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:20:22','main',0,'/',348,NULL,NULL,'2026-07-30 08:20:22'),
(86,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:20:22','main',0,'/',0,NULL,NULL,'2026-07-30 08:20:22'),
(87,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:16','main',0,'/',54287,NULL,NULL,'2026-07-30 08:21:16'),
(88,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:16','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:16'),
(89,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:16','main',0,'/',484,NULL,NULL,'2026-07-30 08:21:16'),
(90,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:16','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:16'),
(91,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:17','main',0,'/',311,NULL,NULL,'2026-07-30 08:21:17'),
(92,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:17','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:17'),
(93,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:17','main',0,'/',242,NULL,NULL,'2026-07-30 08:21:17'),
(94,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:17','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:17'),
(95,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:17','main',0,'/',349,NULL,NULL,'2026-07-30 08:21:17'),
(96,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:17','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:17'),
(97,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:34','main',0,'/',16674,NULL,NULL,'2026-07-30 08:21:34'),
(98,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:34','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:34'),
(99,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:35','main',0,'/',762,NULL,NULL,'2026-07-30 08:21:35'),
(100,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:35','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:35'),
(101,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:40','main',0,'/',4793,NULL,NULL,'2026-07-30 08:21:40'),
(102,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:21:40','main',0,'/',0,NULL,NULL,'2026-07-30 08:21:40'),
(103,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:21:45','main',0,'/',5641,NULL,NULL,'2026-07-30 08:21:45'),
(104,'c9cf43b34ee8279c8d63f706e0a11584','pageview','2026-07-30 17:27:41','main',0,'/',0,NULL,NULL,'2026-07-30 08:27:41'),
(105,'c9cf43b34ee8279c8d63f706e0a11584','dwell','2026-07-30 17:28:05','main',0,'/',23887,NULL,NULL,'2026-07-30 08:28:05'),
(106,'c9cf43b34ee8279c8d63f706e0a11584','pageview','2026-07-30 17:28:05','property',0,'/property',0,NULL,NULL,'2026-07-30 08:28:05'),
(107,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:44:30','main',0,'/',0,NULL,NULL,'2026-07-30 08:44:30'),
(108,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:44:32','main',0,'/',1422,NULL,NULL,'2026-07-30 08:44:32'),
(109,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','pageview','2026-07-30 17:44:32','property',0,'/property',0,NULL,NULL,'2026-07-30 08:44:32'),
(110,'f4de97381ef1ad3ec2bc2b7b1d1d5a8f','dwell','2026-07-30 17:44:52','property',0,'/property',20215,NULL,NULL,'2026-07-30 08:44:52'),
(111,'219e7ff0e4c2f54a3a97e73488d82d8e','pageview','2026-07-31 12:26:29','main',0,'/',0,NULL,NULL,'2026-07-31 03:26:30'),
(112,'219e7ff0e4c2f54a3a97e73488d82d8e','dwell','2026-07-31 12:26:30','main',0,'/',1657,NULL,NULL,'2026-07-31 03:26:32'),
(113,'219e7ff0e4c2f54a3a97e73488d82d8e','pageview','2026-07-31 12:26:30','property',0,'/property',0,NULL,NULL,'2026-07-31 03:26:32'),
(114,'219e7ff0e4c2f54a3a97e73488d82d8e','dwell','2026-07-31 12:26:33','property',0,'/property',2589,NULL,NULL,'2026-07-31 03:26:35'),
(115,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:40:12','main',0,'/',0,NULL,NULL,'2026-07-31 05:40:14'),
(116,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:40:21','main',0,'/',8933,NULL,NULL,'2026-07-31 05:40:23'),
(117,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:40:21','notice',0,'/notice',0,NULL,NULL,'2026-07-31 05:40:23'),
(118,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:40:22','notice',0,'/notice',883,NULL,NULL,'2026-07-31 05:40:24'),
(119,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:40:22','charge',0,'/charge',0,NULL,NULL,'2026-07-31 05:40:24'),
(120,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:40:23','charge',0,'/charge',929,NULL,NULL,'2026-07-31 05:40:25'),
(121,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:40:23','review',0,'/review',0,NULL,NULL,'2026-07-31 05:40:25'),
(122,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:40:23','review',0,'/review',639,NULL,NULL,'2026-07-31 05:40:25'),
(123,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:40:23','property',0,'/property',0,NULL,NULL,'2026-07-31 05:40:25'),
(124,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:40:25','property',0,'/property',1433,NULL,NULL,'2026-07-31 05:40:27'),
(125,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:40:25','main',0,'/',0,NULL,NULL,'2026-07-31 05:40:27'),
(126,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:40:30','main',0,'/',4740,NULL,NULL,'2026-07-31 05:40:32'),
(127,'97483f0d1964070999beaa63b077c5ad','pageview','2026-07-31 14:43:12','main',0,'/',0,NULL,NULL,'2026-07-31 05:43:13'),
(128,'97483f0d1964070999beaa63b077c5ad','dwell','2026-07-31 14:44:31','main',0,'/',79647,NULL,NULL,'2026-07-31 05:44:33'),
(129,'97483f0d1964070999beaa63b077c5ad','pageview','2026-07-31 14:44:46','main',0,'/',0,NULL,NULL,'2026-07-31 05:44:48'),
(130,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:45:17','main',0,'/',0,NULL,NULL,'2026-07-31 05:45:19'),
(131,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:45:19','main',0,'/',1136,NULL,NULL,'2026-07-31 05:45:20'),
(132,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:45:19','main',0,'/',0,NULL,NULL,'2026-07-31 05:45:20'),
(133,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:45:19','main',0,'/',399,NULL,NULL,'2026-07-31 05:45:21'),
(134,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:45:19','main',0,'/',0,NULL,NULL,'2026-07-31 05:45:21'),
(135,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:45:21','main',0,'/',1801,NULL,NULL,'2026-07-31 05:45:23'),
(136,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:45:36','main',0,'/',0,NULL,NULL,'2026-07-31 05:45:38'),
(137,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:45:45','main',0,'/',8574,NULL,NULL,'2026-07-31 05:45:46'),
(138,'97483f0d1964070999beaa63b077c5ad','dwell','2026-07-31 14:46:23','main',0,'/',96852,NULL,NULL,'2026-07-31 05:46:25'),
(139,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:51:59','main',0,'/',0,NULL,NULL,'2026-07-31 05:52:00'),
(140,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:03','main',0,'/',4416,NULL,NULL,'2026-07-31 05:52:05'),
(141,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:03','notice',0,'/notice',0,NULL,NULL,'2026-07-31 05:52:05'),
(142,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:04','notice',0,'/notice',941,NULL,NULL,'2026-07-31 05:52:06'),
(143,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:04','property',0,'/property',0,NULL,NULL,'2026-07-31 05:52:06'),
(144,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:05','property',0,'/property',1038,NULL,NULL,'2026-07-31 05:52:07'),
(145,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:05','charge',0,'/charge',0,NULL,NULL,'2026-07-31 05:52:07'),
(146,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:06','charge',0,'/charge',1041,NULL,NULL,'2026-07-31 05:52:08'),
(147,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:06','review',0,'/review',0,NULL,NULL,'2026-07-31 05:52:08'),
(148,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:07','review',0,'/review',748,NULL,NULL,'2026-07-31 05:52:09'),
(149,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:07','charge',0,'/charge',0,NULL,NULL,'2026-07-31 05:52:09'),
(150,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:07','charge',0,'/charge',423,NULL,NULL,'2026-07-31 05:52:09'),
(151,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:07','notice',0,'/notice',0,NULL,NULL,'2026-07-31 05:52:09'),
(152,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:08','notice',0,'/notice',518,NULL,NULL,'2026-07-31 05:52:10'),
(153,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:08','property',0,'/property',0,NULL,NULL,'2026-07-31 05:52:10'),
(154,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:09','property',0,'/property',1424,NULL,NULL,'2026-07-31 05:52:11'),
(155,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:09','property',0,'/property',0,NULL,NULL,'2026-07-31 05:52:11'),
(156,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:12','property',0,'/property',2652,NULL,NULL,'2026-07-31 05:52:14'),
(157,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:56','property',0,'/property',0,NULL,NULL,'2026-07-31 05:52:58'),
(158,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:56','property',0,'/property',612,NULL,NULL,'2026-07-31 05:52:58'),
(159,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:56','property',0,'/property',0,NULL,NULL,'2026-07-31 05:52:58'),
(160,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:57','property',0,'/property',1006,NULL,NULL,'2026-07-31 05:52:59'),
(161,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:57','property',0,'/property',0,NULL,NULL,'2026-07-31 05:52:59'),
(162,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:58','property',0,'/property',459,NULL,NULL,'2026-07-31 05:53:00'),
(163,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:58','property',0,'/property',0,NULL,NULL,'2026-07-31 05:53:00'),
(164,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:58','property',0,'/property',212,NULL,NULL,'2026-07-31 05:53:00'),
(165,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:58','property',0,'/property',0,NULL,NULL,'2026-07-31 05:53:00'),
(166,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:58','property',0,'/property',212,NULL,NULL,'2026-07-31 05:53:00'),
(167,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:58','property',0,'/property',0,NULL,NULL,'2026-07-31 05:53:00'),
(168,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:58','property',0,'/property',179,NULL,NULL,'2026-07-31 05:53:00'),
(169,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:58','property',0,'/property',0,NULL,NULL,'2026-07-31 05:53:00'),
(170,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:59','property',0,'/property',184,NULL,NULL,'2026-07-31 05:53:00'),
(171,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:59','property',0,'/property',0,NULL,NULL,'2026-07-31 05:53:00'),
(172,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:52:59','property',0,'/property',188,NULL,NULL,'2026-07-31 05:53:01'),
(173,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:52:59','property',0,'/property',0,NULL,NULL,'2026-07-31 05:53:01'),
(174,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:53:02','property',0,'/property',2777,NULL,NULL,'2026-07-31 05:53:03'),
(175,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:17','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 05:56:19'),
(176,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:18','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',122,NULL,NULL,'2026-07-31 05:56:19'),
(177,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:18','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 05:56:19'),
(178,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:19','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',934,NULL,NULL,'2026-07-31 05:56:20'),
(179,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:19','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 05:56:20'),
(180,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:19','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',769,NULL,NULL,'2026-07-31 05:56:21'),
(181,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:19','main',0,'/',0,NULL,NULL,'2026-07-31 05:56:21'),
(182,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:21','main',0,'/',1378,NULL,NULL,'2026-07-31 05:56:23'),
(183,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:21','property',0,'/property',0,NULL,NULL,'2026-07-31 05:56:23'),
(184,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:22','property',0,'/property',1553,NULL,NULL,'2026-07-31 05:56:24'),
(185,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:22','notice',0,'/notice',0,NULL,NULL,'2026-07-31 05:56:24'),
(186,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:23','notice',0,'/notice',636,NULL,NULL,'2026-07-31 05:56:25'),
(187,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:23','charge',0,'/charge',0,NULL,NULL,'2026-07-31 05:56:25'),
(188,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:24','charge',0,'/charge',665,NULL,NULL,'2026-07-31 05:56:25'),
(189,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:24','review',0,'/review',0,NULL,NULL,'2026-07-31 05:56:26'),
(190,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:25','review',0,'/review',1483,NULL,NULL,'2026-07-31 05:56:27'),
(191,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:56:25','main',0,'/',0,NULL,NULL,'2026-07-31 05:56:27'),
(192,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:56:28','main',0,'/',2852,NULL,NULL,'2026-07-31 05:56:30'),
(193,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:58:39','main',0,'/',0,NULL,NULL,'2026-07-31 05:58:41'),
(194,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:58:47','main',0,'/',8488,NULL,NULL,'2026-07-31 05:58:49'),
(195,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 14:59:00','main',0,'/',0,NULL,NULL,'2026-07-31 05:59:02'),
(196,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 14:59:15','main',0,'/',14503,NULL,NULL,'2026-07-31 05:59:17'),
(197,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 15:02:26','main',0,'/',0,NULL,NULL,'2026-07-31 06:02:28'),
(198,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 15:02:31','main',0,'/',5326,NULL,NULL,'2026-07-31 06:02:33'),
(199,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:07:30','property',0,'/property',0,NULL,NULL,'2026-07-31 07:07:30'),
(200,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:07:31','property',0,'/property',890,NULL,NULL,'2026-07-31 07:07:31'),
(201,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:07:31','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 07:07:31'),
(202,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:07:58','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',26898,NULL,NULL,'2026-07-31 07:07:58'),
(203,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:55:44','charge',0,'/charge',0,NULL,NULL,'2026-07-31 07:55:45'),
(204,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:55:47','charge',0,'/charge',2176,NULL,NULL,'2026-07-31 07:55:47'),
(205,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:55:47','review',0,'/review',0,NULL,NULL,'2026-07-31 07:55:47'),
(206,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:55:48','review',0,'/review',1872,NULL,NULL,'2026-07-31 07:55:49'),
(207,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:57:00','property',0,'/property',0,NULL,NULL,'2026-07-31 07:57:00'),
(208,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 16:57:02','main',0,'/',0,NULL,NULL,'2026-07-31 07:57:01'),
(209,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:57:06','property',0,'/property',5773,NULL,NULL,'2026-07-31 07:57:06'),
(210,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 16:57:08','main',0,'/',6291,NULL,NULL,'2026-07-31 07:57:07'),
(211,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 16:57:11','property',0,'/property',0,NULL,NULL,'2026-07-31 07:57:10'),
(212,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 16:57:18','property',0,'/property',7793,NULL,NULL,'2026-07-31 07:57:18'),
(213,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 16:57:21','notice',0,'/notice',0,NULL,NULL,'2026-07-31 07:57:20'),
(214,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 16:57:23','notice',0,'/notice',2597,NULL,NULL,'2026-07-31 07:57:22'),
(215,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 16:57:26','charge',0,'/charge',0,NULL,NULL,'2026-07-31 07:57:25'),
(216,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 16:57:29','charge',0,'/charge',3118,NULL,NULL,'2026-07-31 07:57:28'),
(217,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:59:00','notice',0,'/notice',0,NULL,NULL,'2026-07-31 07:59:00'),
(218,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:59:04','notice',0,'/notice',3625,NULL,NULL,'2026-07-31 07:59:04'),
(219,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:59:04','main',0,'/',0,NULL,NULL,'2026-07-31 07:59:04'),
(220,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:59:08','main',0,'/',4113,NULL,NULL,'2026-07-31 07:59:08'),
(221,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 16:59:08','notice',0,'/notice',0,NULL,NULL,'2026-07-31 07:59:08'),
(222,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 16:59:10','notice',0,'/notice',1711,NULL,NULL,'2026-07-31 07:59:10'),
(223,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 17:00:19','notice',0,'/notice',0,NULL,NULL,'2026-07-31 08:00:18'),
(224,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 17:00:21','notice',0,'/notice',1903,NULL,NULL,'2026-07-31 08:00:20'),
(225,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 17:00:23','main',0,'/',0,NULL,NULL,'2026-07-31 08:00:22'),
(226,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 17:01:34','main',0,'/',70227,NULL,NULL,'2026-07-31 08:01:33'),
(227,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:02:37','property',0,'/property',0,NULL,NULL,'2026-07-31 08:02:37'),
(228,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:02:40','property',0,'/property',3093,NULL,NULL,'2026-07-31 08:02:41'),
(229,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:02:40','property',0,'/property',0,NULL,NULL,'2026-07-31 08:02:41'),
(230,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:02:46','property',0,'/property',5567,NULL,NULL,'2026-07-31 08:02:46'),
(231,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:04:08','property',0,'/property',0,NULL,NULL,'2026-07-31 08:04:08'),
(232,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:04:11','property',0,'/property',2436,NULL,NULL,'2026-07-31 08:04:11'),
(233,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:04:11','property',0,'/property',0,NULL,NULL,'2026-07-31 08:04:11'),
(234,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:04:12','property',0,'/property',1088,NULL,NULL,'2026-07-31 08:04:12'),
(235,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:04:12','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:04:12'),
(236,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:04:13','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',823,NULL,NULL,'2026-07-31 08:04:13'),
(237,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:04:13','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:04:13'),
(238,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:04:14','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',1407,NULL,NULL,'2026-07-31 08:04:14'),
(239,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:04:14','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:04:14'),
(240,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:04:16','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',1771,NULL,NULL,'2026-07-31 08:04:16'),
(241,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:04:16','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:04:16'),
(242,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:04:33','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',16742,NULL,NULL,'2026-07-31 08:04:33'),
(243,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:05:24','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:05:24'),
(244,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:05:33','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',9356,NULL,NULL,'2026-07-31 08:05:33'),
(245,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:06:11','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:06:11'),
(246,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:06:25','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',14163,NULL,NULL,'2026-07-31 08:06:25'),
(247,'3245ceac52d8271afe9d00123731d2b2','pageview','2026-07-31 17:07:16','property',0,'/property',0,NULL,NULL,'2026-07-31 08:07:16'),
(248,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:08:42','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:08:42'),
(249,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:08:42','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',515,NULL,NULL,'2026-07-31 08:08:42'),
(250,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:08:42','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:08:42'),
(251,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:08:42','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',253,NULL,NULL,'2026-07-31 08:08:42'),
(252,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:08:42','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:08:42'),
(253,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:08:43','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',242,NULL,NULL,'2026-07-31 08:08:43'),
(254,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:08:43','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:08:43'),
(255,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:08:43','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',171,NULL,NULL,'2026-07-31 08:08:43'),
(256,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:08:43','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:08:43'),
(257,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:08:44','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',925,NULL,NULL,'2026-07-31 08:08:44'),
(258,'3245ceac52d8271afe9d00123731d2b2','dwell','2026-07-31 17:09:52','property',0,'/property',155921,NULL,NULL,'2026-07-31 08:09:52'),
(259,'3245ceac52d8271afe9d00123731d2b2','pageview','2026-07-31 17:09:52','property',0,'/property',0,NULL,NULL,'2026-07-31 08:09:52'),
(260,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:10:33','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:10:33'),
(261,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:10:36','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',2379,NULL,NULL,'2026-07-31 08:10:36'),
(262,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:10:53','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:10:53'),
(263,'3245ceac52d8271afe9d00123731d2b2','dwell','2026-07-31 17:10:54','property',0,'/property',61982,NULL,NULL,'2026-07-31 08:10:54'),
(264,'3245ceac52d8271afe9d00123731d2b2','pageview','2026-07-31 17:10:54','property',0,'/property',0,NULL,NULL,'2026-07-31 08:10:54'),
(265,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:10:57','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',4553,NULL,NULL,'2026-07-31 08:10:57'),
(266,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:10:57','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:10:57'),
(267,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:10:59','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',2251,NULL,NULL,'2026-07-31 08:11:00'),
(268,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:10:59','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:00'),
(269,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:01','property',0,'/property',1551,NULL,NULL,'2026-07-31 08:11:01'),
(270,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:01','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:01'),
(271,'3245ceac52d8271afe9d00123731d2b2','dwell','2026-07-31 17:11:21','property',0,'/property',26832,NULL,NULL,'2026-07-31 08:11:21'),
(272,'3245ceac52d8271afe9d00123731d2b2','pageview','2026-07-31 17:11:21','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:11:21'),
(273,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:53','property',0,'/property',51960,NULL,NULL,'2026-07-31 08:11:53'),
(274,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:53','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:53'),
(275,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:54','property',0,'/property',803,NULL,NULL,'2026-07-31 08:11:54'),
(276,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:54','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:54'),
(277,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:55','property',0,'/property',836,NULL,NULL,'2026-07-31 08:11:55'),
(278,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:55','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:55'),
(279,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:55','property',0,'/property',327,NULL,NULL,'2026-07-31 08:11:55'),
(280,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:55','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:55'),
(281,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:55','property',0,'/property',217,NULL,NULL,'2026-07-31 08:11:55'),
(282,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:55','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:55'),
(283,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:55','property',0,'/property',187,NULL,NULL,'2026-07-31 08:11:56'),
(284,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:56','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:56'),
(285,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:56','property',0,'/property',180,NULL,NULL,'2026-07-31 08:11:56'),
(286,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:56','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:56'),
(287,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:56','property',0,'/property',164,NULL,NULL,'2026-07-31 08:11:56'),
(288,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:56','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:56'),
(289,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:11:57','property',0,'/property',781,NULL,NULL,'2026-07-31 08:11:57'),
(290,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:11:57','property',0,'/property',0,NULL,NULL,'2026-07-31 08:11:57'),
(291,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:12:19','property',0,'/property',22555,NULL,NULL,'2026-07-31 08:12:19'),
(292,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:12:19','property',0,'/property',0,NULL,NULL,'2026-07-31 08:12:19'),
(293,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:12:26','property',0,'/property',6783,NULL,NULL,'2026-07-31 08:12:26'),
(294,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:12:26','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:12:26'),
(295,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:13:24','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',58018,NULL,NULL,'2026-07-31 08:13:24'),
(296,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:13:24','property',0,'/property',0,NULL,NULL,'2026-07-31 08:13:24'),
(297,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:13:28','property',0,'/property',3768,NULL,NULL,'2026-07-31 08:13:28'),
(298,'561fc9341c0ebf2ff27acc8c4900ca96','pageview','2026-07-31 17:15:52','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',0,NULL,NULL,'2026-07-31 08:15:52'),
(299,'561fc9341c0ebf2ff27acc8c4900ca96','dwell','2026-07-31 17:16:05','property',28,'/property/view/28/%EB%AA%A9%EB%8F%99%20%EC%A0%84%EC%84%B8%200%EB%A7%8C%EC%9B%90',13013,NULL,NULL,'2026-07-31 08:16:05'),
(300,'09783f13b168b11e48fcbff843766e7e','pageview','2026-07-31 17:20:44','main',0,'/',0,NULL,NULL,'2026-07-31 08:20:43'),
(301,'09783f13b168b11e48fcbff843766e7e','dwell','2026-07-31 17:20:48','main',0,'/',3674,NULL,NULL,'2026-07-31 08:20:47');

/*Table structure for table `site_analytics_visit` */

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

/*Data for the table `site_analytics_visit` */

insert  into `site_analytics_visit`(`visit_id`,`client_key`,`occurred_at`,`ip`,`referrer_url`,`utm_source`,`utm_medium`,`utm_campaign`,`user_agent`,`viewport_w`,`viewport_h`,`is_returning`,`created_at`) values 
('09783f13b168b11e48fcbff843766e7e','ms8nfzzcgkwc4s4s11m','2026-07-31 16:57:02','59.25.162.138',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',1707,781,'Y','2026-07-31 07:57:01'),
('10ffd2e6700487fca539e58f459821d9','ms5l8fi3vkpbzk5za7','2026-07-29 13:31:51','114.201.4.34','http://foray.bomul.co.kr/property',NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',883,616,'N','2026-07-29 04:31:53'),
('219e7ff0e4c2f54a3a97e73488d82d8e','ms8ds254s1i4h58mnjh','2026-07-31 12:26:29','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',1415,706,'Y','2026-07-31 03:26:30'),
('3245ceac52d8271afe9d00123731d2b2','ms8nt5npdyxbdd60t07','2026-07-31 17:07:16','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Claude/1.24012.9 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36 MSIX',720,807,'Y','2026-07-31 08:07:16'),
('561fc9341c0ebf2ff27acc8c4900ca96','ms8ik13pgjb6t6slpdv','2026-07-31 14:40:12','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',1415,706,'Y','2026-07-31 05:40:14'),
('97483f0d1964070999beaa63b077c5ad','ms8invkrpz40vnbc1p','2026-07-31 14:43:12','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Claude/1.24012.9 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36 MSIX',0,0,'N','2026-07-31 05:43:13'),
('bf8e20fbf1e18f76c5e5c57ec2f3919d','ms745tm2hrk7pukttk8','2026-07-30 15:09:28','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Claude/1.24012.9 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36 MSIX',0,0,'N','2026-07-30 06:09:30'),
('c5f5e45b36a83a6353fd472ed0a10f01','ms77ppg3p03ag83koip','2026-07-30 16:48:55','59.25.162.138','http://localhost:3000/',NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',1707,781,'N','2026-07-30 07:48:55'),
('c9cf43b34ee8279c8d63f706e0a11584','ms793k0p7rqrc9srve8','2026-07-30 17:27:41','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Claude/1.24012.9 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36 MSIX',0,0,'Y','2026-07-30 08:27:41'),
('eb4fe97c8f851cdb4d28d70a3fbb5fbf','ms6u58m3yuq2kktcq2f','2026-07-30 10:29:05','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',883,616,'Y','2026-07-30 01:29:07'),
('f4de97381ef1ad3ec2bc2b7b1d1d5a8f','ms72px0ftx4q4hcggc','2026-07-30 14:29:07','114.201.4.34',NULL,NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',883,616,'Y','2026-07-30 05:29:08');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
