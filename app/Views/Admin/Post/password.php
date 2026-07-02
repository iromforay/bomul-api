<link rel="stylesheet" href="<?=RS_CSS?>/post/style_<?=$frame['style']?>.css?var=20230622061341">
<script>
    function submit()
    {
        //document.post_form.action="/Post/view";
        document.post_form.submit();
    }
</script>
<div id="password_box">
    <form name="post_form" method="post" action="">
        <p class="info"> 패스워를 입력해주세요.</p>
        <input type="password" name="password" value="">
        <input type="hidden" name="post_no" value="<?=$post_no?>">
        <input type="hidden" name="mode" value="password">
        <a href="#" onclick="javascript:submit()">확인</a>
    </form>
</div>