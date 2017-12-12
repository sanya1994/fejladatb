<?php
$prefix = 'termek_';

$alltipus = array();
$alltermeknev = array();

foreach(getAllTermek() as $kategoria){
    foreach($kategoria as $tipus => $termekek){
        $alltipus[] = $tipus;
        $alltermeknev = array_merge($alltermeknev, $termekek);
    }
}

$kategoria = isset($_GET[$prefix.'kategoria']) && in_array($_GET[$prefix.'kategoria'], array_keys(getAllTermek())) ? $_GET[$prefix.'kategoria'] : '';
$tipus = isset($_GET[$prefix.'tipus']) && in_array($_GET[$prefix.'tipus'],$alltipus) ? $_GET[$prefix.'tipus'] : '';
$termeknev = isset($_GET[$prefix.'termeknev']) && in_array($_GET[$prefix.'termeknev'],$alltermeknev) ? $_GET[$prefix.'termeknev'] : '';
$marka = isset($_GET[$prefix.'marka']) && in_array($_GET[$prefix.'marka'],getAllMarka()) ? $_GET[$prefix.'marka'] : '';
$orszag = isset($_GET[$prefix.'orszag']) && in_array($_GET[$prefix.'orszag'],$fields['termek']['orszag'])? $_GET[$prefix.'orszag'] : '';

$fieldtoVariable = array(
    'kategoria' => 'name($kategoria_adat)',
    'tipus' => 'name($tipus_adat)',
    'termeknev' => 'name($termek_adat)',
    'marka' => '$markak[@id=$markastermek/@id]/elementname/text()'
);
$orderbys = array();
foreach(array('first_order','second_order','third_order') as $order){
    $myorder = isset($_GET[$order]) && isset($fieldtoVariable[$_GET[$order]]) ? $fieldtoVariable[$_GET[$order]] : '';
    $myorder_type = isset($_GET[$order.'_type']) && in_array($_GET[$order.'_type'],array('ascending','descending')) ? $_GET[$order.'_type'] : '';
    if($myorder!=''){
        $orderbys[] = $myorder.' '.$myorder_type;
    }
}
$xql =
'let $markak := for $marka in/uzletlanc/markak/'.($marka!='' ? $marka : '*').'
    return element result {
      attribute id { $marka/@id },
      element elementname { name($marka) }
    }
let $vasarlasszam := for $termekuzletben in /uzletlanc/vasarlasok/descendant-or-self::node()/vasarolt_termek
    let $itemid_ref := $termekuzletben/@id
    group by $itemid_ref
    return element result {
      element itemid_ref { $itemid_ref },
      element numberOfitemes { count($termekuzletben) }
    }
return <results>{
for $kategoria_adat in /uzletlanc/termek_tipusok/'.($tipus!='' ? $tipus : '*').'
    for $tipus_adat in $kategoria_adat/'.($kategoria!='' ? $kategoria : '*').'
        for $termek_adat in $tipus_adat/'.($termeknev!='' ? $termeknev : '*').'
            for $termek in /uzletlanc/termekek/*
                where $termek/@id = $termek_adat/@id
                    for $markastermek in $termek/*
                        where not(empty($markak[@id=$markastermek/@id]))
                        for $tenylegestermek in $markastermek/*';
            if(!empty($orderbys)){
                $xql.='
                        order by '.implode(',',$orderbys);
            }
$xql.='
                            return <termek id="{$tenylegestermek/@id}"><ajanlott_ar>{data($tenylegestermek/@ajanlott_ar)}</ajanlott_ar><marka>{$markak[@id=$markastermek/@id]/elementname/text()}</marka><vasarlas_szam>{$vasarlasszam[itemid_ref/@id = $tenylegestermek/@id]/numberOfitemes/text()}</vasarlas_szam><termeknev>{name($termek_adat)}</termeknev><tipus>{name($tipus_adat)}</tipus><kategoria>{name($kategoria_adat)}</kategoria></termek>
}</results>';
$stmt = $conn->prepareQuery($xql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();

return $results;