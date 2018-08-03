<?php
include('../system/Config.php');
$page = $handler->redis->random();
header('Location: /v/' . $page->getID() . '/' . strtolower(preg_replace('/[^\w]/', '-', $page->getTitle())) . '/');
