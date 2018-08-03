<?php
global $CONFIG;
$CONFIG = array();
date_default_timezone_set('America/Denver');

$CONFIG['index:main-header'] = 'Welcome to Shellframe';
$CONFIG['index:sub-header'] = 'Shellframe is a simple and small framework for small projects.';

$CONFIG['main:title'] = 'Daniel Barnes';

$CONFIG['author'] = 'Daniel';


include('../libs/Handler.class.php');
global $handler;
$handler = new Handler;

function auth(){
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header("WWW-Authenticate: Basic realm=\"danielbarnes.me Blog - Create entry\"");
            header("HTTP/1.0 401 Unauthorized");
            echo '401 Unauthorized - No username/password supplied.';
            exit;
        } else {
            if(!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_PW'] !== trim(file_get_contents('../pass.txt'))){
                header("WWW-Authenticate: Basic realm=\"danielbarnes.me Blog - Create Entry\"");
                header("HTTP/1.0 401 Unauthorized");
                echo "401 Unauthorized - Incorrect username/password.";
                exit;
            }
        }
}
