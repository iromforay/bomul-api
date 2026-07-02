
<script>
    function edit()
    {
        location.href="<?=Admin_SITE?>/Post/modify?post_no=<?=$data['post_no']?>";
    }
    function list()
    {
        location.href="<?=Admin_SITE?>/Post/list?bcode=<?=$bcode?>";
    }
</script>

<table class="table table-hover table-dark">
    <tbody>
        <input type="hidden" name="bcode" value="<?=$bcode?>">
        <tr>
            <th scope="row" >제목</th>
             <td class="table-primary" type="text"><?=$data['subject']?></td>
        </tr>
        <tr>
            <th scope="row" >이름</th>
            <td class="table-primary" type="text"><?=$data['name']?></td>
        </tr>
        <?if($is_secret){?>
        <tr>
            <th scope="row" >비밀글</th>
            <td class="table-primary" type="text"><?=$data['secret']=="Y" ? "비밀글": "일반글"?></td>
        </tr>
        <?}?>
        <tr>
            <th scope="row" >내용</th>
            <td class="table-primary" type="text">
                <?=$data['content']?>
            </td>
        </tr>

    </form>
</table>
    <div class="button_div">
        <span class="btn btn-primary" onclick="edit()">수정</span>
        <span class="btn btn-warning" onclick="list()">리스트</span>
    </div>

