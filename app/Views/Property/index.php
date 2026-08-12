<!-- CSS -->
<link rel="stylesheet"  href="<?= RS_CSS ?>/property/style.css?var=<?=FRESH_TIME?>">
<script  src="<?= RS_JS ?>/property/index.js?var=<?=FRESH_TIME?>"></script>

<div class="App">
    <!-- section -->
    <section class="sections section_02 slide">
        <div class="width_con <?=$sub['widthConClass']?>">

            <div class="main_con">
                <h6><?=$sub['name']?></h6>
                <?=($sub['title_show']=="Y"? $sub['title'] : "")?>
            </div>

            <?php if (empty($rows)): ?>
                <div class="property_empty">등록된 매물이 없습니다.</div>
            <?php else: ?>
                <div class="property_list">
                    <?php foreach ($rows as $row): ?>
                        <a class="property_card" href="/property/view/<?= $row['property_no'] ?>/<?= rawurlencode($row['url_slug']) ?>">
                            <div class="thumb"<?= !empty($row['main_image_url']) ? " style=\"background-image:url('".$row['main_image_url']."')\"" : "" ?>>
                                <?= empty($row['main_image_url']) ? '이미지 없음' : '' ?>
                                <div class="property_code">매물코드 <?= $row['property_no'] ?></div>
                                <?php if (!empty($row['status_stamp_txt'])): ?>
                                    <div class="status_stamp status_stamp--<?= $row['status'] ?>"><?= $row['status_stamp_txt'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="body">
                                <span class="type"><?= $row['property_type_txt'] ?: $row['property_type'] ?></span>
                                <h3><?= $row['property_name'] ?></h3>
                                <p class="addr"><?= trim($row['addr_sido'].' '.$row['addr_sigungu'].' '.$row['addr_dong']) ?></p>
                                <div class="price">
                                    <?php
                                        switch ($row['transaction_type']) {
                                            case 'sale':
                                                echo '매매 '.number_format($row['sale_price']).'만원';
                                                break;
                                            case 'jeonse':
                                                echo '전세 '.number_format($row['jeonse_price']).'만원';
                                                break;
                                            case 'monthly':
                                                echo '월세 '.number_format($row['deposit_price']).'/'.number_format($row['monthly_rent']);
                                                break;
                                            default:
                                                echo $row['transaction_type_txt'];
                                        }
                                    ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="property_paging">
                    <?php for ($p = $paging['block_start']; $p <= $paging['block_end']; $p++): ?>
                        <a class="<?= $p == $paging['now_page'] ? 'on' : '' ?>" href="?page=<?= $p ?>"><?= $p ?></a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <!-- //section -->
</div>
