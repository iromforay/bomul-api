<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=0">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php
// 페이지별 title/description은 각 Web 컨트롤러가 set_title()/set_description()으로 채운다
// (예: Property::getview()가 매물 요약을 description으로 넣음). 둘 다 안 채워졌으면
// site_name/company_name 기반 기본값으로 폴백한다 - 빈 문자열로 나가는 것보단 낫다.
$pageTitle = $common['title'] ?? ($common['site_name'] ?? '');
$pageDescription = $common['description'] ?? '';
if ($pageDescription === '') {
    $pageDescription = ($common['company_name'] ?? $common['site_name'] ?? '') . ' 홈페이지입니다.';
}
$currentUrl = (($_SERVER['HTTPS'] ?? 'off') !== 'off' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
$ogImage = 'http://' . ($_SERVER['HTTP_HOST'] ?? '') . '/favicon/og.jpg';
?>
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="<?=htmlspecialchars($pageDescription, ENT_QUOTES)?>">

<meta property="og:type" content="website">
<meta property="og:title" content="<?=htmlspecialchars($pageTitle, ENT_QUOTES)?>">
<meta property="og:description" content="<?=htmlspecialchars($pageDescription, ENT_QUOTES)?>">
<meta property="og:image" content="<?=htmlspecialchars($ogImage, ENT_QUOTES)?>">
<meta property="og:url" content="<?=htmlspecialchars($currentUrl, ENT_QUOTES)?>">

<title><?=$common['title']?></title>
<style>

</style>
<link rel="stylesheet" href="<?=RS_CSS?>/common/style.css?var=<?=FRESH_TIME?>" >

<script src="<?=RS_JS?>/common/index.js?var=<?=FRESH_TIME?>"></script>

<!-- Font Awesome :: Start -->
<!--<link rel="stylesheet" href="<?php /*=RS_TH*/?>/fontawesome_free_6_1_1/css/all.css" />-->
<!--<script type="text/javascript" src="<?php /*=RS_TH*/?>/fontawesome_free_6_1_1/js/all.js"></script>-->
<!-- Font Awesome :: End -->

<!-- Slick CSS -->
<link rel="stylesheet" href="<?= RS_CSS ?>/slick.css">
<link rel="stylesheet" href="<?= RS_CSS ?>/slick-theme.css">

<!-- jQuery -->
<script src="<?=RS_TH?>/jquery3.7/jquery-3.7.0.min.js"></script>


<!-- Slick JS -->
<script src="<?= RS_JS ?>/slick.min.js"></script>