<?php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>view_more</title>
    <style>
        :root {
          --bgcolor : rgba(0,0,0,0);
          --sec_bgurl : #f5f5f5;

          /* 임의로 바꾼 값 */
          --main_menucolor : #000000;
          --sub_menubgcolor : #000000;
          --menu_back_color : #006eff;
          --menu_back_transparent : 0.5;
          --transparent: 0.4;
          --sec_bgcolor: #000000;

          /* 원래 값
          --main_menucolor : #fff;
          --sub_menubgcolor : #E72D2B;
          --sec_bgcolor : #f5f5f5;
          --menu_back_color : #fff;
          --menu_back_transparent : 0; 
          --transparent : 0;
          */
        }
        @font-face {
            font-family: 'Pretendard';
            src: url('/resources/css/fonts/pretendard/Pretendard-Regular.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Pretendard';
            src: url('/resources/css/fonts/pretendard/Pretendard-Medium.woff2') format('woff2');
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'Pretendard';
            src: url('/resources/css/fonts/pretendard/Pretendard-Bold.woff2') format('woff2');
            font-weight: 700;
            font-style: normal;
        }
        body {
            background-color: #f5f5f5;
            font-family: 'Pretendard', Arial, sans-serif;
            font-size: 1rem;
            line-height: 1.4rem;
            word-break: keep-all;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
                'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
                sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        code {
            font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New',
                monospace;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .App {
        position: relative;  overflow: hidden;
        }
        * {margin: 0; padding: 0; border: none; box-sizing: border-box; text-decoration: none; list-style: none; color: #000; line-height: initial;}
        html {
            font-size: 20px;
            line-height: 28px;
            font-weight: 400;
            overflow-y: scroll;
        }
        body {
            font-family: 'pretendard', sans-serif;
            word-break: keep-all;
            font-size: 1rem;
            line-height: 1.4rem;
            overflow-y: auto;
        }

        .sections {position: relative; min-height: calc(100vh - 90px); margin: 80px 0 0 0; padding-bottom: 180px;}
        .width_con {position: relative; width: 1200px; margin: 0 auto;}
        svg path {color: inherit;}
        span {font-size: inherit; font-weight: inherit; color: inherit; line-height: inherit;}

        p {font-size: 1rem; line-height: 1.4rem;}
        h1 {font-size: 2rem; line-height: 2.4rem;}
        h2 {font-size: 1.8rem; line-height: 2.2rem;}
        h3 {font-size: 1.6rem; line-height: 2rem;}
        h4 {font-size: 1.5rem; line-height: 1.9rem;}
        h5 {font-size: 1.2rem; line-height: 1.6rem;}
        h6 {font-size: 1rem; line-height: 1.4rem;}


        #header {position: fixed; top: 0; left: 0; display: flex; width: 100%; height: 80px; z-index: 9998;}
        #header:before {position: absolute; top: 0; left: 0; content: ''; width: 100%; height: 100%; background: var(--menu_back_color); opacity: var(--menu_back_transparent);}
        #header.on {background: #fff; border-bottom: 1px solid #E0E0E0;}
        #header .width_con {display: flex; justify-content: space-between; align-items: center;}
        #header .width_con .logo_con {display: flex; justify-content: center; align-items: center;  height: 100%;}
        #header .width_con .logo_con a {cursor: pointer;}
        #header .width_con .logo_con a img {max-width: 165px; object-fit: cover;}
        #header .width_con .nav_con {display: flex; justify-content: space-between; height: 100%;}
        #header .width_con .nav_con .fn_btn {position: relative; display: flex; justify-content: center;  align-items: center; width: 72px; height: 100%; background: #fff; border: 1px solid #E0E0E0; border-top: none; border-bottom: none;}
        #header .width_con .nav_con .fn_btn > div {position: relative; cursor: pointer;}
        #header .width_con .nav_con .fn_btn div {text-align: center;}
        #header .width_con .nav_con .fn_btn div p {font-size: 0.6rem; line-height: 1rem; font-weight: 700; color: #999;}
        #header .width_con .nav_con .fn_btn div svg {color: #999;}
        #header .width_con .mob_con {display: none; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 7px 14px; border: 1px solid #E0E0E0; border-top: none; border-bottom: none; background: #fff; cursor: pointer;}
        #header .width_con .mob_con .open_nav {position: relative; width: 30px; height: 24px;}
        #header .width_con .mob_con .open_nav:before {position: absolute; top: 0; left: 0; width: 100%; content: ''; height: 3px; background: #999;}
        #header .width_con .mob_con .open_nav:after {position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; content: ''; background: #999;}
        #header .width_con .mob_con .open_nav div {position: absolute; top: 50%; left: 0; width: 100%; height: 3px; background: #999; transform: translateY(-50%);}
        #header.on .width_con .mob_con .open_nav:before,
        #header.on .width_con .mob_con .open_nav:after,
        #header.on .width_con .mob_con .open_nav div {background: #999;}
        #header .width_con .mob_con span {margin: 5px 0 0 0; font-size: 0.6rem; line-height: 1rem; font-weight: 700; color: #999;}
        #header .width_con .nav_con .fn_btn .sns_list {position: absolute; top: calc(100%); left: 50%; display: none; padding: 20px; background: #fff; border: 1px solid #E0E0E0; transform: translateX(-50%);}
        #header .width_con .nav_con .fn_btn .sns_list.on {display: block;}
        #header .width_con .nav_con .fn_btn .sns_list li {margin: 0 0 10px 0;}
        #header .width_con .nav_con .fn_btn .sns_list li:last-child {margin: 0;}
        #header .width_con .nav_con .fn_btn .sns_list li img {width: 30px; height: 30px; object-fit: cover;}
        #header .width_con .nav_con > ul {display: flex; align-items: center;}
        #header .width_con .nav_con > ul li {margin: 0 20px 0 0; padding: 0 5px;}
        #header .width_con .nav_con > ul li a {font-size: 0.9rem; line-height: 1.3rem; font-weight: 700; color: var(--main_menucolor); cursor: pointer;}
        #header.on .width_con .nav_con > ul li a {color: #000;}
        #header.notice .width_con .nav_con ul li.notice,
        #header.around .width_con .nav_con ul li.around,
        #header.charge .width_con .nav_con ul li.charge,
        #header.review .width_con .nav_con ul li.review,
        #header.vod .width_con .nav_con ul li.vod,
        #header.map .width_con .nav_con ul li.map {background: var(--sub_menubgcolor);}
        #header.notice .width_con .nav_con ul li.notice a,
        #header.around .width_con .nav_con ul li.around a,
        #header.charge .width_con .nav_con ul li.charge a,
        #header.review .width_con .nav_con ul li.review a,
        #header.vod .width_con .nav_con ul li.vod a,
        #header.map .width_con .nav_con ul li.map a {color: #fff;}


        .footer_con {position: fixed; bottom: 0; left: 0; display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start; width: 100%; padding: 18px 0; background: #212121;}
        .footer_con.folding {padding: 7px 0;}
        .footer_con .width_con {}
        .footer_con .width_con .tit {display: flex; justify-content: space-between; align-items: center; width: 100%;}
        .footer_con .width_con .tit h5 {font-size: 0.7rem; line-height: 1.1rem; font-weight: 700; color: #999; cursor: pointer;}
        .footer_con .width_con .tit p {font-size: 0.7rem; line-height: 1.1rem; font-weight: 400; color: #999;}
        .footer_con .width_con p.footer_desc {height: auto; font-size: 0.6rem; line-height: 1rem; font-weight: 400; color: #999; opacity: 1;}
        .footer_con.folding .width_con p.footer_desc {height: 0; opacity: 0;}
        .footer_con .bottom_btn_con {position: absolute;  top: -62px; right: 0;}
        .footer_con.folding .bottom_btn_con {top: -51px;}
        .footer_con .bottom_btn_con .inner {position: relative; display: flex; justify-content: center;}
        .footer_con .bottom_btn_con .inner > div {display: flex; justify-content: center; align-items: center; padding: 10px 35px; border-radius: 4px 4px 0px 0px;}
        .footer_con .bottom_btn_con .inner .naver_con {background: #2DB400;}
        .footer_con .bottom_btn_con .inner .kakao_con {background: #FFDE00;}
        .footer_con .bottom_btn_con .inner > div a {display: flex; justify-content: center; align-items: center; width: 100%;}
        .footer_con .bottom_btn_con .inner > div img {width: 20px; margin: 0 5px 0 0;}
        .footer_con .bottom_btn_con .inner > div p {color: #fff; font-size: 0.8rem; line-height: 1.2rem; font-weight: 700;}
        .footer_con .bottom_btn_con .inner .kakao_con p {color: #231815;}


        body.open_nav {
            overflow: hidden;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .App.open_nav {overflow: hidden;}
        .App .mob_nav {position: fixed; top: 0; right: -300px; display: block; width: 300px; height: 100vh; padding: 25px 10px; background: #F5F5F5; transition: all 300ms ease-in-out; z-index: 9998; overflow-y: auto; -webkit-overflow-scrolling: touch;}
        .App .mob_nav .row1 {display: flex; justify-content: flex-end; align-items: center;}
        .App .mob_nav .row1 .sns_list {display: flex; align-items: center;}
        .App .mob_nav .row1 .sns_list li {margin: 0 10px;}
        .App .mob_nav .row1 .sns_list li img {height: 20px;}
        .App .mob_nav .row1 .close_nav {position: relative; width: 30px; height: 24px; cursor: pointer;}
        .App .mob_nav .row1 .close_nav:before {position: absolute; top: 10px; left: 0; width: 100%; content: ''; height: 3px; background: #212121; transform: rotate(45deg);}
        .App .mob_nav .row1 .close_nav:after {position: absolute; top: 10px; left: 0; width: 100%; height: 3px; content: ''; background: #212121; transform: rotate(-45deg);}
        .App .mob_nav .row2 {margin: 30px 0 0 0;}
        .App .mob_nav .row2 .login {padding: 5px 0; font-weight: 700;}
        .App .mob_nav .row2 .login svg {margin: 0 0 0 10px;}
        .App .mob_nav .row2 .join {font-size: 0.7rem; line-height: 1.1rem; color: #E72D2B;}
        .App .mob_nav .row2 .join span {font-weight: 700; text-decoration: underline;}
        .App .mob_nav .row3 {}
        .App .mob_nav .rows h6 {position: relative; margin: 30px 0 0 0; font-size: 1rem; line-height: 1.4rem; font-weight: 700; color: #212121;}
        .App .mob_nav .rows h6:before {position: absolute; top: 50%; right: 0; content: ''; width: 70%; height: 1px; background: #212121; transform: translateY(-50%);}
        .App .mob_nav .row3 ul {}
        .App .mob_nav .row3 ul li {padding: 15px 0; border-bottom: 1px solid #E0E0E0;}
        .App .mob_nav .row3 ul li a {font-size: 0.8rem; line-height: 1.2rem; font-weight: 500;}
        .App .mob_nav .row4 {}
        .App .mob_nav .rows.row4 h6:before {width: 80%;}
        .App .mob_nav .row4 ul {display: flex; align-items: center;}
        .App .mob_nav .row4 ul li {padding: 10px; margin: 0 5px 0 0;}
        .App .mob_nav .row4 ul li img {width: 20px;}

        .sections.view {}
        .sections.view .width_con {padding: 20px 0 0 0;}
        .sections.view .width_con .view_con {padding: 15px; border-radius: 4px; background: #fff;}
        .sections.view .row {}
        .sections.view .row1 {}
        .sections.view .row1 .board_type {display: inline-block; padding: 5px 10px; border-radius: 4px;}
        .sections.view .row1 .board_type.notice {background: #212121;}
        .sections.view .row1 .board_type.event {background: #FF51A4;}
        .sections.view .row1 .board_type.charge {background: #7B9DB5;}
        .sections.view .row1 .board_type.review {background: #2DB400;}
        .sections.view .row1 .board_type.vod {background: #AF41CB;}
        .sections.view .row1 .board_type span {font-size: 0.8rem; line-height: 1.2rem; font-weight: 400; color: #fff;}
        .sections.view .row2 {border-bottom: 2px solid #06F;}
        .sections.view .row2 .title_con {padding: 6px 0; text-align: left;}
        .sections.view .row2 .title_con h3 {font-size: 1.5rem; line-height: 1.9rem; font-weight: 400;}
        .sections.view .row2 .score_con {display: flex; align-items: center; padding: 10px 0; border-top: 1px solid #A5AFB6;}
        .sections.view .row2 .score_con h5 {}
        .sections.view .row2 .score_con > div {margin: 0 10px;}
        .sections.view .row2 .score_con span {font-size: 0.7rem; line-height: 1.1rem;}
        .sections.view .row3 {padding: 10px 0; border-bottom: 1px solid #A5AFB6;}
        .sections.view .row3 .view_info {display: flex; justify-content: space-between; align-items: flex-end;}
        .sections.view .row3 .view_info .col {}
        .sections.view .row3 .view_info .col span {position: relative; font-size: 0.7rem; line-height: 1.1rem;}
        .sections.view .row3 .view_info .col span.writer {padding: 0 10px 0 0; margin: 0 10px 0 0;}
        .sections.view .row3 .view_info .col span.writer::before {position: absolute; top: 0; right: 0; content: ''; width: 1px; height: 100%; background: #A5AFB6;}
        .sections.view .row3 .view_info .col span.date {}
        .sections.view .row3 .view_info .col span.hits {}
        .sections.view .row4 {min-height: 200px; padding: 0px 0 0 0; border-bottom: 1px solid #A5AFB6; overflow: auto;}
        .sections.view .row4 img {max-width: 100%;}
        .sections.view .row4 video {width: 80%; margin: 0 0 10px 0;}
        .sections.view .row4 .iframe_con {position: relative; width: 50%; height: 0; padding-bottom: 28%; margin: 0 0 10px 0;}
        .sections.view .row4 .iframe_con iframe {position: absolute; top: 0; left: 0; width: 100%; height: 100%;}
        .sections.view .view_con .row5 {margin: 20px 0 0 0;}
        .sections.view .view_con .row5 .btn_con {display: flex;}
        .sections.view .view_con .row5 .btns {width: 120px; margin: 0 0 0 10px; padding: 10px 0; text-align: center; border-radius: 4px; box-shadow: 2px 2px 4px rgba(0,0,0,.2);}
        .sections.view .view_con .row5 .btns span {font-size: 0.8rem; line-height: 1.2rem; font-weight: 700;}
        .sections.view .view_con .row5 .btns.exit {background: #fff; border: 1px solid #06F; cursor: pointer;}
        .sections.view .view_con .row5 .btns.exit span {color: #06F;}
        .sections.view .view_con .row5 .btns.exit span a {display: inline-block; width: 100%; height: 100%;; color: inherit;}


        .section_01 {width: 100%; height: 100vh; margin: 0; padding-bottom: 80px; overflow: hidden;}
        .section_01 .title_con {position: absolute; top: 50%; left: 50%; width: 100%; transform: translate(-50%, -50%); text-align: center; z-index: 9997;}
        .section_01 .title_con p {font-size: 2rem; line-height: 2.4rem; font-weight: 300; color: #fff;}
        .section_01 .title_con h2 {font-size: 2rem; line-height: 2.4rem; font-weight: 700; color: #fff;}
        .section_01 .img_con {position: relative;}
        .section_01 .img_con div {height: 100vh;}
        .section_01 .img_con:before {position: absolute; top: 0; left: 0; width: 100%; height: 100%; content: ''; background: var(--bgcolor); opacity: var(--transparent);}
        .section_01 .img_con img {width: 100%;}
        .section_01 .slick-prev {left: 16% !important; z-index: 9997;}
        .section_01 .slick-next {right: 16% !important; z-index: 9997;}
        .section_01 .slick-prev:before, .section_01 .slick-next:before {position: absolute;top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50px; height: 50px; transition: all 150ms ease-in-out;}
        .section_01 .slick-prev:before {content: '' !important; background: url(../public/img/sec_01/prev.png) center no-repeat; background-size: contain;}
        .section_01 .slick-prev:hover:before {background: url(../public/img/sec_01/prev_h.png) center no-repeat; background-size: contain;}
        .section_01 .slick-next:before {content: '' !important; background: url(../public/img/sec_01/next.png) center no-repeat; background-size: contain;}
        .section_01 .slick-next:hover:before {background: url(../public/img/sec_01/next_h.png) center no-repeat; background-size: contain;}

        .section_02 {background: var(--sec_bgurl); background-size: cover; background-position: center; background-repeat: no-repeat;}
        .section_02::before {position: absolute; top: 0; left: 0; content: ''; width: 100%; height: 100%; background: var(--sec_bgcolor); opacity: var(--transparent);}
        .section_02 .width_con {}
        .section_02 .width_con .title_con {padding: 40px 0; text-align: center;}
        .section_02 .width_con .main_con {position: relative; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; padding: 45px 0;}
        .section_02 .width_con .btn_con {margin: 20px 0 0 0; text-align: center;}
        .section_02 .width_con .btn_con .btn_more {padding: 10px 20px; border-radius: 4px; background: #999; font-size: 0.9rem; line-height: 1.3rem; font-weight: 600; color: #fff; cursor: pointer; box-shadow: 2px 2px 4px 0px rgba(0, 0, 0, 0.20);}
        .section_02 .width_con .main_con h4 {font-weight: 700;}
        .section_02 .width_con .main_con p {margin: 20px 0 0 0; font-size: 0.8rem; line-height: 1.2rem;}
        .section_02 .width_con .main_con ul {display: flex; justify-content: center; align-items: center; margin: 10px 0 0 0;}
        .section_02 .width_con .main_con ul li {position: relative; padding: 0 20px; font-size: 0.8rem; line-height: 1.2rem; cursor: pointer;}
        .section_02 .width_con .main_con ul li.on {font-weight: 700;}
        .section_02 .width_con .main_con ul li:before {position: absolute; top: 50%; right: 0; content: ''; width: 1px; height: 70%; background: #000; transform: translateY(-50%);}
        .section_02 .width_con .main_con ul li:last-child::before {display: none;}
        .section_02 .board_con {}
        .section_02 .board_con ul {display: flex; flex-wrap: wrap; width: 100%;}
        .section_02 .board_con ul li {display: flex; justify-content: space-between; flex-direction: column; width: calc(25% - 20px); margin: 0 20px 20px 0; padding: 25px; border: 1px solid #E0E0E0; box-shadow: 2px 2px 4px 0px rgba(0, 0, 0, 0.20); background: #fff;}
        .section_02 .board_con ul.vod_list li {width: calc(33.333% - 20px); padding: 0;}
        .section_02 .board_con ul.vod_list li .desc_con {margin: 0;}
        .section_02 .board_con ul.vod_list li h3.board_title {padding: 0 25px;}
        .section_02 .board_con ul.vod_list li .btn_con {padding: 0 25px 25px 25px;}
        .section_02 .board_con ul li:nth-child(4),
        .section_02 .board_con ul li:nth-child(8) {}
        .section_02 .board_con ul li h5 {display: flex; align-items: center;}
        .section_02 .board_con ul li h5 span {display: inline-block; padding: 2px 10px; border-radius: 4px; color: #fff; font-size: 0.7rem; line-height: 1.1rem; font-weight: 700;}
        .section_02 .board_con ul li h5.event span {background: #FF51A4;}
        .section_02 .board_con ul li h5.notice span {background: #E72D2B;}
        .section_02 .board_con ul li h5.review span {background: #2DB400;}
        .section_02 .board_con ul li h3.board_title {max-height: 55px; margin: 10px 0 0 0; font-size: 1rem; line-height: 1.4rem; font-weight: 600; overflow: hidden;}
        .section_02 .board_con ul li .score_con div {display: flex; margin: 0 0 0 10px;}
        .section_02 .board_con ul li .img_con {width: 100%; margin: 20px 0 0 0;}
        .section_02 .board_con ul li .img_con img {width: 100%;}
        .section_02 .board_con ul li .desc_con {max-height: 220px; margin: 12px 0 0 0; overflow: hidden;}
        .section_02 .board_con ul li .desc_con p {font-size: 0.7rem; line-height: 1.1rem; font-weight: 400; color: #555;}
        .section_02 .board_con ul li .desc_con img {width: 100%;}
        .section_02 .board_con ul li .desc_con .video_con {}
        .section_02 .board_con ul li .desc_con .video_con video {width: 100%;}
        .section_02 .board_con ul li .desc_con .iframe_con {position: relative; width: 100%; height: 0; padding-bottom: 56.25%;}
        .section_02 .board_con ul li .desc_con .iframe_con iframe {position: absolute; top: 0; left: 0; width: 100%; height: 100%;}
        .section_02 .board_con ul li .btn_con {display: flex; justify-content: space-between; align-items: center; width: 100%; margin: 20px 0 0 0;}
        .section_02 .board_con ul li .btn_con .btn01 {font-size: 0.6rem; line-height: 1rem; font-weight: 700; text-decoration: underline;}
        .section_02 .board_con ul li .btn_con img {}

        .section_02.map .width_con {height: 100%;}
        .section_02 .width_con .map_con {display: flex; justify-content: space-between; align-items: end; height: calc(100% - 140px); padding: 40px 0 0 0;}
        .section_02 .width_con .map_con .col {width: 50%;}
        .section_02 .width_con .map_con .col h3 {font-size: 1.5rem; line-height: 1.9rem; font-weight: 700;}
        .section_02 .width_con .map_con .col ul {margin: 80px 0 0 0;}
        .section_02 .width_con .map_con .col ul li {display: flex; align-items: center; margin: 10px 0 0 0; padding: 0 0 10px 0; border-bottom: 1px solid #E0E0E0;}
        .section_02 .width_con .map_con .col ul li .img_con {position: relative; display: flex; justify-content: center; align-items: center; padding: 18px 24px;}
        .section_02 .width_con .map_con .col ul li .img_con:before {position: absolute; top: 50%; right: 0; content: ''; width: 1px; height: 80%; transform: translateY(-50%); background: #212121;}
        .section_02 .width_con .map_con .col ul li .img_con img {}
        .section_02 .width_con .map_con .col ul li .desc_con {margin: 0 0 0 20px;}
        .section_02 .width_con .map_con .col ul li .desc_con h5 {font-size: 1rem; line-height: 1.4rem; font-weight: 700;}
        .section_02 .width_con .map_con .col ul li .desc_con p {margin: 10px 0 0 0; font-size: 0.8rem; line-height: 1.2rem; font-weight: 400;}
        .section_02 .width_con .map_con .col .link_con {width: 100%;}
        .section_02 .width_con .map_con .col .link_con img {width: 100%;}
        .section_02 .width_con .map_con .col .link_con iframe {}

        .sections.slide .slick-prev,
        .sections.slide .slick-next {top: -12% !important; transform: translate(0, 0) !important; z-index: 999;}
        .sections.slide .slick-prev {left: calc(50% - 150px); transform: translateX(-50%) !important;}
        .sections.slide .slick-next {right: calc(50% - 150px); transform: translateX(50%) !important;}
        .sections.slide .slick-prev:before, .slick-next:before {display: block; width: 20px; height: 7px;}
        .sections.slide .slick-prev:before {content: '' !important; background: url(/resources/img/sec_02/prev.png) center no-repeat; background-size: contain;}
        .sections.slide .slick-next:before {content: '' !important; background: url(/resources/img/sec_02/next.png) center no-repeat; background-size: contain;}

        .section_02.more {}
        .section_02.more:before {display: none;}
        .section_02.more .width_con {padding: 20px 0 0 0;}
        .section_02.more .width_con .more_con {padding: 15px; border-radius: 4px; background: #fff; box-shadow: 2px 2px 10px 0px rgba(0, 0, 0, 0.20);}
        .section_02.more .width_con .btn_home {display: inline-block; padding: 10px 20px; border-radius: 999px; background: #212121; box-shadow: 2px 2px 4px 0px rgba(0, 0, 0, 0.20); cursor: pointer; color: #fff; font-size: 0.9rem; line-height: 1.3rem; font-weight: 600;}
        .section_02.more .width_con .borad_rows {margin: 20px 0 0 0;}
        .section_02.more .width_con .borad_rows .rows {}
        .section_02.more .width_con .rows ul {position: relative; display: flex; border-top: 1px solid #E0E0E0;}
        .section_02.more .width_con .rows ul:after {position: absolute; top: 0; left: 0; content: ''; width: 100%; height: 100%; background: rgba(0, 0, 0, .8); display: none;}
        .section_02.more .width_con .rows ul.deleted:after {display: block;}
        .section_02.more .width_con .rows ul li {padding: 15px 0; text-align: center; font-size: 0.8rem; line-height: 1.2rem; color: #212121; border-right: 1px solid #E0E0E0;}
        .section_02.more .width_con .rows ul li.board_no {width: 10%}
        .section_02.more .width_con .rows ul li.board_type {width: 10%;}
        .section_02.more .width_con .rows ul li.board_type span {display: inline-block; padding: 4px; border-radius: 4px; color: #fff; font-size: 0.7rem; line-height: 1.1rem; font-weight: 700;}
        .section_02.more .width_con .rows ul li.board_type .notice {background: #212121;}
        .section_02.more .width_con .rows ul li.board_type .event {background: #FF51A4;}
        .section_02.more .width_con .rows ul li.board_type .charge {background: #7B9DB5;}
        .section_02.more .width_con .rows ul li.board_type .review {background: #2DB400;}
        .section_02.more .width_con .rows ul li.board_type .vod {background: #AF41CB;}
        .section_02.more .width_con .rows ul li.score {width: 12%;}
        .section_02.more .width_con .rows ul li.board_title {width: 35%;}
        .section_02.more .width_con .rows ul li.board_writer {width: 18%;}
        .section_02.more .width_con .rows ul li.board_date {width: 15%; border-right: none;}
        .section_02.more .width_con .row0 ul {border: none;}
        .section_02.more .width_con .row0 ul li {border: none; font-size: 0.7rem; line-height: 1.1rem; font-weight: 700;}

        .section_02.more .width_con .pagenation {display: flex; margin: 20px 0 0 0;}
        .section_02.more .width_con .pagenation li {display: flex; justify-content: center; align-items: center; padding: 10px; margin: 0 5px 0 0; border-radius: 4px; background: #EBEFF2; cursor: pointer;}
        .section_02.more .width_con .pagenation li span {font-size: 0.7rem; line-height: 0.7rem;}
        .section_02.more .width_con .pagenation li.on {background: #A5AFB6;}
        .section_02.more .width_con .pagenation li.on span {color: #fff;}

        .section_03 {background: #f5f5f5;}
        .section_03::before {position: absolute; top: 0; left: 0; content: ''; width: 100%; height: 100%; background: var(--sec_bgcolor); opacity: var(--transparent);}
        .section_03 .width_con {}
        .section_03 .width_con .title_con {padding: 40px 0; text-align: center;}
        .section_03 .width_con .title_con h3 {}
        .section_03 .width_con .title_con p {margin: 10px 0 0 0;}
        .section_03 .width_con .main_con {display: flex; justify-content: center; align-items: center; padding: 45px 0 0 0;}
        .section_03 .width_con .main_con h4 {}
        .section_03 .slide_con {margin: 20px 0 0 0;}
        .section_03.slide .slick-prev,
        .section_03.slide .slick-next {top: -22% !important;}
        .section_03 .slide_con ul {}
        .section_03 .slide_con ul li {width: 100%; height: 500px;}
        /* .section_03 .slide_con ul li img {width: 100%;} */
        .section_03 .zoom {display: none; margin: 40px 0 0 0; font-size: 0.7rem; line-height: 1.1rem; text-align: center;}
        .section_03 .zoom.on {display: block;}

        /* ===========================
        * Layer Popup
        * =========================== */

        .layer_popup{
          display: none;
          position: fixed;
          inset: 0;
          z-index: 9999;
        }

        .layer_popup.on{
          display: flex;
        }

        .layer_popup .dim{
          position: absolute;
          inset: 0;
          background: rgba(0, 0, 0, .6);
        }
        .layer_popup .popup_wrap{
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 80%;
          height: 80%;
          overflow: hidden;
        }

        .layer_popup .popup_wrap .section_02::before { 
          content: none;
        }

        .layer_popup .sections.view{
          overflow: auto;
          height: 100%;
          min-height:0;
          margin: 0;
          padding-bottom: 0px;
          background: none;
        }

        .layer_popup .sections.view .width_con{
          height: 100%;
          padding: 0;
        }

        .layer_popup .sections.view .view_con{
          min-height: 100%;
          display: flex;
          flex-direction: column;
          overflow: auto;
        }

        .layer_popup .sections.view .row4{
          flex: 1;
          min-height: 0;
          overflow-y: auto;
          overflow-x: hidden;
        }

        .layer_popup .view_con .row4 p{
          white-space: normal;
          word-break: break-word;
          overflow-wrap: break-word;
        }

        .layer_popup .sections.view .row5{
          margin: 0;
        }
        .sections.view .view_con .row5 .btns {
            margin: 0;
        }

        @media screen and (max-width:1200px){
          .sections {}
          .width_con {width: 100%; padding: 0 10px;}
          
          #header .width_con {padding: 0 0 0 10px;}
          .App #header .width_con .nav_con {display: none;}
          .App .mob_nav {}
          .App.open_nav .mob_nav {right: 0;}
          .App.open_nav:before {position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; content: ''; background: rgba(0,0,0,.35); z-index: 9998;}
          #header .width_con .mob_con {display: flex;}

          .section_02 .board_con ul li {width: calc(33.3333% - 20px);}

          .section_02 .width_con .map_con {flex-direction: column; justify-content: flex-start;}
          .section_02 .width_con .map_con .col {width: 100%; margin: 10px 0;}
          .section_02 .width_con .map_con .col ul {margin: 20px 0 0 0;}
          .section_02 .width_con .map_con .col .link_con img {}

        }

        @media screen and (max-width:960px){
          body {font-size: 0.8rem; line-height: 1.2rem;}
          p {font-size: 0.8rem; line-height: 1.2rem;}
          h1 {font-size: 1.8rem; line-height: 2.2rem;}
          h2 {font-size: 1.6rem; line-height: 2rem;}
          h3 {font-size: 1.4rem; line-height: 1.8rem;}
          h4 {font-size: 1.3rem; line-height: 1.7rem;}
          h5 {font-size: 1rem; line-height: 1.4rem;}
          h6 {font-size: 0.8rem; line-height: 1.2rem;}
        
        }

        @media screen and (max-width:900px){

          .sections {margin: 70px 0 0 0;}

          #header {height: 70px;}

          .section_01 .slick-prev {left: 15px !important;}
          .section_01 .slick-next {right: 15px !important;}
          .section_01 .slick-prev:before, .section_01 .slick-next:before {width: 30px; height: 30px;}
          .section_01 .title_con {width: 80%;}

          .section_02 .board_con ul.vod_list li,
          .section_02 .board_con ul li {width: calc(50% - 30px); margin: 0 30px 30px 0;}

        }
        @media screen and (max-width:520px){
          body {font-size: 0.7rem; line-height: 1.1rem;}
          p {font-size: 0.7rem; line-height: 1.1rem;}
          h1 {font-size: 1.5rem; line-height: 1.9rem;}
          h2 {font-size: 1.4rem; line-height: 1.8rem;}
          h3 {font-size: 1.3rem; line-height: 1.7rem;}
          h4 {font-size: 1.2rem; line-height: 1.7rem;}
          h5 {font-size: 1rem; line-height: 1.4rem;}
          h6 {font-size: 0.8rem; line-height: 1.2rem;}

          .sections {margin: 60px 0 0 0;}
          .sections.view {margin: 0px 0 0 0;}

          #header {height: 60px;}
          #header .width_con .logo_con {padding: 12px 0;}

          .footer_con {}
          .footer_con .width_con .tit {flex-direction: column; align-items: flex-start;}
          .footer_con .width_con .tit p {display: none;}
          .footer_con.folding .width_con .tit p {display: block;}
          .footer_con .bottom_btn_con {width: 100%;}
          .footer_con .bottom_btn_con .inner > div {width: 50%; border-radius: 0;}


          .section_01 .title_con {top: 40%;}

          .sections.slide .slick-prev {left: calc(50% - 100px);}
          .sections.slide .slick-next {right: calc(50% - 100px);}

          .section_02 .width_con .main_con .btn_more {top: 10px; transform: translateY(0); font-size: 0.7rem; line-height: 1.1rem;}

          .section_02 .board_con ul.vod_list li,
          .section_02 .board_con ul li {width: 100%; margin: 0 0 10px 0;}
          .section_02 .board_con ul li .desc_con {max-height: 195px;}
          .section_02 .board_con ul.vod_list li .desc_con {max-height: 220px;}

          .section_02 .width_con .map_con {padding: 30px 0 0 0;}

          .section_03 .slide_con ul li {height: 350px;}
        
        }
        @media screen and (max-width:300px){}
    </style>
</head>
<body>
  <div class="App">

    <!-- 공지&이벤트 레이어 팝업 section -->
    <div class="layer_popup">
      <div class="dim"></div>

      <div class="popup_wrap">
        
        <div class="sections section_02 view">
          <div class="width_con">
              <div class="view_con">

                <div class="row row1">
                  <div class="board_type notice">
                    <span>공지</span>
                  </div>
                </div>

                <div class="row row2">
                  <div class="title_con">
                    <h3>돈많이 벌꺼야</h3>
                  </div>
                </div>

                <div class="row row3">
                  <div class="view_info">
                    <div class="col col1">
                      <span class="writer">foray</span>
                      <span class="date">2026-06-30 06:20:34</span>
                    </div>
                  </div>
                </div>

                <div class="row row4">
                  <div>
                    <p>월 2000만원만</p>
                  </div>
                </div>

                <div class="row row5">
                  <div class="btn_con">
                    <div class="btns exit">
                      <span>돌아가기</span>
                    </div>
                  </div>
                </div>

              </div>
          </div>
        </div>

      </div>
    </div>
    <!-- //공지&이벤트 레이어 팝업 section -->

    <!-- 이용후기 레이어팝업 section -->
    <div class="layer_popup">
      <div class="dim"></div>

      <div class="popup_wrap">
        
        <div class="sections section_02 view">
          <div class="width_con">
            <div class="view_con">

              <div class="row row1">
                <div class="board_type review">
                  <span>이용후기</span>
                </div>
              </div>

              <div class="row row2">
                <div class="title_con">
                  <h3>이용후기1</h3>
                </div>

                <div class="score_con">
                  <h5>리뷰평점</h5>
                  <div>
                    <img src="<?= RS_IMG ?>/review/star.png" alt="별점">
                    <img src="<?= RS_IMG ?>/review/star.png" alt="별점">
                    <img src="<?= RS_IMG ?>/review/star.png" alt="별점">
                    <img src="<?= RS_IMG ?>/review/star.png" alt="별점">
                    <img src="<?= RS_IMG ?>/review/star.png" alt="별점">
                  </div>
                  <span>아주좋아요</span>
                </div>
              </div>

              <div class="row row3">
                <div class="view_info">
                  <div class="col col1">
                    <span class="writer">foray</span>
                    <span class="date">2026-07-07 09:11:34</span>
                  </div>
                </div>
              </div>

              <div class="row row4">
                <div>
                  <p>이용후기1</p>
                </div>
              </div>

              <div class="row row5">
                <div class="btn_con">
                  <div class="btns exit">
                    <span>돌아가기</span>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- //이용후기 레이어팝업 section -->

  </div>

  <script>
        $(document).ready(function () {

        $('.slider').slick({
            arrows: true,
            dots: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1
        });

        $('.slick-slider').slick({
            arrows: true,
            dots: false,
            infinite: true,
            appendArrows: '.slide_nav',
            slidesToShow: 4,
            slidesToScroll: 1,
            adaptiveHeight: true,

            responsive: [
                {
                    breakpoint: 1400,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        centerMode: false
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        centerMode: false
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true,
                        centerPadding: 'clamp(24px, 8vw, 80px)'
                    }
                },
                {
                    breakpoint: 520,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true,
                        centerPadding: '12px'
                    }
                }
            ]
        });

        $('.footer_con .tit').on('click', function () {
            let $footer = $('.footer_con');
            $footer.toggleClass('folding');
            $footer.find('.tit span').text($footer.hasClass('folding') ? '▲' : '▼');
        });

        $('.mob_con').on('click', function () {
            if ($('body').hasClass('open_nav')) {
                $('.App').removeClass('open_nav');
                $('body').removeClass('open_nav');
            } else {
                $('.App').addClass('open_nav');
                $('body').addClass('open_nav');
            }
        });

        $('.mob_nav .close_nav').on('click', function () {
            $('.App').removeClass('open_nav');
            $('body').removeClass('open_nav');
        });

        $('.App').on('click', function (e) {
          if (!$(e.target).closest('.mob_nav, .mob_con').length) {
              $(this).removeClass('open_nav');
              $('body').removeClass('open_nav');
          }
        });

        // 이용후기 리스트에서 VIEW MORE(자세히 보기) 버튼 클릭 시
        $('.board_con .view_more').on('click', function (e) {
            e.preventDefault(); // 기본 링크 이동 동작(href="#")을 방지합니다.
            $('.layer_popup').addClass('on');
        });

        // 레이어 팝업 내부의 돌아가기(exit) 버튼 클릭 시
        $('.layer_popup .btns.exit').on('click', function () {
            $('.layer_popup').removeClass('on');
        });

    });
  </script>
</body>
</html>