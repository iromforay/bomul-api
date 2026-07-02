<style>
    .wrap {}
    .wrap .width_con {width: 1200px; margin: 0 auto; padding: 20px 0;}
    .wrap .txt_c {text-align: center; padding: 20px 0;}
    .wrap .hd {display: flex; justify-content: space-between; align-items: center;}
    .wrap .hd .cibi {width: 20%; background: #faa;}
    .wrap .hd .cibi span {}
    .wrap .hd .hd_main {display: flex; width: 65%; background: #aaf;}
    .wrap .hd .hd_main .academy {width: 30%; background: #ffa;}
    .wrap .hd .hd_main .allstudy {width: 70%;}
    .wrap .hd .btn_con {display: flex; flex-direction: column; justify-content: space-between; align-items: center; width: 10%; }
    .wrap .hd .btn_con .btns {padding: 5px; margin: 5px 0;}
    .wrap .hd .btn_con .btns.btn_01 {background: #faf;}
    .wrap .hd .btn_con .btns.btn_02 {background: #aaf;}
    .wrap .bd {display: flex; justify-content: space-between; margin: 20px 0 0 0;}
    .wrap .bd .introduce {width: 20%; background: #dfafea; text-align: center;}
    .wrap .bd .notice {width: 50%; }
    .wrap .bd .notice .notice_hd {width: 100%; text-align: center;}
    .wrap .bd .notice .n_con {display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin: 10px 0 0 0;}
    .wrap .bd .notice .conts {background: #afa; width: calc(50% - 5px); margin: 10px 0 0 0; padding: 50px 0; text-align: center;}
    .wrap .bd .review {width: 25%;}
    .wrap .bd .review .review_hd {width: 100%; text-align: center;}
    .wrap .bd .review .r_con {display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin: 10px 0 0 0;}
    .wrap .bd .review .r_con .conts {}
    .wrap .bd .review .conts {background: #afa; width: calc(50% - 5px); margin: 10px 0 0 0; padding: 50px 0; text-align: center;}
    .wrap .bd .review {width: 25%;}
    .build{cursor: pointer;}
</style>
<script>
    $(document).ready(function (){
       $(document).on("click",".build",function (){
           const seq_no = $(this).attr("data-id");
           location.href="<?=Admin_SITE?>/Setup/detail?seq_no="+seq_no;
       });
    });
</script>
<div class="wrap">
    <div class="width_con">
        <div class="hd">
            <div class="cibi txt_c build" data-id="1"><span>CI / BI</span><br><br>(이미지 또는 텍스트)</div>
            <div class="hd_main">
                <div class="academy txt_c build" data-id="2">학원<br><br>시설 이미지 1장</div>
                <div class="allstudy txt_c build" data-id="3">올티칭 공부방 : 세상에 모든 도움을 주는 올티칭 공부방<br><br>(텍스트)</div>
            </div>
            <div class="btn_con">
                <div class="btn_01 btns build" data-id="4">상담문의</div>
                <div class="btn_02 btns build" data-id="5">찾아오시는 길</div>
            </div>
        </div>
        <div class="bd">
            <div class="introduce build" data-id="6" >소개글<br>(공통이미지)<br><br>[내용] 텍스트<br><br>상담문의<br>(공통이미지)<br><br>000-0000-0000<br><br><br>[내용]텍스트</div>
            <div class="notice">
                <div class="notice_hd build" data-id="7">공지사항 (게시판)</div>
                <div class="n_con">
                    <div class="conts cont1 " >(이미지 또는 텍스트 병행)</div>
                    <div class="conts cont2 " >(이미지 또는 텍스트 병행)</div>
                    <div class="conts cont3 " >(이미지 또는 텍스트 병행)</div>
                    <div class="conts cont4 " >(이미지 또는 텍스트 병행)</div>
                </div>
            </div>
            <div class="review">
                <div class="review_hd build" data-id="8">이용후기</div>
                <div class="r_con">
                    <div class="conts cont1 build" data-id="11">(이미지 또는 텍스트 병행)</div>
                    <div class="conts cont2 build" data-id="12">(이미지 또는 텍스트 병행)</div>
                </div>
            </div>
        </div>
    </div>
</div>