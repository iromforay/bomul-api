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
</style>
<div class="wrap">
  <div class="width_con">
    <div class="hd">
      <div class="cibi txt_c"><?=$data['ci']['text']?></div>
      <div class="hd_main">
        <div class="academy txt_c"><?=$data['subimg']['text']?></div>
        <div class="allstudy txt_c"><?=$data['title']['text']?></div>
      </div>
      <div class="btn_con">
        <div class="btn_01 btns"><?=$data['custom']['text']?></div>
        <div class="btn_02 btns"><?=$data['map']['text']?></div>
      </div>
    </div>
    <div class="bd">
      <div class="introduce"><?=$data['left_wing']['text']?></div>
      <div class="notice">
        <div class="notice_hd"><?=$data['main_board']['text']?></div>
        <div class="n_con">
          <div class="conts cont1">(게시물)</div>
          <div class="conts cont2">(게시물)</div>
          <div class="conts cont3">(게시물)</div>
          <div class="conts cont4">(게시물)</div>
        </div>
      </div>
      <div class="review">
        <div class="review_hd"><?=$data['right_board']['text']?></div>
        <div class="r_con">
          <div class="conts cont1">(게시물)</div>
          <div class="conts cont2">(게시물)</div>
        </div>
      </div>
    </div>
  </div>
</div>