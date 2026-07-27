<!-- CSS -->
<link rel="stylesheet" href="<?= RS_CSS ?>/property/style.css">

<style>
.property_view{padding:20px 0 60px;}
.property_view .gallery{display:grid;grid-template-columns:2fr 1fr;gap:10px;margin-bottom:24px;}
.property_view .gallery .main{aspect-ratio:4/3;background:#f2f4f7 center/cover no-repeat;border-radius:10px;}
.property_view .gallery .thumbs{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;}
.property_view .gallery .thumbs div{aspect-ratio:1/1;background:#f2f4f7 center/cover no-repeat;border-radius:8px;}
.property_view .type{display:inline-block;font-size:12px;font-weight:700;color:#006eff;background:#eaf2ff;border-radius:5px;padding:3px 10px;margin-bottom:10px;}
.property_view h1{font-size:24px;font-weight:800;margin:0 0 6px;}
.property_view .addr{font-size:14px;color:#6b7280;margin:0 0 16px;}
.property_view .price{font-size:22px;font-weight:800;color:#16181d;margin-bottom:24px;}
.property_view .info_grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px 12px;border-top:1px solid #e4e7ec;border-bottom:1px solid #e4e7ec;padding:20px 0;margin-bottom:24px;}
.property_view .info_grid dt{font-size:12px;color:#9aa1ac;margin-bottom:4px;}
.property_view .info_grid dd{font-size:14px;font-weight:700;margin:0;}
.property_view .summary{font-size:15px;font-weight:600;margin-bottom:12px;}
.property_view .desc{font-size:14px;line-height:1.7;color:#333;margin-bottom:32px;}
.property_view .btn_con{text-align:center;}
.property_view .btn_con a{display:inline-block;padding:10px 24px;border:1px solid #e4e7ec;border-radius:8px;color:#16181d;text-decoration:none;font-size:14px;}
</style>

<div class="App">
    <!-- section -->
    <section class="sections section_02 view">
        <div class="width_con">
            <div class="property_view">

                <div class="gallery">
                    <div class="main"<?= !empty($row['main_image_url']) ? " style=\"background-image:url('".$row['main_image_url']."')\"" : "" ?>></div>
                    <?php if (!empty($row['detail_images'])): ?>
                        <div class="thumbs">
                            <?php foreach (array_slice($row['detail_images'], 0, 4) as $img): ?>
                                <div style="background-image:url('<?= $img['full_url'] ?>')"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <span class="type"><?= $row['property_type_txt'] ?: $row['property_type'] ?> · <?= $row['transaction_type_txt'] ?></span>
                <h1><?= $row['property_name'] ?></h1>
                <p class="addr"><?= trim($row['addr_sido'].' '.$row['addr_sigungu'].' '.$row['addr_dong'].' '.$row['addr_road']) ?></p>

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
                                echo '월세 '.number_format($row['deposit_price']).'/'.number_format($row['monthly_rent']).'만원';
                                break;
                            default:
                                echo $row['transaction_type_txt'];
                        }
                    ?>
                    <?php if (!empty($row['maintenance_fee'])): ?>
                        <span style="font-size:13px;font-weight:400;color:#9aa1ac;"> (관리비 <?= number_format($row['maintenance_fee']) ?>만원)</span>
                    <?php endif; ?>
                </div>

                <dl class="info_grid">
                    <div><dt>공급면적</dt><dd><?= $row['supply_area'] ? $row['supply_area'].'㎡' : '-' ?></dd></div>
                    <div><dt>전용면적</dt><dd><?= $row['exclusive_area'] ? $row['exclusive_area'].'㎡' : '-' ?></dd></div>
                    <div><dt>해당층/총층</dt><dd><?= $row['current_floor'] ?>/<?= $row['total_floor'] ?>층</dd></div>
                    <div><dt>방/욕실</dt><dd><?= $row['room_count'] ?>개 / <?= $row['bathroom_count'] ?>개</dd></div>
                    <div><dt>방향</dt><dd><?= $row['direction_txt'] ?: '-' ?></dd></div>
                    <div><dt>주차</dt><dd><?= $row['parking_available']=='Y' ? ($row['parking_count'].'대 가능') : '불가' ?></dd></div>
                    <div><dt>입주가능일</dt><dd><?= $row['move_in_date'] ? date('Y.m.d', strtotime($row['move_in_date'])) : '협의' ?></dd></div>
                    <div><dt>난방</dt><dd><?= $row['heating_type_txt'] ?: '-' ?></dd></div>
                </dl>

                <?php if (!empty($row['summary'])): ?>
                    <p class="summary"><?= $row['summary'] ?></p>
                <?php endif; ?>
                <div class="desc"><?= nl2br($row['description'] ?? '') ?></div>

                <div class="btn_con">
                    <a href="/property">목록으로</a>
                </div>

            </div>
        </div>
    </section>
    <!-- //section -->
</div>
