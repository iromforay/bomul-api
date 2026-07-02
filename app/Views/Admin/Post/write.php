<script>
    function save()
    {
        document.board_form.submit();
    }
    function cancel()
    {
        location.href="<?=Admin_SITE?>/Post/list?bcode=<?=$bcode?>";
    }
</script>
<table class="table table-hover table-dark">
    <tbody>
    <form name="board_form" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="bcode" value="<?=$bcode?>">
       <tr>
            <th scope="row">제목</th>
            <td class="table-primary" type="text"><input type="text" name="title" ></td>
       </tr>
       <tr>
            <th scope="row">이름</th>
            <td class="table-primary" type="text"><input type="text" name="name" ></td>
       </tr>
        <?if($is_secret){?>
       <tr>
            <th scope="row">비밀글</th>
            <td class="table-primary" type="text"><input type="checkbox" name="secret" value="T" ></td>
       </tr>
       <tr>
            <th scope="row">패스워드</th>
            <td class="table-primary" type="text"><input type="password" name="password" value="" ></td>
       </tr>
        <?}?>
       <tr>
            <th scope="row">내용</th>
            <td class="table-primary" type="text">
                <textarea name="content"></textarea>
            </td>
       </tr>

    </form>
</table>
    <div class="button_div">
        <span class="btn btn-primary" role="button" onclick="save()">등록</span>
        <span class="btn btn-warning" role="button" onclick="cancel()">취소</span>
   </tr>
</div>