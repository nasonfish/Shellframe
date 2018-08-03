<?php
if(!array_key_exists(0, $args)){
    echo '<h3>403 Access Denied</h3><p>Uh oh.</p>';
}
$tag = urldecode($args[0]);
$results = $handler->redis->tagged($tag);
?>
<div class="margined">
    <h1>Tag '<?=$tag?>':</h1>
<?php if(empty($results)): ?>
    <h3>No results found.</h3>
<?php else: ?>
    <?php foreach($results as $result): ?>
        <?php echo $result->show(); ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
