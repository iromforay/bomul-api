<link rel="stylesheet"  href="<?= RS_CSS ?>/home/style.css?var=<?=FRESH_TIME?>">
<script  src="<?= RS_JS ?>/home/index.js?var=<?=FRESH_TIME?>"></script>

    <div class="App">
        <!-- section -->
        <section class="sections section_01">

            <div class="title_con">
                <div>

                    <h5><?=$sub['title_show'] == "Y" ?$sub['title']:""?></h5>
                </div>
            </div>

            <div class="slider">
                <div class="img_con">
                    <div class="bg"
                        style="<?= !empty($img) ? "background:url('".$img."') center center / cover no-repeat;" : "background:linear-gradient(135deg, #0F2D48 0%, #123A5E 55%, #1B4F72 100%);" ?>">
                    </div>
                </div>
            </div>

        </section>
        <!-- //section -->
    </div>
