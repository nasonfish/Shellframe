<div class="marginedx2 entry">
    <h1>Tags</h1>
<?php
foreach($handler->redis->tags() as $tag){
    echo '<span style="font-size: '.($handler->redis->tagged($tag, true)*4+20).'px"><a href="/tag/'.$tag.'/">' . $tag . '</a></span>&nbsp;&nbsp;';
}
?>
</div>