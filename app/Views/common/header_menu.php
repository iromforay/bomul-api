<div id="header">
    <div class="sections section_00">
        <div class="width_con no_pad">
            <div class="row row1">
                <div class="logo">
                    <a href="<?php echo SITE; ?>"><img src="<?php echo RS_IMG; ?>/common/logo.png" /></a>
                </div>
                <div class="extra_con">
                    <ul class="login_menu">
                        <?php if(0){ ?>

                            <li>
                                <a href=" <?php echo SITE; ?>/Mypage">
                                    <span>내정보</span>
                                </a>
                                <span class="news"><i class="fa-solid fa-bell"></i></span>
                                <span class="my_news">6</span>
                                <div class="gnb_notice">
                                    <div id="notice"></div>
                                    <div class="all_notice"><span>내 알림 전체보기</span></div>
                                </div>
                            </li>

                            <li>
                                <a href="javascript:logout();">
                                    <span>로그아웃</span>
                                </a>
                            </li>
                        <?php }else{ ?>
                            <!--li>
                                <a href="javascript:login();">
                                    <span>로그인</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo SITE; ?>/User/Join">
                                    <span>회원가입</span>
                                </a>
                            </li-->
                        <?php } ?>
                    </ul>
                    <ul class="sns_list">
                        <li class="youtube">
                            <a href="https://www.youtube.com/@at-franchise" target="_blank"></a>
                        </li>
                        <li class="face">
                            <a href="https://www.facebook.com/profile.php?id=100082964276254" target="_blank"></a>
                        </li>
                        <li class="naver">
                            <a href="https://blog.naver.com/all-teaching7" target="_blank"></a>
                        </li>
                        <li class="insta">
                            <a href="https://www.instagram.com/allteachingstudy/" target="_blank"></a>
                        </li>
                        <li class="kakao">
                            <a href="http://pf.kakao.com/_UxlxgUxj" target="_blank"></a>
                        </li>
                    </ul>
                </div>
                <div class="mob_con">
                    <div class="btn_open">
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <ul class="nav">
                    <!--li><a href="<?php echo SITE; ?>/Info/intro">서비스 소개</a></li>
                    <li><a href="<?php echo SITE; ?>/Info/homepage">웹사이트 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Info/video">홍보영상 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Marketing">SNS마케팅 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Learn">이러닝 서비스</a></li>
                    <li><a href="<?php echo SITE; ?>/Tax">절세 세무기장 서비스</a></li>
                    <li><a href="<?php echo SITE; ?>/Remodel">리모델링 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Service">서비스 신청</a></li-->
                </ul>
            </div>
            <div class="row row3">
                <ul class="nav">
                    <li><a href="<?php echo SITE; ?>/Info/intro">서비스 소개</a></li>
                    <li><a href="<?php echo SITE; ?>/Info/homepage">웹사이트 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Info/video">홍보영상 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Marketing">SNS마케팅 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Learn">이러닝 서비스</a></li>
                    <li><a href="<?php echo SITE; ?>/Tax">절세 세무기장 서비스</a></li>
                    <li><a href="<?php echo SITE; ?>/Remodel">리모델링 지원안내</a></li>
                    <li><a href="<?php echo SITE; ?>/Service">서비스 신청</a></li>
                </ul>
                <div class="extra_con">
                    <ul class="sns_list">
                        <li class="naver">
                            <a href="https://blog.naver.com/all-teaching7" target="_blank"></a>
                        </li>
                        <li class="kakao">
                            <a href="http://pf.kakao.com/_UxlxgUxj" target="_blank"></a>
                        </li>
                        <li class="insta">
                            <a href="https://www.instagram.com/allteachingstudy/" target="_blank"></a>
                        </li>
                        <li class="face">
                            <a href="https://www.facebook.com/profile.php?id=100082964276254" target="_blank"></a>
                        </li>
                        <li class="youtube">
                            <a href="https://www.youtube.com/@at-franchise" target="_blank"></a>
                        </li>
                    </ul>
                    <ul class="login_menu">
                        <?php if( 0){ ?>

                        <li>
                            <a href=" <?php echo SITE; ?>/Mypage/Overview">
                                <span><i class="fa-solid fa-user-gear"></i></span>
                            </a>
                        </li>
                        -->
                            <li>
                                <a href=" <?php echo SITE; ?>/Mypage">
                                    <span>내정보</span>
                                </a>
                                <i class="fa-solid fa-bell"></i>
                                <span class="my_news">6</span>
                            </li>
                            <li>
                                <a href="javascript:logout();">
                                    <span>로그아웃</span>
                                </a>
                            </li>
                        <?php }else{ ?>
                            <li>
                                <a href="javascript:login();">
                                    <span>로그인</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo SITE; ?>/User/Join">
                                    <span>회원가입</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="h_modal"></div>
    <dov class="h_post"></dov>
</div>
