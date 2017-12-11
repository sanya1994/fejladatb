<?php
include_once $site_dir.'/generatexml/functions.php';

$types = array(
    'uzlethely' => 'Üzlethely',
    'dolgozo' => 'Dolgozó',
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
    )
);

$booloperators = array(
    '<' => 'Kisebb, mint',
    '>' => 'Nagyobb, mint',
    '<=' => 'Kisebb egyenlő, mint',
    '>=' => 'Nagyobb egyenlő, mint',
    '=' => 'Egyenlő',
    '<>' => 'Nem egyenlő',
);