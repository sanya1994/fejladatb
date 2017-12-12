<?php

$prefix = 'torzsvasarlo_';
$orszag = isset($_GET[$prefix.'orszag']) && in_array($_GET[$prefix.'orszag'],$fields['torzsvasarlo']['orszag'])? $_GET[$prefix.'orszag'] : '';

$helyesvaros = false;
if(isset($_GET[$prefix.'varos'])){
    foreach ($fields['torzsvasarlo']['varos'] as $k => $varosok) {
        foreach($varosok as $varos){
            if($varos==$_GET[$prefix.'varos']){
                $helyesvaros = true;
                break 2;
            }
        }
    }
}

$varos = $helyesvaros ? $_GET[$prefix.'varos'] : '';

$nem = isset($_GET[$prefix.'nem']) && in_array($_GET[$prefix.'nem'],$fields['torzsvasarlo']['nem']) ? $_GET[$prefix.'nem'] : '';
$keresztnev = isset($_GET[$prefix.'keresztnev']) ? $_GET[$prefix.'keresztnev'] : '';
$vezeteknev = isset($_GET[$prefix.'vezeteknev'])? $_GET[$prefix.'vezeteknev'] : '';

$kozeli_boltok_operator = isset($_GET[$prefix.'kozeli_boltok_operator']) && in_array($_GET[$prefix.'kozeli_boltok_operator'], array_keys($booloperators)) ? $_GET[$prefix.'kozeli_boltok_operator'] : '';
$kozeli_boltok = $kozeli_boltok_operator!='' && isset($_GET[$prefix.'kozeli_boltok']) && is_numeric($_GET[$prefix.'kozeli_boltok']) ? $_GET[$prefix.'kozeli_boltok'] : '';

$fieldtoVariable = array(
    'vezeteknev' => '$torzsvasarlo/vezeteknev/text()',
    'keresztnev' => '$torzsvasarlo/keresztnev/text()',
    'orszag' => 'name($orszag)',
    'varos' => 'name($varos)',
    'nem' => 'name($nem)',
    'kozeli_boltok' => '$countuzlet'
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
if($kozeli_boltok!=''){
    $nevwhere[] = '$countuzlet '.$kozeli_boltok_operator.' '.$kozeli_boltok;
}
$xql =
'for $torzsvasarlok in /uzletlanc/torzsvasarlok/*
    for $orszag in $torzsvasarlok/'.($orszag!='' ? $orszag : '*').'
        for $varos in $orszag/'.($varos!='' ? $varos : '*').'
            for $nem in $varos/*/'.($nem=='' ? '*' : $nem).'
                for $torzsvasarlo in $nem/descendant-or-self::node()/torzsvasarlo
                    let $countuzlet := for $uzletorszag in /uzletlanc/uzlethelysegek/*
                                    where name($uzletorszag) = name($orszag)
                                    for $uzletvaros in $uzletorszag/*
                                        where name($uzletvaros) = name($varos)
                                        return count($uzletvaros/descendant-or-self::node()/uzlethely)';
if(!empty($nevwhere)){
    $xql.='
                                        where '.implode(' and ',$nevwhere);
}
if(!empty($orderbys)){
                $xql.='
                                        order by '.implode(',',$orderbys);
            }
$xql.='
                
                    return <torzsvasarlo id="{$torzsvasarlo/@kartyaazonosito}"><vezeteknev>{$torzsvasarlo/vezeteknev/text()}</vezeteknev><keresztnev>{$torzsvasarlo/keresztnev/text()}</keresztnev><nem>{name($nem)}</nem><orszag>{name($orszag)}</orszag><varos>{name($varos)}</varos><kozeli_boltok>{$countuzlet}</kozeli_boltok></torzsvasarlo>
';
$stmt = $conn->prepareQuery($xql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();

return $results;