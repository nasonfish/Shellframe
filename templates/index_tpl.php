<div class="margined">
    <h2>Hey There!</h2>
    <p>I'm nasonfish, a web developer/high school student from Colorado. I make a lot of <a target="_blank" href="https://github.com/nasonfish/">small open-source projects</a> in my free time, and I figured I'd be able to ramble a lot if I had a place for it.</p>
    <p>So, you've found my blog. Have fun!</p>
    <br style="margin-top: 20px; margin-bottom: 20px"/>
    <?php
    foreach(array_slice($handler->redis->all(), 0, 5) as $entry){
        echo $handler->entry($entry)->show(true);
    }
    ?>
</div>
