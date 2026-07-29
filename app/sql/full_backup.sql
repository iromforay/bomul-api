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
  PRIMARY KEY (`basic_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

/*Data for the table `basic` */

insert  into `basic`(`basic_no`,`site_name`,`logo_dir`,`logo_name`,`logo_show`,`menu_back_color`,`menu_back_transparent`,`menu_bg_color`,`menu_bg_transparent`,`main_menu_color`,`sub_menu_focus_color`,`property_show`,`notice_show`,`around_show`,`charge_show`,`review_show`,`vod_show`,`map_show`,`link_menu_show`,`naver_reserve`,`naver_reserve_show`,`kakao_channel`,`kakao_channel_show`,`company_name`,`broker_office_name`,`broker_address`,`broker_phone`,`broker_reg_no`,`broker_ceo_name`,`broker_agent_name`,`broker_agent_phone`,`footer_copy`) values 
(1,'foray','','로고','N','#006eff','50','#e0e0e0',NULL,'#000000','#000000','Y','Y','N','Y','Y','Y','N','Y','','Y','','Y','25시 부동산','중개사무소 명칭 테스트','서울 동작구','02-1234-5678','000-00-00000','foray','foray','02-1234-5678','대표이사 : 나병연 | 개인정보관리책임자 : 나병연 | 사업자등록번호 : 000-00-00000 | 주소 경기 부천시 | TEL 02-1234-5678');

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
  PRIMARY KEY (`memo_no`),
  KEY `idx_parent` (`parent_table`,`parent_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='메모 (1:N, 상담노트 등에서 사용)';

/*Data for the table `memo` */

insert  into `memo`(`memo_no`,`parent_table`,`parent_no`,`writer_no`,`writer_name`,`memo_text`,`write_date`) values 
(1,'note',1,NULL,NULL,'foray','2026-07-24 08:25:12');

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
(1,'메인','main','#bc2020','46','<h5>타이틀문구&nbsp;- 보이는 매물</h5>','Y','N',NULL),
(2,'공지사항','notice','#de0d0d','42',NULL,NULL,NULL,NULL),
(3,'둘러보기','around','#000000','50',NULL,'N',NULL,NULL),
(4,'이용요금','charge','#000000','60',NULL,'N',NULL,NULL),
(5,'후기','review','#000000','62',NULL,'N',NULL,NULL),
(6,'오시는길','map','#000000','36',NULL,NULL,NULL,NULL),
(7,'홍보영상','vod','#000000','0',NULL,'N','N',NULL),
(8,'매물관리','property','#427cf0','0',NULL,'N','N',NULL);

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
  `visit_date` datetime DEFAULT NULL COMMENT '방문예정일',
  `next_contact_date` date DEFAULT NULL COMMENT '다음연락예정일',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '등록일',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`note_no`),
  KEY `idx_agent` (`agent_no`),
  KEY `idx_property` (`property_no`),
  KEY `idx_status` (`status`),
  KEY `idx_visit_date` (`visit_date`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='고객노트(방문일지)';

/*Data for the table `note` */

insert  into `note`(`note_no`,`agent_no`,`property_no`,`visitor_name`,`visitor_phone`,`visit_type`,`transaction_type`,`budget`,`preferred_area`,`inflow_type`,`status`,`visit_date`,`next_contact_date`,`created_at`,`updated_at`) values 
(1,0,0,'foray','010-1234-5678','tenant','sale\n',3000,'서울 동작구','visit','conulting','2026-07-24 00:00:00','2026-07-27','2026-07-24 08:13:18','2026-07-24 08:13:18'),
(2,0,0,'foray','010-1234-5678','tenant','sale\n',3000,'서울 동작구','visit','conulting','2026-07-24 00:00:00','2026-07-27','2026-07-24 08:14:35','2026-07-24 08:14:35');

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
(9,'notice',NULL,'foray',NULL,'F',NULL,'1','테스트2','&lt;p&gt;테스트2&lt;/p&gt;','','','','2026-07-20 06:11:23',NULL,0,'','');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_nopad_ci COMMENT='매물 정보';

/*Data for the table `property` */

insert  into `property`(`property_no`,`property_type`,`transaction_type`,`property_name`,`building_name`,`addr_sido`,`addr_sigungu`,`addr_dong`,`addr_road`,`supply_area`,`exclusive_area`,`total_floor`,`current_floor`,`room_count`,`bathroom_count`,`parking_available`,`parking_count`,`completion_date`,`move_in_date`,`direction`,`heating_fuel`,`heating_type`,`sale_price`,`jeonse_price`,`deposit_price`,`monthly_rent`,`loan_amount`,`maintenance_fee`,`status`,`display_yn`,`summary`,`description`,`main_image`,`main_path`,`view_count`,`created_at`,`updated_at`) values 
(1,'villa','jeonse','테스트 ',NULL,'서울시','동작구','',NULL,0,0,3,0,4,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,'테스트용 매물입니다.',NULL,NULL,NULL,0,'2026-07-09 07:47:18','2026-07-22 07:42:12'),
(2,'apt','sale','test',NULL,'','','',NULL,0,0,0,0,0,0,'Y',0,'2026-07-22 07:34:56','2026-07-22 07:34:56','S',1,1,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-22 07:34:56','2026-07-22 07:41:58'),
(3,'apt','monthly','1122',NULL,'','','',NULL,0,0,0,0,0,0,'Y',0,'2026-07-22 07:35:35','2026-07-22 07:35:35','S',1,1,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-22 07:35:35','2026-07-22 07:42:03'),
(10,'factory','0','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 03:38:10','2026-07-23 03:38:10'),
(11,'factory','0','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 03:42:51','2026-07-23 03:42:51'),
(12,'factory','0','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 03:46:17','2026-07-23 03:46:17'),
(13,'factory','sale','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 03:48:37','2026-07-23 03:48:37'),
(14,'factory','sale','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 03:56:04','2026-07-23 03:56:04'),
(15,'factory','sale','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 04:09:11','2026-07-23 04:09:11'),
(16,'factory','sale','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-23 04:13:06','2026-07-23 04:13:06'),
(17,'factory','sale','test',NULL,'서울시','영등포구','여의도동  45-3',NULL,0,0,0,0,0,0,'Y',0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1,1,NULL,NULL,'2d2ea918023d17aead7e5829ef2a9fa2.png','foray/property/17/',0,'2026-07-23 04:14:37','2026-07-23 06:18:11'),
(18,'apt','sale','ㅂㅇㄹ',NULL,'서울','영등포구','대림동','서울 영등포구 가마산로 311',0,0,0,0,0,0,'Y',0,'2026-07-27 02:02:28','2026-07-27 02:02:28','S',1,1,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,0,'2026-07-27 02:02:28','2026-07-27 02:02:28'),
(19,'apt','sale','사진 test',NULL,'경남','창녕군','반포리','경남 창녕군 남지읍 골담실길 16',0,0,0,0,0,0,'Y',0,'2026-07-27 13:23:13','2026-07-27 13:23:13','S',1,1,0,0,0,0,0,0,1,1,NULL,NULL,'a1c96fcdd536eb0304df01dbac0174c9.png','foray/property/19/',14,'2026-07-27 13:23:13','2026-07-28 08:56:10'),
(20,'officetel','monthly','사진 상세 테스트',NULL,'서울','강남구','신사동','서울 강남구 가로수길 5',2,0,0,0,0,0,'Y',0,'2026-07-27 00:00:00','2026-07-27 00:00:00','S',1,1,30000,0,5000,20,0,15,2,1,'한줄소개 테스트','<p>상세설명 테스트</p>',NULL,NULL,23,'2026-07-27 17:48:40','2026-07-29 04:02:37');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='방문자 트래킹 - 행동 이벤트 원본 (append-only)';

/*Data for the table `site_analytics_event` */

insert  into `site_analytics_event`(`event_no`,`visit_id`,`event_type`,`occurred_at`,`page_type`,`property_no`,`request_url`,`dwell_ms`,`click_action`,`search_filter`,`created_at`) values 
(1,'79d1967707a0d88f16d855de1c4cd850','pageview','2026-07-29 13:02:34','property',20,'/property/view/20/%EC%8B%A0%EC%82%AC%EB%8F%99%20%EC%9B%94%EC%84%B8',0,NULL,NULL,'2026-07-29 04:02:37'),
(2,'79d1967707a0d88f16d855de1c4cd850','dwell','2026-07-29 13:02:35','property',20,'/property/view/20/%EC%8B%A0%EC%82%AC%EB%8F%99%20%EC%9B%94%EC%84%B8',1163,NULL,NULL,'2026-07-29 04:02:38');

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
('79d1967707a0d88f16d855de1c4cd850',NULL,'2026-07-29 13:02:34','114.201.4.34','http://foray.bomul.co.kr/property',NULL,NULL,NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0',883,616,'N','2026-07-29 04:02:37');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
