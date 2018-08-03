<?php

include('CustomConfig.php');

function get($key){
    global $CONFIG;
    if(isset($CONFIG[$key])){
        return $CONFIG[$key];
    }
    return "";
}

function has($key){
    global $CONFIG;
    return isset($CONFIG[$key]);
}
