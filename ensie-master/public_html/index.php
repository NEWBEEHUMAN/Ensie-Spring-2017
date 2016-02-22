<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

if(strpos($_SERVER['REQUEST_URI'], '/index.php') !== false){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . str_replace('/index.php', '', $_SERVER['REQUEST_URI']));
} else {
    include "app_dev.php";
}
