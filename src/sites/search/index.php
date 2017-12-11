<?php
include 'variables.php';

if(isset($_GET['search'])){
    if(isset($_GET['type']) && isset($types[$_GET['type']])){
        if($_GET['type']=='uzlethely'){
            $results = include 'uzlethelysearch.php';
            $columns = array(
                'orszag' => 'Ország',
                'varos' => 'Város',
                'kozternev' => 'Közterület neve',
                'kozterjellege' => 'Közterület jellege',
                'hazszam' => 'Házszám',
                'vasarlasok_szama' => 'Vásárlások száma',
                'dolgozok_szama' => 'Dolgozók száma'
            );
        }
        include 'resultview.php';
        return;
    } else{
        setMessage('Nem adott meg típust!', 'error');
    }
}

include 'searchview.php';