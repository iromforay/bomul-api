<footer class="footer_con folding">

    <div class="width_con">

        <div class="tit">
            <h5>
                <?=$common['company_name']??"회사"?>
                <span>▲</span>
            </h5>
            <p>Copyrightⓒ 2023 보이는 매물 All rights reserved.</p>
        </div>

        <p class="footer_desc">
            <?if(strlen($common['footer_copy'])>0){
                echo $common['footer_copy'];
                }else{?>
                    Copyrightⓒ 2023 보이는 매물 All rights reserved.
            <?}?>
            <br>
            Copyrightⓒ 2023 보이는 매물 All rights reserved.
        </p>

        <div class="bottom_btn_con">
            <div class="inner">
                <div class="naver_con">
                    <a href="" target="_blank">
                        <img src="<?= RS_IMG ?>/header/naver.png" alt="네이버 예약">
                        <p>예약하기</p>
                    </a>
                </div>

                <div class="kakao_con">
                    <a href="" target="_blank">
                        <img src="<?= RS_IMG ?>/header/kakao.png" alt="카카오톡 채널">
                        <p>카톡채널추가</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

</footer>