
<script>
    function post_view(post_no)
    {
        location.href="<?=Admin_SITE?>/Post/view?post_no="+post_no;
    }
    function post_modify(post_no)
    {
        location.href="<?=Admin_SITE?>/Post/modify?post_no="+post_no;
    }
    function post_delete(post_no)
    {
        alert('구현중');
    }
    function post_write()
    {
        location.href="<?=Admin_SITE?>/Post/write?bcode=<?=$bcode?>";
    }
</script>

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
           $secret =  $val['secret']=="T" ? "<span class='secret'>" :"";
           $show = $val['status'] == 0 ? "class='table-dark'" : "";
            ?>
            <tr <?=$show?>>
                <td><?=$num--?></td>
                <td><?=$secret?> <?=$val['subject']?></td>
                <td><?=$val['name']?></td>
                <td><?=$val['write_date']?></td>
                <td>
                    <a href="javascript:post_view(<?=$val['post_no']?>)">보기</a>
                    <?if($val['status']){?>
                        <a href="javascript:post_modify(<?=$val['post_no']?>)">수정</a>
                        <a href="javascript:post_delete(<?=$val['post_no']?>)">삭제</a>
                    <? } ?>
                </td>
            </tr>
        <?php }// end foreach ?>
        </tbody>
    </table>
    <div id="pagination">
    <?=$pager_links?>
    </div>

    <div class="button_div">
        <span class="btn btn-primary" ROLE="button" onclick="javascript:post_write()">글쓰기</span>
    </div>

</div>
