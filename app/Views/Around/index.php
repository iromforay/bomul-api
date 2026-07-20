<?php
?>
<link rel="stylesheet"  href="<?= RS_CSS ?>/around/style.css">
<script  src="<?= RS_JS ?>/notice/index.js?var=<?=FRESH_TIME?>"></script>
  <div class="App">
    <!-- section -->
    <div class="sections section_03 slide">
      <div class="width_con">
        <div class="main_con">
          <div class="slide_nav">
            <h6><?=$rows['name']?></h6>
          </div>
        </div>
      </div>

      <div class="slide_con">
        <ul class="slick-slider">
        <?foreach($img as $k=>$row){?>
          <li>
              <img src="<?=$row?>" alt="slide<?=$k?>">
          </li>
        <?}//end foreach?>
        </ul>
      </div>

      <p class="zoom">
        각 이미지들을 클릭하시면 크게 보실 수 있습니다.
      </p>
    </div>
    <!-- //section -->

  </div>
