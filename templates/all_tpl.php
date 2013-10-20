<div class="margined">
    <h1>Recent Entries</h1>
<?php
$page = array_key_exists('page', $_GET) ? $_GET['page'] : 1;
$page = (int) $page;
foreach(array_slice($handler->redis->all(), 10 * ($page-1), 10) as $entry){
    echo $handler->entry($entry)->show(true);
}
?>
</div>
