<div class="marginedx2 entry">
    <h1>Tags</h1>
<?php
foreach($handler->redis->tags() as $tag){
    echo '<span style="font-size: '.($handler->redis->tagged($tag, true)*5+11).'px"><a style="color: black;" href="/tag/'.$tag.'/">' . $tag . '</a></span>&nbsp;&nbsp;';
}
?>
</div>
