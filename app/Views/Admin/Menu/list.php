<h1>메뉴 관리</h1>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">메뉴명</th>
            <th scope="col">링크타입</th>
            <th scope="col">이미지</th>
            <th scope="col">마우스오버 이미지</th>
            <th scope="col">대/중메뉴</th>
            <th scope="col">부모메뉴</th>
            <th scope="col">기능</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $k => $val) {?>
            <tr>
                <td><?=$num--?></td>
                <td><?=$val['name']?></td>
                <td><?=$val['link_type_txt']?></td>
                <td><?=$val['img']?></td>
                <td><?=$val['img_over']?></td>
                <td><?=$val['depth']==1 ? "대메뉴":"중메뉴"?></td>
                <td><?=$val['p_menu_no']?></td>
                <td>
                    <a href="javascript:board_copy(<?=$val['menu_no']?>)">복사</a>,
                    <a href="javascript:modify(<?=$val['menu_no']?>)">수정</a>,
                    <a href="javascript:delete_board(<?=$val['menu_no']?>)">삭제</a>
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
        location.href="<?=Admin_SITE?>/Menu/write";
    }
    function modify(seq_no)
    {
        location.href="<?=Admin_SITE?>/Menu/edit?seq_no="+seq_no;
    }
    function board_copy(seq_no)
    {
        const url= "<?=Admin_SITE?>/Menu/copy";
        const objpara = "seq_no="+seq_no;
        const objData = getAjaxJson(url,objpara,"GET");
        console.log(objData);
    }
    function delete_board(seq_no)
    {
        alert('구현중입니다.');
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


