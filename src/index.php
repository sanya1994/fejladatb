<?php

session_start();
include_once 'config.php';

if(existSite($act_url)){
    include $actual_site_path."/index.php";
} else{
    setMessage("A megtekinteni kívánt oldal nem létezik.", "error");
}
include 'view.php';
$_SESSION['messages'] = serialize($messages);