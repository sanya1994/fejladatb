<?php

$uzletekszamaxql= 'count(/uzletlanc/descendant-or-self::node()/uzlethely)';
$varosuzletszamxql =
'<results>{for $varos in /uzletlanc/uzlethelysegek/*/*
    return <result name="{name($varos)}" count="{count($varos/descendant-or-self::node()/uzlethely)}"/>}</results>';

$torzsvasarloxql= 'count(/uzletlanc/descendant-or-self::node()/torzsvasarlo)';
$torzsvasarlokedvezmenyxql =
'<results>{for $kedvezmenytipus in /uzletlanc/torzsvasarlok/kedvezmeny_tipusa
    return <result name="{data($kedvezmenytipus/@tipus)}" count="{count($kedvezmenytipus/descendant-or-self::node()/torzsvasarlo)}"/>}</results>';

$avgfizu =
'<results>{for $munkakor in /uzletlanc/munkakorok/*/*
    for $dolgozo_munkakor in /uzletlanc/dolgozok/uzlethelyseg/munkakor
        where $dolgozo_munkakor/@id = $munkakor/@id
        group by $a := $munkakor/@id
        return <result name="{$munkakor/@nev}" avg="{format-number(avg($dolgozo_munkakor/descendant-or-self::node()/fizetes/text()),\'#,##0.00\')}"></result>}</results>';

$kategoriankentavg =
'<results>{for $munkakorkat in /uzletlanc/munkakorok/*
    for $munkakor in $munkakorkat/*
    for $dolgozo_munkakor in /uzletlanc/dolgozok/uzlethelyseg/munkakor
        where $dolgozo_munkakor/@id = $munkakor/@id
        group by $a := $munkakorkat/@nev
        return <result name="{$munkakorkat/@nev}" avg="{format-number(avg($dolgozo_munkakor/descendant-or-self::node()/fizetes/text()),\'#,##0.00\')}"></result>}</results>';

$teljesavg ='format-number(avg(/uzletlanc/descendant-or-self::node()/dolgozo/fizetes/text()),"#,##0.00")';

$vasarlasok_szama_evenkent = 
'<vasarlarlasok>{for $fizetendo in //vasarlasok//vasarlas//fizetendo
group by $vasarlas := $fizetendo/../../@ev
order by count($fizetendo) descending
return <vasarlas ev = "{$vasarlas}" db = "{count($fizetendo)}" />}</vasarlarlasok>';

$vasarlasok_ertekenek_osszege_evenkent = 
'<vasarlarlasok>{for $fizetendo in //vasarlasok//vasarlas//fizetendo
group by $vasarlas := $fizetendo/../../@ev
order by count($fizetendo) descending
return <vasarlas ev = "{$vasarlas}" osszeg = "{sum($fizetendo)}" />}</vasarlarlasok>';

$vasarlasok_ertekenek_osszege_uzletenkent =
'<vasarlarlasok>{for $fizetendo in //vasarlasok//vasarlas//fizetendo
group by $uzlet := $fizetendo/../../../../@id
order by number(sum($fizetendo)) descending
return <vasarlas uzlet = "{$uzlet}" osszeg = "{sum($fizetendo)}" />>}</vasarlarlasok>';

$stmt = $conn->prepareQuery($uzletekszamaxql);
$resultPool = $stmt->execute();
$count = $resultPool->getAllResults();
$count=$count[0];

$stmt = $conn->prepareQuery($varosuzletszamxql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
$processedresult= simplexml_load_string($results[0]);
$varostable = '<table class="MyTable" width="100%"><thead><tr><th width="50%">Város</th><th>Darab</th></tr></thead><tbody>';
foreach($processedresult->children() as $result){
    $varostable.='<tr>';
    $varostable.='<td>'.$result->attributes()->name.'</td>';
    $varostable.='<td>'.$result->attributes()->count.'</td>';
    $varostable.='</tr>';
}

$varostable.='</tbody></table>';

$stmt = $conn->prepareQuery($teljesavg);
$resultPool = $stmt->execute();
$avg = $resultPool->getAllResults();
$avg=$avg[0];

$stmt = $conn->prepareQuery($kategoriankentavg);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
$processedresult= simplexml_load_string($results[0]);
$kategoriatable = '<table class="MyTable" width="100%"><thead><tr><th width="50%">Kategória</th><th>Átlag</th></tr></thead><tbody>';
foreach($processedresult->children() as $result){
    $kategoriatable.='<tr>';
    $kategoriatable.='<td>'.$result->attributes()->name.'</td>';
    $kategoriatable.='<td>'.$result->attributes()->avg.'</td>';
    $kategoriatable.='</tr>';
}

$kategoriatable.='</tbody></table>';

$stmt = $conn->prepareQuery($avgfizu);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
$processedresult= simplexml_load_string($results[0]);
$munkatable = '<table class="MyTable" width="100%"><thead><tr><th width="50%">Munkahely</th><th>Átlag</th></tr></thead><tbody>';
foreach($processedresult->children() as $result){
    $munkatable.='<tr>';
    $munkatable.='<td>'.$result->attributes()->name.'</td>';
    $munkatable.='<td>'.$result->attributes()->avg.'</td>';
    $munkatable.='</tr>';
}

$munkatable.='</tbody></table>';

$stmt = $conn->prepareQuery($torzsvasarloxql);
$resultPool = $stmt->execute();
$tcount = $resultPool->getAllResults();
$tcount=$tcount[0];

$stmt = $conn->prepareQuery($torzsvasarlokedvezmenyxql);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
$processedresult= simplexml_load_string($results[0]);
$ktable = '<table class="MyTable" width="100%"><thead><tr><th width="50%">Kedvezménytípus</th><th>Darab</th></tr></thead><tbody>';
foreach($processedresult->children() as $result){
    $ktable.='<tr>';
    $ktable.='<td>'.$result->attributes()->name.'</td>';
    $ktable.='<td>'.$result->attributes()->count.'</td>';
    $ktable.='</tr>';
}

$content = <<<ALMA
<div class="MyPage">
    <div class="PageTitle">Statisztika</div>
    <div class="BlocFull">Az üzletláncnak $count üzlete van. Városok szerint így néz ki csoportosítva az üzletek</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlocFull">$varostable</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlocFull">Az üzletláncnak $avg átlagos fizetése van. Munakörök és munkák szerint így van csoportosítva</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlocFull">$kategoriatable</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlocFull">$munkatable</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlocFull">Az üzletláncnak $tcount törzsvásárlója van. Kedvezménytípusok szerint így néz ki csoportosítva a törzsvásárlók</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlocFull">$ktable</div>
    <div class="BlockMiniSpacer"></div>
    <div class="BlockSpacer"></div>
    <div class="BlockEnd"></div>
</div>
ALMA;
