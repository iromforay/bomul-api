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

            <?php
                // 이미지는 항상 기본 배경 - bgcolor_show="Y"일 때 색상은 이미지를 대체하지 않고
                // 그 위에 반투명 오버레이로 얹힌다(has-color-overlay가 .img_con:before를 켬).
                $imgCount = !empty($img) ? count($img) : 0;
                $hasColorOverlay = (($sub['bgcolor_show'] ?? 'N') === 'Y');
            ?>
            <div class="img_con<?= $hasColorOverlay ? ' has-color-overlay' : '' ?>">
                    <?php if ($imgCount >= 2): ?>
                    <div class="bg bg-fade">
                        <?php foreach ($img as $i => $url): ?>
                        <div class="tile<?= $i === 0 ? ' active' : '' ?>" style="background-image:url('<?=$url?>');"></div>
                        <?php endforeach; ?>
                    </div>
                    <?php elseif ($imgCount === 1): ?>
                    <div class="bg" style="background-image:url('<?=$img[0]?>'); background-size:cover; background-position:center; background-repeat:no-repeat;"></div>
                    <?php else: ?>
                    <div class="bg" style="background:linear-gradient(135deg, #0F2D48 0%, #123A5E 55%, #1B4F72 100%);"></div>
                    <?php endif; ?>
            </div>

        </section>
        <!-- //section -->
    </div>
