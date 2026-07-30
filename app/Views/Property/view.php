<!-- CSS -->
<link rel="stylesheet" href="<?= RS_CSS ?>/property/style.css?var=<?=FRESH_TIME?>">
<script  src="<?= RS_JS ?>/property/index.js?var=<?=FRESH_TIME?>"></script>

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

                <?php if (!empty($row['lat']) && !empty($row['lng'])): ?>
                <div class="map_con">
                    <h3>위치</h3>
                    <img src="http://api.bomul.co.kr/admin/map/image?lat=<?= urlencode($row['lat']) ?>&lng=<?= urlencode($row['lng']) ?>" alt="매물 위치 지도">
                </div>
                <?php endif; ?>

                <?php
                    // 부동산 표시·광고 법정 고지사항 - 중개사무소 정보. basic 테이블 값이라 $common에
                    // 이미 들어있음(BasicService::get_by_basic()가 SELECT *) - 렌더링만 추가.
                    $hasBrokerInfo = !empty($common['broker_office_name']) || !empty($common['broker_reg_no'])
                        || !empty($common['broker_ceo_name']) || !empty($common['broker_address'])
                        || !empty($common['broker_phone']);
                ?>
                <?php if ($hasBrokerInfo): ?>
                <div class="broker_info">
                    <h3>중개사무소 정보</h3>
                    <dl>
                        <?php if (!empty($common['broker_office_name'])): ?><div><dt>상호명</dt><dd><?= $common['broker_office_name'] ?></dd></div><?php endif; ?>
                        <?php if (!empty($common['broker_reg_no'])): ?><div><dt>등록번호</dt><dd><?= $common['broker_reg_no'] ?></dd></div><?php endif; ?>
                        <?php if (!empty($common['broker_ceo_name'])): ?><div><dt>대표자</dt><dd><?= $common['broker_ceo_name'] ?></dd></div><?php endif; ?>
                        <?php if (!empty($common['broker_address'])): ?><div><dt>소재지</dt><dd><?= $common['broker_address'] ?></dd></div><?php endif; ?>
                        <?php if (!empty($common['broker_phone'])): ?><div><dt>연락처</dt><dd><?= $common['broker_phone'] ?></dd></div><?php endif; ?>
                        <?php if (!empty($common['broker_agent_name'])): ?><div><dt>소속공인중개사</dt><dd><?= $common['broker_agent_name'] ?><?= !empty($common['broker_agent_phone']) ? ' ('.$common['broker_agent_phone'].')' : '' ?></dd></div><?php endif; ?>
                    </dl>
                </div>
                <?php endif; ?>

                <div class="btn_con">
                    <a href="/property">목록으로</a>
                </div>

            </div>
        </div>
    </section>
    <!-- //section -->
</div>
