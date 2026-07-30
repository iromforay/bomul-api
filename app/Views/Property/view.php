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
                <?php // title(set_title()가 url_slug로 채움)과 핵심 키워드를 맞춰야 검색엔진이
                      // 페이지 주제를 일관되게 인식한다 - h1도 같은 slug 기준, 매물명은 부제로. ?>
                <h1><?= $row['url_slug'] ?? $row['property_name'] ?></h1>
                <?php if (!empty($row['property_name'])): ?>
                    <p class="property_name_sub"><?= $row['property_name'] ?></p>
                <?php endif; ?>
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
                <div class="property_map">
                    <h3>위치</h3>
                    <?php $mapScheme = (($_SERVER['HTTPS'] ?? 'off') !== 'off') ? 'https' : 'http'; ?>
                    <img src="<?= $mapScheme ?>://api.bomul.co.kr/admin/map/image?lat=<?= urlencode($row['lat']) ?>&lng=<?= urlencode($row['lng']) ?>" alt="매물 위치 지도">
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
                    <dl class="info_grid">
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

<?php
    // 검색엔진 리치스니펫용 구조화 데이터(schema.org RealEstateListing). 매물 상세페이지 전용이라
    // 공용 header.php가 아니라 여기(Property/view.php)에 둔다.
    $scheme = (($_SERVER['HTTPS'] ?? 'off') !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $slugPath = !empty($row['url_slug']) ? '/' . rawurlencode($row['url_slug']) : '';
    $canonicalPropertyUrl = $scheme . '://' . $host . '/property/view/' . $row['property_no'] . $slugPath;

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateListing',
        // title/h1과 동일 기준(url_slug)으로 통일 - 구조화 데이터의 name이 실제 h1/title과
        // 다르면 검색엔진이 불일치로 보고 리치스니펫 신뢰도를 낮출 수 있다.
        'name' => $row['url_slug'] ?? $row['property_name'],
        'description' => !empty($row['summary']) ? $row['summary'] : strip_tags($row['description'] ?? ''),
        'url' => $canonicalPropertyUrl,
        'address' => [
            '@type' => 'PostalAddress',
            'addressRegion' => $row['addr_sido'] ?? '',
            'addressLocality' => trim(($row['addr_sigungu'] ?? '') . ' ' . ($row['addr_dong'] ?? '')),
            'streetAddress' => $row['addr_road'] ?? '',
        ],
    ];
    if (!empty($row['main_image_url'])) {
        $jsonLd['image'] = $row['main_image_url'];
    }
    if (!empty($row['lat']) && !empty($row['lng'])) {
        $jsonLd['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $row['lat'],
            'longitude' => $row['lng'],
        ];
    }
?>
<script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
