<?php
include 'variables.php';

if(isset($_GET['search'])){
    if(isset($_GET['type']) && isset($types[$_GET['type']])){
        if($_GET['type']=='uzlethely'){
            include 'uzlethelysearch.php';
        }
    } else{
        setMessage('Nem adott meg típust!', 'error');
    }
}

include 'searchview.php';