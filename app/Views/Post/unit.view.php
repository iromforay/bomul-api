<?php
$slug = preg_replace('/[\/\?\#\s]+/u', '-', trim($row['board_title'] ?? ''));
$slug = trim($slug, '-');
$view_url = '/'.$common['code'].'/view/'.($row['board_no']??0).($slug !== '' ? '/'.$slug : '');
?>
<li>
    <div class="inner">
        <h5 class="<?=$row['board_type']??"notice"?>">
            <span><?=$row['board_type_txt']??""?></span>
        </h5>

        <h3 class="board_title"><?=$row['board_title']??""?></h3>

        <div class="desc_con">
            <div>
                <?=$row['board_content']??""?>
            </div>
        </div>
    </div>

    <div class="btn_con">
        <a href="<?=$view_url?>" class="view_more btn01" title="게시글 자세히 보기">VIEW MORE</a>
        <a href="<?=$view_url?>" class="view_more" title="게시글 자세히 보기">
            <img src="<?= RS_IMG ?>/sec_02/next.png" alt="게시글 자세히 보기">
        </a>
    </div>
</li>