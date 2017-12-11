<?php
include 'variables.php';

if(isset($_GET['search'])){
    if(isset($_GET['type']) && isset($types[$_GET['type']])){
        $results = include $_GET['type'].'search.php';
        include 'resultview.php';
        return;
    } else{
        setMessage('Nem adott meg típust!', 'error');
    }
}

include 'searchview.php';