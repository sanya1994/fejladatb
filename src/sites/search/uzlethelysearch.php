<?php
$prefix = 'uzlethely_';
$orszag = isset($_GET[$prefix.'orszag']) && in_array($_GET[$prefix.'orszag'],$fields['uzlethely']['orszag'])? $_GET[$prefix.'orszag'] : '';

$helyesvaros = false;
if(isset($_GET[$prefix.'varos'])){
    foreach ($fields['uzlethely']['varos'] as $k => $varosok) {
        foreach($varosok as $varos){
            if($varos==$_GET[$prefix.'varos']){
                $helyesvaros = true;
                break 2;
            }
        }
    }
}

$varos = $helyesvaros ? $_GET[$prefix.'varos'] : '';

$kozternev = isset($_GET[$prefix.'kozternev']) ? $_GET[$prefix.'kozternev'] : '';
$kozterjelleg = isset($_GET[$prefix.'kozterjelleg']) ? $_GET[$prefix.'kozterjelleg'] : '';
$hazszam = isset($_GET[$prefix.'hazszam']) ? $_GET[$prefix.'hazszam'] : '';
$vasarlasok_szama_operator = isset($_GET[$prefix.'vasarlasok_szama_operator']) && in_array($_GET[$prefix.'vasarlasok_szama_operator'], array_keys($booloperators)) ? $_GET[$prefix.'vasarlasok_szama_operator'] : '';
$vasarlasok_szama = $vasarlasok_szama_operator!='' && isset($_GET[$prefix.'vasarlasok_szama']) && is_numeric($_GET[$prefix.'vasarlasok_szama']) ? $_GET[$prefix.'vasarlasok_szama'] : '';
$dolgozok_szama_operator = isset($_GET[$prefix.'dolgozok_szama_operator']) && in_array($_GET[$prefix.'dolgozok_szama_operator'], array_keys($booloperators)) ? $_GET[$prefix.'dolgozok_szama_operator'] : '';
$dolgozok_szama = $dolgozok_szama_operator!='' && isset($_GET[$prefix.'dolgozok_szama']) && is_numeric($_GET[$prefix.'dolgozok_szama']) ? $_GET[$prefix.'dolgozok_szama'] : '';

$fieldtoVariable = array(
    'orszag' => '$orszagname',
    'varos' => '$varosname',
    'kozternev' => 'data($kozter/@name)',
    'kozterjelleg' => 'data($kozter/@jelleg)',
    'hazszam' => 'data($uzlethely/@hazszam)',
    'vasarlasok_szama' => '$vasarlasokszama',
    'dolgozok_szama' => '$dolgozokszama'
);
$orderbys = array();
foreach(array('first_order','second_order','third_order') as $order){
    $myorder = isset($_GET[$order]) && isset($fieldtoVariable[$_GET[$order]]) ? $fieldtoVariable[$_GET[$order]] : '';
    $myorder_type = isset($_GET[$order.'_type']) && in_array($_GET[$order.'_type'],array('ascending','descending')) ? $_GET[$order.'_type'] : '';
    if($myorder!=''){
        $orderbys[] = $myorder.' '.$myorder_type;
    }
}

$kozterwhere =array();
if($kozternev!=''){
    $kozterwhere[] = 'fn:matches(lower-case($kozter/@name),lower-case("'.$kozternev.'"))';
}
if($kozterjelleg!=''){
    $kozterwhere[] = 'fn:matches(lower-case($kozter/@jelleg),lower-case("'.$kozterjelleg.'"))';
}

$xql =
'for $orszag in /uzletlanc/uzlethelysegek/'.($orszag!='' ? $orszag : '*').'
    let $orszagname:= name($orszag)
    for $varos in $orszag/'.($varos!='' ? $varos : '*').'
        let $varosname:= name($varos)
        for $kozter in $varos/*';
        if(!empty($kozterwhere)){
            $xql.='
            where '.implode(' and ',$kozterwhere);
        }
        $xql.='
            for $uzlethely in $kozter/*';
        if($hazszam!=''){
            $xql.='
                where $uzlethely/@hazszam = '.$hazszam;
        }
$xql.='
                for $vasarlasok in /uzletlanc/vasarlasok/vasarlasi_uzlethelyseg
                    where $vasarlasok/@id=$uzlethely/@id
                    let $vasarlasokszama := count($vasarlasok/penztaros/vasarlasi_ev/vasarlas)';
        if($vasarlasok_szama!=''){
            $xql.='
                    where $vasarlasokszama '.$vasarlasok_szama_operator.' '.$vasarlasok_szama;
        }
$xql.='
                    for $dolgozok in /uzletlanc/dolgozok/uzlethelyseg
                        where $dolgozok/@id=$uzlethely/@id
                        let $dolgozokszama := count($dolgozok/descendant-or-self::node()/dolgozo)';
            if($dolgozok_szama!=''){
                $xql.='
                        where $dolgozokszama '.$dolgozok_szama_operator.' '.$dolgozok_szama;
            }
            if(!empty($orderbys)){
                $xql.='
                        order by '.implode(',',$orderbys);
            }
$xql.='
                        return <uzlethely id="{$uzlethely/@id}"><orszag>{$orszagname}</orszag><varos>{$varosname}</varos><kozternev>{data($kozter/@name)}</kozternev><kozterjelleg>{data($kozter/@jelleg)}</kozterjelleg><hazszam>{data($uzlethely/@hazszam)}</hazszam><vasarlasok_szama>{$vasarlasokszama}</vasarlasok_szama><dolgozok_szama>{$dolgozokszama}</dolgozok_szama></uzlethely>
';

$stmt = $conn->prepareQuery($xql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();

return $results;