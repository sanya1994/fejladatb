<?php
include_once $site_dir.'/generatexml/functions.php';

$types = array(
    'uzlethely' => 'Üzlethely',
    'dolgozo' => 'Dolgozó',
    'torzsvasarlo' => 'Törzsvásárló',
    'termek' => 'Termék'
);

$fields = array(
    'uzlethely' => array(
        'orszag' => getAllOrszag(),
        'varos' => getAllOrszagVaros(),
        'kozternev' => 'string',
        'kozterjelleg' => 'string',
        'hazszam' => 'string',
        'vasarlasok_szama' => 'biggerint',
        'dolgozok_szama' => 'biggerint'
    ),
    'dolgozo' => array(
        'vezeteknev' => 'string',
        'keresztnev' => 'string',
        'uzlethely' => 'string',
        'nem' => array('férfi','nő'),
        'munkakor' => getAllMunkakor()
    ),
    'torzsvasarlo' => array(
        'vezeteknev' => 'string',
        'keresztnev' => 'string',
        'nem' => array('férfi','nő'),
        'orszag' => getAllOrszag(),
        'varos' => getAllOrszagVaros(),
        'kozeli_boltok' => 'biggerint',
    ),
    'termek' => array(
        'kategoria' => array_keys(getAllTermek()),
        'tipus' => getAllTermek(),
        'termeknev' => getAllTermek(),
        'marka' => getAllMarka(),
    )
);

$name = array(
    'uzlethely'=>array(
        'orszag' => 'Ország',
        'varos' => 'Város',
        'kozternev' => 'Közterület neve',
        'kozterjelleg' => 'Közterület jellege',
        'hazszam' => 'Házszám',
        'vasarlasok_szama' => 'Vásárlások száma',
        'dolgozok_szama' => 'Dolgozók száma',
    ),
    'dolgozo' => array(
        'vezeteknev' => 'Vezetéknév',
        'keresztnev' => 'Keresztnév',
        'uzlethely' => 'Munkahely címe',
        'nem' => 'Nem',
        'munkakor' => 'Munkakör'
    ),
    'torzsvasarlo' => array(
        'vezeteknev' => 'Vezetéknév',
        'keresztnev' => 'Keresztnév',
        'nem' => 'Nem',
        'orszag' => 'Ország',
        'varos' => 'Város',
        'kozeli_boltok' => 'Lakóhelyi boltok száma',
    ),
    'termek' => array(
        'kategoria' => 'Kategória',
        'tipus' => 'Típus',
        'marka' => 'Márka',
        'termeknev' => 'Név',
        'vasarlas_szam' => 'Vásárlások száma',
        'ajanlott_ar' => 'Ajánlott ár'
    )
);
$compareoperators = array(
    '<' => 'Kisebb, mint',
    '>' => 'Nagyobb, mint',
    '<=' => 'Kisebb egyenlő, mint',
    '>=' => 'Nagyobb egyenlő, mint',
    '=' => 'Egyenlő',
    '<>' => 'Nem egyenlő',
);