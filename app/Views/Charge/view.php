<!-- CSS -->
<link rel="stylesheet" href="<?= RS_CSS ?>/notice/style.css">

    <div class="App">
        <!-- section -->
        <section class="sections section_02 view">
          <div class="width_con">
              <div class="view_con">

                  <div class="row row1">
                    <div class="board_type <?=$row['board_type']??"notice"?>">
                        <span><?=$row['board_type_txt']??""?></span>
                    </div>
                  </div>

                  <div class="row row2">
                    <div class="title_con">
                        <h3><?=$row['board_title']??""?></h3>
                    </div>
                  </div>

                  <div class="row row3">
                    <div class="view_info">
                        <div class="col col1">
                        <span class="writer"><?=$row['board_writer']??""?></span>
                        <span class="date"><?=$row['board_date']??""?></span>
                        </div>
                    </div>
                  </div>

                  <div class="row row4">
                    <div>
                        <?=$row['board_content']??""?>
                    </div>
                  </div>

                  <div class="row row5">
                    <div class="btn_con">
                        <div class="btns exit">
                        <a href="/change"><span>돌아가기</span></a>
                        </div>
                    </div>
                  </div>

              </div>
          </div>
        </section>
        <!-- //section -->
    </div>
