<?php if (empty($rows)): ?>
<div class="board_con">
    <p class="board_empty">등록된 게시물이 없습니다.</p>
</div>
<?php else: ?>
<div class="board_con">
    <ul>
        <?foreach($rows as $row){?>
                <?echo view("/Post/unit.view.php",array("row"=>$row));?>
        <?}//end foreach?>
    </ul>
</div>
<?=view("/Post/unit.paging.php", array("paging" => $paging))?>
<?php endif; ?>
