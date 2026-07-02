<link rel="stylesheet" href="<?=RS_CSS?>/post/style_<?=$frame['style']?>.css?var=20230622061341">
<script>
    function edit()
    {
        document.board_form.submit();
    }
</script>
<div id="board_header">
    <?=$frame['header']?>
</div>
<div id="board_body">
    <form name="board_form" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="bcode" value="<?=$data['bcode']?>">
        <input type="hiddens" name="token"  value="<?=$csrf?>"/>
        <div class="row">
            <span class="title">제목</span>
            <span class="content"><input type="text" name="title" value="<?=$data['subject']?>"></span>
        </div>
        <div class="row">
            <span class="title">이름</span>
            <span class="content"><input type="text" name="name" value="<?=$data['name']?>"></span>
        </div>
        <div class="row">
            <span class="title">비밀글</span>
            <span class="content"><input type="checkbox" name="secret" value="T" <?=$data['secret']=="T"? "checked":""?>></span>
        </div>
        <div class="row">
            <span class="title">패스워드</span>
            <span class="content"><input type="password" name="password" value=""></span>
        </div>
        <div class="row">
            <span class="title">내용</span>
            <span class="content">
                <textarea name="content"><?=$data['content']?></textarea>
            </span>
        </div>

    </form>
    <div class="button_div">
        <?if($button['write']){?>
            <a href="#" onclick="javascript:save()">등록</a>
        <?}?>
        <a href="#" onclick="javascript:cancel()">취소</a>
    </div>
</div>

<div id="board_footer">
    <?=$frame['footer']?>
</div>