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
                    <?php if (!empty($img)): ?>
                    <div class="bg bg-scroll">
                        <div class="scroll-track">
                            <div class="tile" style="background-image:url('<?=$img?>');"></div>
                            <div class="tile" style="background-image:url('<?=$img?>');"></div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bg" style="background:linear-gradient(135deg, #0F2D48 0%, #123A5E 55%, #1B4F72 100%);"></div>
                    <?php endif; ?>
                </div>
            </div>

        </section>
        <!-- //section -->
    </div>
