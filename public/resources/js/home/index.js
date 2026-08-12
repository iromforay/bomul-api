$(document).ready(function () {

    // 히어로 배경 이미지 스와프 - 등록된 메인이미지가 2장 이상일 때만 동작(.bg-fade가 있을 때)
    (function () {
        var $tiles = $('.section_01 .bg-fade .tile');
        if ($tiles.length < 2) return;

        var current = 0;
        setInterval(function () {
            $tiles.eq(current).removeClass('active');
            current = (current + 1) % $tiles.length;
            $tiles.eq(current).addClass('active');
        }, 5000);
    })();

    $('.slider').slick({
        arrows: true,
        dots: false,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    //   $('.slick-slider').slick({
    //       arrows: true,
    //       dots: false,
    //       infinite: true,
    //       appendArrows: '.slide_nav',
    //       slidesToShow: 4,
    //       slidesToScroll: 1,
    //       adaptiveHeight: true,

    //       responsive: [
    //           {
    //               breakpoint: 1400,
    //               settings: {
    //                   slidesToShow: 3,
    //                   slidesToScroll: 1,
    //                   centerMode: false
    //               }
    //           },
    //           {
    //               breakpoint: 1024,
    //               settings: {
    //                   slidesToShow: 2,
    //                   slidesToScroll: 1,
    //                   centerMode: false
    //               }
    //           },
    //           {
    //               breakpoint: 768,
    //               settings: {
    //                   slidesToShow: 1,
    //                   slidesToScroll: 1,
    //                   centerMode: true,
    //                   centerPadding: 'clamp(24px, 8vw, 80px)'
    //               }
    //           },
    //           {
    //               breakpoint: 520,
    //               settings: {
    //                   slidesToShow: 1,
    //                   slidesToScroll: 1,
    //                   centerMode: true,
    //                   centerPadding: '12px'
    //               }
    //           }
    //       ]
    //   });

    $('.footer_con .tit').on('click', function () {
        let $footer = $('.footer_con');
        $footer.toggleClass('folding');
        $footer.find('.tit span').text($footer.hasClass('folding') ? '▲' : '▼');
    });

    // 햄버거/네비게이션 토글은 common/index.js로 이동함

    // 1. SNS 버튼(.sns_con) 클릭 시 .sns_list 노출 토글
    $('.fn_btn .sns_con').on('click', function (e) {
        e.stopPropagation();
        $(this).siblings('.sns_list').toggleClass('on');
    });

    // 2. SNS 팝업 외부 클릭 시 닫기
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.fn_btn').length) {
            $('.fn_btn .sns_list').removeClass('on');
        }
    });

    // 이용후기 리스트에서 VIEW MORE(자세히 보기) 버튼 클릭 시
    $('.board_con .view_more').on('click', function (e) {
        e.preventDefault(); // 기본 링크 이동 동작(href="#")을 방지합니다.
        $('.layer_popup').addClass('on');
    });

    // 레이어 팝업 내부의 돌아가기(exit) 버튼 클릭 시
    $('.layer_popup .btns.exit').on('click', function () {
        $('.layer_popup').removeClass('on');
    });

});