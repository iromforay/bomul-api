<?php
?>
<link rel="stylesheet"  href="<?= RS_CSS ?>/around/style.css">
<script  src="<?= RS_JS ?>/notice/index.js?var=<?=FRESH_TIME?>"></script>
  <div class="App">
    <!-- section -->
    <div class="sections section_03 slide">
      <div class="width_con <?=$sub['widthConClass']?>">
        <div class="main_con">
          <h6><?=$rows['name']?></h6>
            <?=($rows['title_show']=="Y"? $rows['title'] : "")?>
        </div>
      </div>
     <?if(count($img)>0) :?>
      <div class="slide_con">
        <ul class="slick-slider">
        <?foreach($img as $k=>$row){?>
          <li>
              <img src="<?=$row?>" alt="slide<?=$k?>">
          </li>
        <?}//end foreach?>
        </ul>
      </div>
     <?php endif; ?>


    </div>
    <!-- //section -->

  </div>
