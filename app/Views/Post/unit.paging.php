<?php
$now_page    = (int) ($paging['now_page'] ?? 1);
$total_page  = (int) ($paging['total_page'] ?? 1);
$block_start = (int) ($paging['block_start'] ?? 1);
$block_end   = (int) ($paging['block_end'] ?? $total_page);

?>
<?php if ($total_page > 1): ?>
<nav class="paging">
    <ul>
        <?php if ($now_page > 1): ?>
        <li class="paging_nav">
            <a href="<?= $common['base_url'] ?>?page=<?= $now_page - 1 ?>" rel="prev">이전</a>
        </li>
        <?php endif; ?>

        <?php for ($p = $block_start; $p <= $block_end; $p++): ?>
        <li class="<?= $p == $now_page ? 'on' : '' ?>">
            <?php if ($p == $now_page): ?>
            <span><?= $p ?></span>
            <?php else: ?>
            <a href="<?= $common['base_url'] ?>?page=<?= $p ?>"><?= $p ?></a>
            <?php endif; ?>
        </li>
        <?php endfor; ?>

        <?php if ($now_page < $total_page): ?>
        <li class="paging_nav">
            <a href="<?= $common['base_url'] ?>?page=<?= $now_page + 1 ?>" rel="next">다음</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
