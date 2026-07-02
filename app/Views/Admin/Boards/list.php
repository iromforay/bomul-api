<h1>게시판 관리</h1>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">게시판코드</th>
            <th scope="col">게시판명</th>
            <th scope="col">적용스타일</th>
            <th scope="col">읽기권한</th>
            <th scope="col">쓰기권한</th>
            <th scope="col">리스트권한</th>
            <th scope="col">댓글</th>
            <th scope="col">비밀글</th>
            <th scope="col">기능</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $k => $val) {?>
        <tr>
            <td><?=$num--?></td>
            <td><a href="javascript:post_view('<?=$val['bcode']?>')"><?=$val['bcode']?></a></td>
            <td><?=$val['bname']?></td>
            <td><?=$val['style']?></td>
            <td><?=$val['reader_text']?></td>
            <td><?=$val['writer_text']?></td>
            <td><?=$val['lister_text']?></td>
            <td><?=$val['reply_text']?></td>
            <td><?=$val['secret_text']?></td>
            <td>
                <a href="javascript:truncate_board(<?=$val['boards_no']?>)">비우기</a>,
                <a href="javascript:backckup_board(<?=$val['boards_no']?>)">백업</a>,
                <a href="javascript:board_copy(<?=$val['boards_no']?>)">복사</a>,
                <a href="javascript:modify(<?=$val['boards_no']?>)">수정</a>,
                <a href="javascript:delete_board(<?=$val['boards_no']?>)">삭제</a>
                <a href="javascript:view('<?=$val['bcode']?>')">보기</a>

            </td>
        </tr>
        <?php }// end foreach ?>
        </tbody>
    </table>
<?=$pager_links?>
</div>
<div class="mb-2">
<span class="btn btn-primary" ROLE="button" onclick="javascript:make()">생성</span>
</div>
<script>
    function make()
    {
        location.href="<?=Admin_SITE?>/Boards/write";
    }
    function modify(seq_no)
    {
        location.href="<?=Admin_SITE?>/Boards/edit?seq_no="+seq_no;
    }
    function board_copy(seq_no)
    {
        const url= "<?=Admin_SITE?>/Boards/copy";
        const objpara = "seq_no="+seq_no;
        const objData = getAjaxJson(url,objpara,"GET");
        console.log(objData);
    }
    function delete_board(seq_no)
    {
        alert('구현중입니다.');
    }
    function backckup_board(seq_no) {
        alert('구현중입니다.');
    }
    function truncate_board(seq_no){
        alert('구현중입니다.');
    }
    function view(code){
        const url = "<?=SITE?>Post/list?bcode="+code
        const options = "width=800,height=600,scrollbars=yes";
        window.open(url,"Board",options)
    }
    function post_view(code){
        location.href="<?=Admin_SITE?>/Post/list?bcode="+code
    }
</script>
<style>
    .pagination .active

    ul.pagination > li{
        border: 1px solid #08a1f1;
        padding:5px;
        background: #11ff00;
    }
</style>


