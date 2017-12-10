<?php

session_start();
include_once 'config.php';

if(existSite($act_url)){
    include $actual_site_path."/index.php";
} else{
    if($act_url=="createdatabase"){
        include 'createdatabase.php';
        exit();
    } else{
        setMessage("A megtekinteni kívánt oldal nem létezik.", "error");
    }
}
include 'view.php';
$_SESSION['messages'] = serialize($messages);