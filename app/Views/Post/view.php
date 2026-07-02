<link rel="stylesheet" href="<?=RS_CSS?>/post/style_<?=$frame['style']?>.css?var=<?=FRESH_TIME?>">
<script>
    function edit()
    {

    }
    function list()
    {
        location.href="/Post/list?bcode=<?=$bcode?>";
    }
</script>
<div id="board_header">
    <?=$frame['header']?>
</div>
<div id="board_body">
        <input type="hidden" name="bcode" value="<?=$bcode?>">
        <div class="row">
            <span class="title">제목</span>
            <span class="content"><?=$data['subject']?></span>
        </div>
        <div class="row">
            <span class="title">이름</span>
            <span class="content"><?=$data['name']?></span>
        </div>
        <div class="row">
            <span class="title">비밀글</span>
            <span class="content"><?=$data['secret']?></span>
        </div>
        <div class="row">
            <span class="title">내용</span>
            <span class="content">
                <?=$data['content']?>
            </span>
        </div>

    </form>
    <div class="button_div">
    <?php echo "<p>";
    if($button['write']) { echo "T"; }else{ echo "F";}
    echo "<BR/>"
    ?>
        <a href="#" onclick="javascript:edit()">수정</a>

        <a href="#" onclick="javascript:list()">리스트</a>
    </div>
</div>

<div id="board_footer">
    <?=$frame['footer']?>
</div>
