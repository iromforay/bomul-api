<link rel="stylesheet" href="<?=RS_CSS?>/post/style_<?=$frame['style']?>.css?var=20230622061341">
<script>
    function post_view(post_no)
    {
        location.href="/Post/view?post_no="+post_no;
    }
    function post_modify(post_no)
    {
        location.href="/Post/modify?post_no="+post_no;
    }
    function post_delete(post_no)
    {
        alert('구현중');
    }
</script>
<div id="board_header">
<?=$frame['header']?>
</div>
<div id="board_body">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">제목</th>
            <th scope="col">작성자</th>
            <th scope="col">등록일</th>
            <th scope="col">기능</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $k => $val) {
           $secret =  $val['secret']=="T" ? "<span class='secret'>" :""
            ?>
            <tr>
                <td><?=$num--?></td>
                <td><?=$secret?> <?=$val['subject']?></td>
                <td><?=$val['name']?></td>
                <td><?=$val['write_date']?></td>
                <td>
                    <a href="javascript:post_view(<?=$val['post_no']?>)">보기</a>
                    <?if(isset($button['write']) and $button['write']){?>
                        <a href="javascript:post_modify(<?=$val['post_no']?>)">수정</a>
                        <a href="javascript:post_delete(<?=$val['post_no']?>)">삭제</a>
                    <?}?>
                </td>
            </tr>
        <?php }// end foreach ?>
        </tbody>
    </table>
    <div id="pagination">
    <?=$pager_links?>
    </div>

    <div class="button_div">
        <?if(isset($button['write']) and $button['write']){?>
        <a href="/Post/write?bcode=<?=$bcode?>">글쓰기</a>
        <?}?>
    </div>

</div>

<div id="board_footer">
<?=$frame['footer']?>
</div>