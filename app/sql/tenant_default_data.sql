-- 신규 상점 DB 생성 시 반드시 필요한 기본 데이터 (menu/function_menu/basic).
-- basic은 placeholder("데모부동산") 상태로 들어가고, TenantProvisioner::updateBasicRow()가
-- 상호명/대표자명/등록번호만 실제 신청정보로 UPDATE한다 (INSERT 아님 - 이 행을 그대로 씀).
-- add_menu는 지금 주석처리 - file2(지도 아이콘 이미지) 행이 demo 상점 전용 업로드 파일이라
-- 그대로 복사하면 깨진 이미지가 됨. 필요해지면 그 행만 빼고 주석 해제.

insert  into `menu`(`menu_no`,`name`,`code`,`bgcolor`,`transparent`,`title`,`title_show`,`bgcolor_show`,`write_date`) values 
(1,'메인','main','#ffffff','50','<h5><strong>더 잘보이고, 더 빨</strong><strong style="background-color: rgb(230, 0, 0);"></strong><strong>리 거래되는 매물</strong></h5>','Y','N',NULL),
(2,'공지사항','notice','#ffffff','50',NULL,NULL,'Y',now()),
(3,'둘러보기','around','#ffffff','50','<p>시선이 머무는 하이라이트 매물입니다.</p>','N','Y',now()),
(4,'이용요금','charge','#ffffff','50',NULL,'N',NULL,now()),
(5,'후기','review','#ffffff','50','<p>저희 매장을 이용해주신 고객들의 생생후기 게시판입니다.</p>','N','Y',now()),
(6,'오시는길','map','#ffffff','50',NULL,NULL,'Y',now()),
(7,'홍보영상','vod','#ffffff','50',NULL,'N','N',now()),
(8,'매물관리','property','#ffffff','50','믿고 보는 우리동네 매물, 한눈에','N','Y',now());

-- insert  into `add_menu`(`add_menu_no`,`code`,`key`,`text`,`show`,`img_dir`,`img_name`,`write_date`) values
-- (1,'map','tel','연락처1','Y','','',NULL),
-- (2,'map','addr','주소2','Y','','',NULL),
-- (3,'map','subway','지하철 정보','Y','','',NULL),
-- (4,'map','bus','버스 정보','Y','','',NULL),
-- (5,'map','google','maps.google.com/?','Y','','',NULL),
-- (8,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

insert  into `function_menu`(`function_menu_no`,`function_menu_key`,`function_menu_name`,`function_menu_value`,`write_date`) values 
(1,'property_name','매물명','Y',now()),
(2,'building_name','건물명','Y',now()),
(3,'addr_sido','시도','Y',now()),
(4,'addr_sigungu','시군구','Y',now()),
(5,'addr_dong','읍면동','Y',now()),
(6,'addr_road','도로명','Y',now()),
(7,'supply_area','공급면적','Y',now()),
(8,'exclusive_area','전용면적','Y',now()),
(9,'total_floors','총층수','Y',now()),
(10,'current_floor','해당층','Y',now()),
(11,'room_count','방개수','Y',now()),
(12,'bathroom_count','화장실수','Y',now()),
(13,'parking_available','주차가능여부','Y',now()),
(14,'parking_count','총주차가능대수','Y',now()),
(15,'completion_date','준공일','Y',now()),
(16,'move_in_date','입주가능일','Y',now()),
(17,'direction','방향','Y',now()),
(18,'heating_fuel','난방연료','Y',now()),
(19,'heating_type','난방방식','Y',now()),
(20,'sale_price','매매가','Y',now()),
(21,'jeonse_price','전세가','Y',now()),
(22,'deposit_price','보증금','Y',now()),
(23,'monthly_rent','월세','Y',now()),
(24,'loan_amount','융자금','Y',now()),
(25,'maintenance_fee','관리비','Y',now()),
(26,'status','거래상태','Y',now()),
(27,'display_yn','홈페이지노출','Y',now()),
(28,'summary','한줄소개','Y',now()),
(29,'description','상세설명','Y',now()),
(30,'thumbnail_image','대표이미지','Y',now()),
(31,'detail_images','추가이미지','Y',now());

insert  into `basic`(`basic_no`,`site_name`,`logo_dir`,`logo_name`,`logo_show`,`menu_back_color`,`menu_back_transparent`,`menu_bg_color`,`menu_bg_transparent`,`main_menu_color`,`sub_menu_focus_color`,`property_show`,`notice_show`,`around_show`,`charge_show`,`review_show`,`vod_show`,`map_show`,`link_menu_show`,`naver_reserve`,`naver_reserve_show`,`kakao_channel`,`kakao_channel_show`,`company_name`,`broker_office_name`,`broker_address`,`broker_phone`,`broker_reg_no`,`broker_ceo_name`,`broker_agent_name`,`broker_agent_phone`,`footer_copy`,`naver_site_verification`) values
    (1,'데모부동산','','로고','N','#ffffff','100','#F4F5F7',NULL,'#000222','#2563eb','Y','Y','N','N','Y','Y','N','N','','N','','N','데모 부동산','데모 부동산 공인중개사사무소','서울 동작구 노량진로 100','02-815-2500','제11590-2024-00042호','나병연','김민수','02-815-2501','대표이사 : 000 | 개인정보관리책임자 : 000 | 사업자등록번호 : 111-12-12345 | 주소 서울 동작구 노량진로 100 | TEL 02-000-0000','test2');

--sns 링크 5개 초기 세팅
insert  into `add_menu`(`add_menu_no`,`code`,`key`,`text`,`show`,`img_dir`,`img_name`,`write_date`) values
(1,'basic','sns1',NULL,'N',NULL,NULL,now()),
(2,'basic','sns2',NULL,'N',NULL,NULL,now()),
(3,'basic','sns3',NULL,'N',NULL,NULL,now()),
(4,'basic','sns4',NULL,'N',NULL,NULL,now()),
(5,'basic','sns5',NULL,'N',NULL,NULL,now());

INSERT INTO `profile`(profile_no,nick_name,img_dir,img_name)VALUES(1,'데모 중개사무소', '','')