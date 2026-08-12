$(document).ready(function () {

    $('.slider').slick({
        arrows: true,
        dots: false,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    $('.slick-slider').slick({
        arrows: true,
        dots: false,
        infinite: true,
        appendArrows: '.slide_nav',
        slidesToShow: 4,
        slidesToScroll: 1,
        adaptiveHeight: true,

        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    centerMode: false
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    centerMode: false
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerMode: true,
                    centerPadding: 'clamp(24px, 8vw, 80px)'
                }
            },
            {
                breakpoint: 520,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerMode: true,
                    centerPadding: '12px'
                }
            }
        ]
    });

    $('.footer_con .tit').on('click', function () {
        let $footer = $('.footer_con');
        $footer.toggleClass('folding');
        $footer.find('.tit span').text($footer.hasClass('folding') ? '▲' : '▼');
    });

    // 햄버거/네비게이션 토글은 common/index.js로 이동함
});