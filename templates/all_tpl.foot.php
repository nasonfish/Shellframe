<?php
$page = array_key_exists('page', $_GET) ? (int) $_GET['page'] : 1;
$pages = (floor(($num = $handler->redis->all(true)) / 10)) + ($num % 10 ? 1 : 0);
?>
<div class="margined">
    <?php if($page != 1): ?>
        <a href="/all/?page=<?=$page-1?>"><button type="button button-red">Previous page</button></a>
    <?php endif; ?>
    <?php if($page < $pages): ?>
        <button type="button button-green"><a href="/all/?page=<?=$page+1?>">Next page</a></button>
    <?php endif; ?>
    <form action="/all/" method="get">
        <label for="page">
            <select id="page" name="page">
                <?php for($i = 1; $i <= $pages; $i++): // Ick. This looks bad.?>
                    <option value="<?=$i?>" <?= $i==$page ? 'selected' : ''?>>Page <?=$i?></option>
                <?php endfor; ?>
            </select>
            <button type="submit">Go!</button>
    </form>
</div>

