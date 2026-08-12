<?php
?>
<link rel="stylesheet"  href="<?= RS_CSS ?>/review/style.css">
<script  src="<?= RS_JS ?>/<?=$common['code']?>/index.js?var=<?=FRESH_TIME?>"></script>
  <div class="App">
    <!-- section -->
    <div class="sections section_02 slide">
      <div class="width_con <?=$sub['widthConClass']?>">

        <div class="main_con">
          <h6><?=$sub['name']?></h6>
            <?=($sub['title_show']=="Y"? $sub['title'] : "")?>
        </div>

        <div class="board_con">
            <?echo view("/Post/unit.list.php");?>

        </div>

      </div>
    </div>
    <!-- //section -->
  </div>
