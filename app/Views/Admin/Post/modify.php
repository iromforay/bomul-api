
<script>
    function edit()
    {
        document.board_form.submit();
    }
    function cancel()
    {
        location.href="<?=Admin_SITE?>/Post/view?post_no=<?=$data['post_no']?>";
    }
    function list()
    {
        location.href="<?=Admin_SITE?>/Post/list?bcode=<?=$data['bcode']?>";
    }
</script>

<div id="board_body">
<table class="table table-hover table-dark">
    <tbody>
    <form name="board_form" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="bcode" value="<?=$data['bcode']?>">
        <tr>
            <th scope="row">제목</th>
            <td class="table-primary" type="text"><input type="text" name="title" value="<?=$data['subject']?>"></td>
        </tr>
        <tr>
            <th scope="row">이름</th>
            <td class="table-primary" type="text"><input type="text" name="name" value="<?=$data['name']?>"></td>
        </tr>
        <?if($is_secret){?>
        <tr>
            <th scope="row">비밀글</th>
            <td class="table-primary" type="text"><input type="checkbox" name="secret" value="T" <?=$data['secret']=="T"? "checked":""?>></td>
        </tr>
        <?}?>
        <tr>
            <th scope="row">내용</th>
            <td class="table-primary" type="text">
                <textarea name="content"><?=$data['content']?></textarea>
            </td>
        </tr>

    </form>
</table>
    <div class="button_div">
        <span class="btn btn-primary" role="button" onclick="edit()">등록</span>
        <span class="btn btn-warning" role="button" onclick="cancel()">취소</span>
        <span class="btn btn-warning" onclick="list()">리스트</span>
    </div>
