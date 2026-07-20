<?php
?>
<link rel="stylesheet"  href="<?= RS_CSS ?>/review/style.css">
<script  src="<?= RS_JS ?>/<?=$common['code']?>/index.js?var=<?=FRESH_TIME?>"></script>
  <div class="App">
    <!-- section -->
    <div class="sections section_02 slide">
      <div class="width_con">

        <div class="main_con">
          <h6>이용후기</h6>
          <p>저희 매장을 이용해주신 고객들의 생생후기 게시판입니다.</p>
        </div>

        <div class="board_con">
            <?echo view("/Post/unit.list.php");?>

        </div>

      </div>
    </div>
    <!-- //section -->
  </div>
