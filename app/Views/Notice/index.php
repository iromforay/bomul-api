<!-- CSS -->
<link rel="stylesheet"  href="<?= RS_CSS ?>/notice/style.css?var=<?=FRESH_TIME?>">
<script  src="<?= RS_JS ?>/notice/index.js?var=<?=FRESH_TIME?>"></script>
    <div class="App">
        <!-- section -->
        <section class="sections section_02 slide">

          <div class="width_con">

              <div class="main_con">
                  <h6>공지 &amp; 이벤트</h6>

                  <ul>
                      <li class="on"><a href="<?=$common['base_url']?>?code=all">전체</a> </li>
                      <li><a href="<?=$common['base_url']?>?code=notice">공지사항</a> </li>
                      <li><a href="<?=$common['base_url']?>?code=event">이벤트</a> </li>

                  </ul>
              </div>

              <?echo view("/Post/unit.list.php");?>

          </div>

        </section>
        <!-- //section -->

    </div>

