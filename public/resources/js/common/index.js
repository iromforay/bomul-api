$(document).ready(function () {

    // 1. 햄버거 버튼 클릭 토글
    $('.mob_con').on('click', function (e) {
        e.stopPropagation(); // 버튼 클릭 시 바깥 클릭 이벤트가 바로 실행되는 것 방지
        $('body').toggleClass('open_nav');
    });

    // 2. 닫기 버튼 클릭
    $('.mob_nav .close_nav').on('click', function () {
        $('body').removeClass('open_nav');
    });

    // 3. 네비게이션 외부(배경) 클릭 시 닫기
    $(document).on('click', function (e) {
        // 클릭한 요소가 .mob_nav나 .mob_con 내부가 아니라면 메뉴 닫기
        if (!$(e.target).closest('.mob_nav, .mob_con').length) {
            $('body').removeClass('open_nav');
        }
    });

    // 4. SNS 버튼(.sns_con) 클릭 시 .sns_list 노출 토글
    $('.fn_btn .sns_con').on('click', function (e) {
        e.stopPropagation();
        $(this).siblings('.sns_list').toggleClass('on');
    });

    // 5. SNS 팝업 외부 클릭 시 닫기
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.fn_btn').length) {
            $('.fn_btn .sns_list').removeClass('on');
        }
    });
});
