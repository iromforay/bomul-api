<?php
?>
<link rel="stylesheet"  href="<?= RS_CSS ?>/map/style.css">
<script  src="<?= RS_JS ?>/notice/index.js?var=<?=FRESH_TIME?>"></script>
  <div class="App">
        <!-- section -->
    <div class="sections section_02 map">
      <div class="width_con <?=$sub['widthConClass']?>">
        <div class="map_con">

          <div class="col col1">
            <h3>오시는 길 안내</h3>

            <ul>
              <li>
                <div class="img_con">
                  <img src="<?= RS_IMG ?>/sec_02/map_address.png" alt="주소">
                </div>

                <div class="desc_con">
                  <h5>주소 및 연락처</h5>
                  <p>
                      <?=$sub['addr']?><br>
                      대표번호 : <?=$sub['tel']?>
                  </p>
                </div>
              </li>
                <?if($sub['subway_show'] == "Y"):?>
              <li>
                <div class="img_con">
                  <img src="<?= RS_IMG ?>/sec_02/map_subway.png" alt="지하철">
                </div>

                <div class="desc_con">
                  <h5>지하철 이용시</h5>
                  <p><?=$sub['subway']?></p>

                </div>
              </li>
                <?endif;?>
                <?if($sub['bus_show'] == "Y"):?>
              <li>
                <div class="img_con">
                  <img src="<?= RS_IMG ?>/sec_02/map_bus.png" alt="버스">
                </div>
                <div class="desc_con">
                  <h5>버스 이용시</h5>
                  <p><?=$sub['bus']?></p>
                </div>
              </li>
            </ul>
          </div>
            <?endif;?>
          <div class="col col2">
            <div class="link_con">
              <a href="<?=$sub['google']?>" target="_blank">
                <img src="<?=$sub['file2']?>" alt="지도">
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- //section -->
  </div>

