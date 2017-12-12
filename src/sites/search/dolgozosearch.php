<?php

echo "Dolgozók száma: doc('db/uzlethelyisegek.xml')//count(uzletlanc/dolgozok/uzlethelyseg/munkakor/heti_munkaora//dolgozo)";

$prefix = 'dolgozo_';

$helyesmunkakor = false;
if(isset($_GET[$prefix.'munkakor'])){
    foreach ($fields['dolgozo']['munkakor'] as $k => $munkakorok) {
        foreach($munkakorok as $munkakor){
            if($munkakor==$_GET[$prefix.'munkakor']){
                $helyesmunkakor = true;
                break 2;
            }
        }
    }
}

$munkakor = $helyesmunkakor ? $_GET[$prefix.'munkakor'] : '';


        
$nem = isset($_GET[$prefix.'nem']) && in_array($_GET[$prefix.'nem'],$fields['dolgozo']['nem']) ? $_GET[$prefix.'nem'] : '';
$uzlethely_cim = isset($_GET[$prefix.'uzlethely_cim']) ? $_GET[$prefix.'uzlethely_cim'] : '';
$keresztnev = isset($_GET[$prefix.'keresztnev']) ? $_GET[$prefix.'keresztnev'] : '';
$vezeteknev = isset($_GET[$prefix.'vezeteknev'])? $_GET[$prefix.'vezeteknev'] : '';

$fieldtoVariable = array(
    'vezeteknev' => '$dolgozo/vezeteknev/text()',
    'keresztnev' => '$dolgozo/keresztnev/text()',
    'uzlethely_cim' => '$cim',
    'nem' => 'name($nem)',
    'munkakor' => '$munkakor/@nev'
);
$orderbys = array();
foreach(array('first_order','second_order','third_order') as $order){
    $myorder = isset($_GET[$order]) && isset($fieldtoVariable[$_GET[$order]]) ? $fieldtoVariable[$_GET[$order]] : '';
    $myorder_type = isset($_GET[$order.'_type']) && in_array($_GET[$order.'_type'],array('ascending','descending')) ? $_GET[$order.'_type'] : '';
    if($myorder!=''){
        $orderbys[] = $myorder.' '.$myorder_type;
    }
}

$nevwhere =array();
if($vezeteknev!=''){
    $nevwhere[] = 'fn:matches(lower-case($dolgozo/vezeteknev/text()),lower-case("'.$vezeteknev.'"))';
}
if($keresztnev!=''){
    $nevwhere[] = 'fn:matches(lower-case($dolgozo/keresztnev/text()),lower-case("'.$keresztnev.'"))';
}

$xql =
'for $orszag in /uzletlanc/uzlethelysegek/*
    for $varos in $orszag/*
        for $kozter in $varos/*
            for $uzlethely in $kozter/*
                let $cim := concat(name($orszag)," ",name($varos)," ",data($kozter/@name)," ",data($kozter/@jelleg)," ",data($uzlethely/@hazszam))
                for $ittdolgozik in /uzletlanc/dolgozok/uzlethelyseg
                    where $ittdolgozik/@id = $uzlethely/@id
                    ';
        if($uzlethely_cim!=''){
            $xql .=' and fn:matches(lower-case(text($vezeteknev)),lower-case("'.$uzlethely_cim.'"))';
        }
$xql.='
                    for $ilyenmunkakorben in $ittdolgozik/munkakor
                        for $munkakor in /uzletlanc/munkakorok/descendant-or-self::node()/munkakor
                            where $munkakor/@id =$ilyenmunkakorben/@id';
if($munkakor!=''){
    $xql.=' and $munkakor/@nev='.$munkakor;
}
$xql.='
                                for $nem in $ilyenmunkakorben/*/'.($nem=='' ? '*' : $nem).'
                                    for $dolgozo in  $nem/descendant-or-self::node()/dolgozo';
if(!empty($nevwhere)){
    $xql.='
                                        where '.implode(' and ',$nevwhere);
}
if(!empty($orderbys)){
                $xql.='
                                        order by '.implode(',',$orderbys);
            }
$xql.='
                                        return <dolgozo id="{$dolgozo/@id}"><uzlethely>{$cim}</uzlethely><munkakor>{data($munkakor/@nev)}</munkakor><nem>{name($nem)}</nem><vezeteknev>{$dolgozo/vezeteknev/text()}</vezeteknev><keresztnev>{$dolgozo/keresztnev/text()}</keresztnev></dolgozo>';

$stmt = $conn->prepareQuery($xql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();

return $results;
