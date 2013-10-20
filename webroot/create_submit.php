<?php
include('../system/Config.php');
auth();

if(empty($_POST)){
    exit;
}
$id = $handler->redis->create($_POST['title'], $_POST['text'], explode('&', $_POST['tags']));
header('Location: /v/' . $id . '/');
