<div class="margined">
	<?=$handler->entry(0)->show(false,false);?>
</div>
<h2>Recent Entries</h2>
<?php
foreach(array_slice($handler->redis->all(), 0, 10) as $entry){
    echo $handler->entry($entry)->show(true);
}
?>
