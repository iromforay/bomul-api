<!-- CSS -->
<link rel="stylesheet" href="<?= RS_CSS ?>/property/style.css?var=<?=FRESH_TIME?>">
<script  src="<?= RS_JS ?>/property/index.js?var=<?=FRESH_TIME?>"></script>

<div class="App">
    <!-- section -->
    <section class="sections section_02 view">
        <div class="width_con">
            <div class="property_view">

                <?php
                    // 검색엔진이 첫 화면부터 모든 이미지를 실제 <img>(alt 포함)로 인식하도록
                    // 배경이미지(div) 대신 <img> 태그로 구성 - 캐러셀처럼 클릭/스와이프해야만
                    // DOM에 붙는 방식은 크롤러가 대표 이미지 외엔 못 볼 수 있어서 지양.
                    $galleryImages = [];
                    if (!empty($row['main_image_url'])) {
                        $galleryImages[] = $row['main_image_url'];
                    }
                    foreach ($row['detail_images'] ?? [] as $img) {
                        $galleryImages[] = $img['full_url'];
                    }
                    $galleryAlt = $row['url_slug'] ?? $row['property_name'] ?? '매물';
                ?>
                <div class="gallery">
                    <div class="main">
                        <?php if (!empty($galleryImages)): ?>
                            <img id="galleryMainImg" src="<?= $galleryImages[0] ?>" alt="<?= htmlspecialchars($galleryAlt) ?> 사진">
                        <?php else: ?>
                            <div class="no_image">이미지 없음</div>
                        <?php endif; ?>
                        <?php if (!empty($row['status_stamp_txt'])): ?>
                            <div class="status_stamp status_stamp--<?= $row['status'] ?>"><?= $row['status_stamp_txt'] ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if (count($galleryImages) > 1): ?>
                        <div class="thumbs">
                            <?php foreach ($galleryImages as $i => $url): ?>
                                <img class="<?= $i === 0 ? 'on' : '' ?>" src="<?= $url ?>" data-full="<?= $url ?>" alt="<?= htmlspecialchars($galleryAlt) ?> 사진 <?= $i + 1 ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (count($galleryImages) > 1): ?>
                <script>
                    (function () {
                        var mainImg = document.getElementById('galleryMainImg');
                        var thumbs = document.querySelectorAll('.gallery .thumbs img');
                        thumbs.forEach(function (thumb) {
                            thumb.addEventListener('click', function () {
                                mainImg.src = thumb.dataset.full;
                                thumbs.forEach(function (t) { t.classList.remove('on'); });
                                thumb.classList.add('on');
                            });
                        });
                    })();
                </script>
                <?php endif; ?>

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
                    <img src="<?= $mapScheme ?>://api.bomul.ai.kr/admin/map/image?lat=<?= urlencode($row['lat']) ?>&lng=<?= urlencode($row['lng']) ?>" alt="매물 위치 지도">
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
                    <a class="btn_list" href="/property">목록으로</a>
                    <button type="button" class="btn_consult" id="btnConsultOpen">상담신청</button>
                </div>

            </div>
        </div>
    </section>
    <!-- //section -->

    <!-- 상담신청 레이어 팝업 -->
    <div class="consult_modal" id="consultModal">
        <div class="consult_modal_dim"></div>
        <div class="consult_modal_box">
            <button type="button" class="consult_modal_close" id="consultModalClose" aria-label="닫기">&times;</button>
            <h3>상담 신청</h3>
            <p class="consult_modal_desc"><?= htmlspecialchars($row['property_name'] ?? '이 매물') ?>에 대해 상담을 신청합니다.</p>
            <form id="consultForm">
                <input type="hidden" name="property_no" value="<?= (int) $row['property_no'] ?>">
                <div class="field">
                    <label for="consultName">이름</label>
                    <input type="text" id="consultName" name="visitor_name" required placeholder="이름을 입력하세요">
                </div>
                <div class="field">
                    <label for="consultPhone">연락처</label>
                    <input type="tel" id="consultPhone" name="visitor_phone" required placeholder="010-0000-0000">
                </div>
                <div class="field">
                    <label for="consultMemo">문의 내용 (선택)</label>
                    <textarea id="consultMemo" name="memo" placeholder="궁금하신 점을 남겨주세요"></textarea>
                </div>
                <p class="consult_submit_error" id="consultError"></p>
                <button type="submit" class="consult_submit" id="consultSubmitBtn">상담 신청하기</button>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var modal = document.getElementById('consultModal');
            var openBtn = document.getElementById('btnConsultOpen');
            var closeBtn = document.getElementById('consultModalClose');
            var dim = modal.querySelector('.consult_modal_dim');
            var form = document.getElementById('consultForm');
            var submitBtn = document.getElementById('consultSubmitBtn');
            var errorEl = document.getElementById('consultError');

            function openModal() { modal.classList.add('on'); }
            function closeModal() { modal.classList.remove('on'); errorEl.textContent = ''; }

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            dim.addEventListener('click', closeModal);

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                errorEl.textContent = '';
                submitBtn.disabled = true;
                submitBtn.textContent = '접수 중...';

                fetch('/property/saveNote', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form)
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.SUCCESS === 'TRUE') {
                            alert('상담 신청이 접수되었습니다.');
                            closeModal();
                            form.reset();
                        } else {
                            errorEl.textContent = data.MSG || '상담 신청에 실패했습니다. 다시 시도해주세요.';
                        }
                    })
                    .catch(function () {
                        errorEl.textContent = '네트워크 오류로 접수하지 못했습니다. 다시 시도해주세요.';
                    })
                    .finally(function () {
                        submitBtn.disabled = false;
                        submitBtn.textContent = '상담 신청하기';
                    });
            });
        })();
    </script>
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
