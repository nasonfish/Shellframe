<?php
global $_CONFIG;
$_CONFIG = array();

$_CONFIG['index:main-header'] = 'Welcome to Shellframe';
$_CONFIG['index:sub-header'] = 'Shellframe is a simple and small framework for small projects.';

$_CONFIG['main:title'] = 'Shellframe Framework by nasonfish';

include('../libs/Handler.class.php');
global $handler;
$handler = new Handler;
