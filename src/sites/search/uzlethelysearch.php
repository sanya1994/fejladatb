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

$kozterwhere =array();
if($kozternev!=''){
    $kozterwhere[] = '$kozter/@name = '.$kozternev;
}
if($kozterjelleg!=''){
    $kozterwhere[] = '$kozter/@jelleg = '.$kozterjelleg;
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
$xql.='
                        return <uzlethely id="{$uzlethely/@id}"><orszag>{$orszagname}</orszag><varos>{$varosname}</varos><koztername>{$kozter/@name}</koztername><kozterjelleg>{$kozter/@jelleg}</kozterjelleg><hazszam>{$uzlethely/@hazszam}</hazszam><vasarlasok_szama>{$vasarlasokszama}</vasarlasok_szama><dolgozok_szama>{$dolgozokszama}</dolgozok_szama></uzlethely>
';
var_dump($xql);

$stmt = $conn->prepareQuery($xql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
var_dump($results);