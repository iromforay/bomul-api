<!-- CSS -->
<link rel="stylesheet" href="<?= RS_CSS ?>/property/style.css?var=<?=FRESH_TIME?>">

<style>
/* property/style.css가 아직 없을 경우를 대비한 기본 스타일 */
.property_list{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:24px;padding:20px 0;}
.property_card{display:block;border:1px solid #e4e7ec;border-radius:10px;overflow:hidden;text-decoration:none;color:#16181d;background:#fff;transition:box-shadow .15s ease;}
.property_card:hover{box-shadow:0 4px 16px rgba(0,0,0,.08);}
.property_card .thumb{width:100%;aspect-ratio:4/3;background:#f2f4f7 center/cover no-repeat;display:flex;align-items:center;justify-content:center;color:#9aa1ac;font-size:13px;}
.property_card .body{padding:14px 16px;}
.property_card .type{display:inline-block;font-size:11px;font-weight:700;color:#006eff;background:#eaf2ff;border-radius:5px;padding:2px 8px;margin-bottom:8px;}
.property_card h3{font-size:16px;font-weight:700;margin:0 0 6px;}
.property_card .addr{font-size:12.5px;color:#6b7280;margin:0 0 10px;}
.property_card .price{font-size:15px;font-weight:800;color:#16181d;}
.property_empty{padding:60px 0;text-align:center;color:#9aa1ac;}
.property_paging{display:flex;justify-content:center;gap:6px;padding:24px 0;}
.property_paging a{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;border:1px solid #e4e7ec;border-radius:7px;color:#6b7280;text-decoration:none;font-size:13px;}
.property_paging a.on{background:#eaf2ff;color:#006eff;border-color:transparent;font-weight:700;}
</style>

<div class="App">
    <!-- section -->
    <section class="sections section_02 slide">
        <div class="width_con">

            <div class="main_con">
                <h6>매물정보</h6>
            </div>

            <?php if (empty($rows)): ?>
                <div class="property_empty">등록된 매물이 없습니다.</div>
            <?php else: ?>
                <div class="property_list">
                    <?php foreach ($rows as $row): ?>
                        <a class="property_card" href="/property/view/<?= $row['property_no'] ?>">
                            <div class="thumb"<?= !empty($row['main_image_url']) ? " style=\"background-image:url('".$row['main_image_url']."')\"" : "" ?>>
                                <?= empty($row['main_image_url']) ? '이미지 없음' : '' ?>
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
