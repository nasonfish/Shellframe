<?php
include('../system/Config.php');
auth();

if(empty($_POST)){
    exit;
}
$handler->redis->edit($_POST['id'], $_POST['title'], $_POST['text'], explode('&', $_POST['tags']));
header('Location: /v/' . $_POST['id'] . '/' . strtolower(preg_replace('/[^\w]/', '-', $_POST['title'])) . '/');
