<?php if(empty($args)){
    header('Location: /404/');
}
$page = $handler->entry($args[0]);
?>
<div class="margined">
    <form method="post" action="/edit_submit.php">
        <label for="id">ID</label>
        <input id="id" name="id" type="number" value="<?=$page->getID();?>" readonly/>
        <label for="title">Title of your entry</label>
        <input id="title" type="text" name="title" value="<?=$page->getTitle();?>"/>
        <label for="text">Text of your entry</label>
        <textarea id="text" name="text"><?=$page->getRawText();?></textarea>
        <label for="tags">Tags, separated by &</label>
        <input id="tags" type="text" name="tags" value="<?=implode('&', $page->getTags())?>"/>
        <button class="button-green">Create!</button>
    </form>
</div>