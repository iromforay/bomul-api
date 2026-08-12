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

            <div class="img_con">
                    <?php $imgCount = !empty($img) ? count($img) : 0; ?>
                    <?php if ($imgCount >= 2): ?>
                    <div class="bg bg-fade">
                        <?php foreach ($img as $i => $url): ?>
                        <div class="tile<?= $i === 0 ? ' active' : '' ?>" style="background-image:url('<?=$url?>');"></div>
                        <?php endforeach; ?>
                    </div>
                    <?php elseif ($imgCount === 1): ?>
                    <div class="bg bg-scroll">
                        <div class="scroll-track">
                            <div class="tile" style="background-image:url('<?=$img[0]?>');"></div>
                            <div class="tile" style="background-image:url('<?=$img[0]?>');"></div>
                        </div>
                    </div>
                    <?php elseif (!empty($bgcolor)): ?>
                    <?php $opacity = is_numeric($transparent ?? null) ? (100 - (float)$transparent) / 100 : 1; ?>
                    <div class="bg" style="background:<?=$bgcolor?>; opacity:<?=$opacity?>;"></div>
                    <?php else: ?>
                    <div class="bg" style="background:linear-gradient(135deg, #0F2D48 0%, #123A5E 55%, #1B4F72 100%);"></div>
                    <?php endif; ?>
            </div>

        </section>
        <!-- //section -->
    </div>
