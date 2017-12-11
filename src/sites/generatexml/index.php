<?php
include 'functions.php';

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<uzletlanc/>');
$node_uzlethelyseg = $xml->addChild('uzlethelysegek');

$orszagvarosok = array();
for($i = rand(12,18); $i > 0; --$i){
    $orszagvarosok[] = getRandomOrszagVaros();
}

usort($orszagvarosok, function($a, $b) {
    return $a['ország'] > $b['ország'] ? 1 : ($a['ország'] == $b['ország'] ? $a['város'] > $b['város'] : -1);
});

$beforeorszag = '';
$beforevaros = '';
$uzlethelysegek = array();
$id = 0;
foreach($orszagvarosok as $orszagvaros){
    if($beforeorszag!=$orszagvaros['ország']){
        $beforeorszag = $orszagvaros['ország'];
        $node_orszag = $node_uzlethelyseg->addChild($orszagvaros['ország']);
        $beforevaros = '';
    }
    if($beforevaros!=$orszagvaros['város']){
        $node_varos = $node_orszag->addChild($orszagvaros['város']);
        $kozterhazszam = array();
        $beforevaros = $orszagvaros['város'];
    }
    do{
        $kozter = getKozter();
    }
    while(in_array($kozter['kozternev'].$kozter['kozterjelleg'],array_keys($kozterhazszam)));
    $kozterhazszam[$kozter['kozternev'].$kozter['kozterjelleg']] = array();
    $node_kozter = $node_varos->addChild('kozter');
    $node_kozter->addAttribute('name', $kozter['kozternev']);
    $node_kozter->addAttribute('jelleg', $kozter['kozterjelleg']);
    
    $rand = rand(1,1000);
    
    for($i = $rand <805 ? 1 : ($rand < 910 ? 2 : ($rand < 975 ? 3 : 4));$i>0;--$i){
        while(in_array($hazszam=rand(1,500),$kozterhazszam[$kozter['kozternev'].$kozter['kozterjelleg']]));
        $kozterhazszam[$kozter['kozternev'].$kozter['kozterjelleg']][]=$hazszam;
        $node_uzletag = $node_kozter->addChild('uzlethely');
        $node_uzletag->addAttribute('id', ++$id);
        $node_uzletag->addAttribute('hazszam', $hazszam);
        $uzlethelysegek[] = $id;
    }    
}
unset($orszagvarosok);

$munkakors = getAllMunkakor();
$node_munkakorok = $xml->addChild('munkakorok');
$munkakorok = array();
$id = 0;
foreach($munkakors as $tipus => $munkakor){
    $node_munkakorkategoria = $node_munkakorok->addChild('munkakorkategoria');
    $node_munkakorkategoria->addAttribute('nev', $tipus);
    foreach(array_unique($munkakor) as $megnevezes){
        $node_munkakor = $node_munkakorkategoria->addChild('munkakor');
        $node_munkakor->addAttribute('id', ++$id);
        $node_munkakor->addAttribute('nev', $megnevezes);
        $node_munkakor->addAttribute('varhato_fizetes', $fizetesek[$megnevezes]);
        $munkakorok[$id] = $megnevezes;
    }
}
unset($munkakors);

$node_vegzettsegek= $xml->addChild('vegzettsegek');
$vegzettsegs= getAllVegzettseg();
$vegzettsegek = array();
$id = 0;
foreach($vegzettsegs as $tipus => $vegzettseg){
    $node_vegzettsegtipus = $node_vegzettsegek->addChild('tipus');
    $node_vegzettsegtipus->addAttribute('tipus', $tipus);
    foreach(array_unique($vegzettseg) as $szint){
        $node_szint = $node_vegzettsegtipus->addChild('szint');
        $node_szint->addAttribute('id', ++$id);
        $node_szint->addAttribute('szint', $szint);
        $vegzettsegek[$tipus.'|'.$szint] = $id;
    }
}
unset($munkakors);

$dolgozos = array();
$uzlmunk = array();
$dontagain = array();
for($i=count($uzlethelysegek)*rand(10,30)+rand(-50,25);$i>0;--$i){
    $dolgozo = array();
    $dolgozo['uzlethelyseg'] = $uzlethelysegek[array_rand($uzlethelysegek)];
    if(!isset($uzlmunk[$dolgozo['uzlethelyseg']])){
        $uzlmunk[$dolgozo['uzlethelyseg']] = array();
    }

    do{
        $dolgozo['munkakor'] = array_keys($munkakorok)[array_rand(array_keys($munkakorok))];
    }while(!isDiversedArray($uzlmunk[$dolgozo['uzlethelyseg']], $dolgozo['munkakor']));
    $uzlmunk[$dolgozo['uzlethelyseg']][] = $dolgozo['munkakor'];
    
    $x = 0;
    do{
        if(++$x==100){
            break;
        }
        $dolgozo['heti_munkaora'] = getHetiOra();
        $dolgozo['nem'] = getNem();
        $dolgozo['allampolgarsag'] = getAllampolgarsag();
        $dolgozo['csaladi_statusz'] = getCsaladiAllapot();
        $dolgozo['fizetes_tipusa'] = getFizetesTipusa();
        $dolgozo['szuletesi_ev'] = getSzuletesiEv();
    } while(isset($dontagain[$dolgozo['heti_munkaora'].$dolgozo['nem'].$dolgozo['allampolgarsag'].$dolgozo['csaladi_statusz'].$dolgozo['fizetes_tipusa'].$dolgozo['szuletesi_ev']]));
    $dontagain[$dolgozo['heti_munkaora'].$dolgozo['nem'].$dolgozo['allampolgarsag'].$dolgozo['csaladi_statusz'].$dolgozo['fizetes_tipusa'].$dolgozo['szuletesi_ev']] = 'x';

    $dolgozo['vegzettsegek'] = getVegzettsegek();

    $x = 0;
    do{
        if(++$x==100){
            break;
        }
        $dolgozo['nev'] = getNev($dolgozo['nem']);
        $dolgozo['lakcim'] = getRandomOrszagVaros();
    } while(isset($dontagain[implode(',',$dolgozo['nev']).implode(',',$dolgozo['lakcim'])]));
    $dontagain[implode(',',$dolgozo['nev']).implode(',',$dolgozo['lakcim'])] = 'y';
    
    $dolgozos[] = $dolgozo;
}
unset($uzlmunk);
unset($dontagain);

usort($dolgozos, function($a,$b){
    if($a['uzlethelyseg']>$b['uzlethelyseg'] || $a['uzlethelyseg']<$b['uzlethelyseg']){
        return $a['uzlethelyseg']>$b['uzlethelyseg'] ? 1 : -1;
    }
    if($a['munkakor']>$b['munkakor'] || $a['munkakor']<$b['munkakor']){
        return $a['munkakor']>$b['munkakor'] ? 1 : -1;
    }
    if($a['heti_munkaora']>$b['heti_munkaora'] || $a['heti_munkaora']<$b['heti_munkaora']){
        return $a['heti_munkaora']>$b['heti_munkaora'] ? 1 : -1;
    }
    if($a['nem']>$b['nem'] || $a['nem']<$b['nem']){
        return $a['nem']>$b['nem'] ? 1 : -1;
    }
    if($a['allampolgarsag']>$b['allampolgarsag'] || $a['allampolgarsag']<$b['allampolgarsag']){
        return $a['allampolgarsag']>$b['allampolgarsag'] ? 1 : -1;
    }
    if($a['csaladi_statusz']>$b['csaladi_statusz'] || $a['csaladi_statusz']<$b['csaladi_statusz']){
        return $a['csaladi_statusz']>$b['csaladi_statusz'] ? 1 : -1;
    }
    if($a['fizetes_tipusa']>$b['fizetes_tipusa'] || $a['fizetes_tipusa']<$b['fizetes_tipusa']){
        return $a['fizetes_tipusa']>$b['fizetes_tipusa'] ? 1 : -1;
    }
    if($a['szuletesi_ev']>$b['szuletesi_ev'] || $a['szuletesi_ev']<$b['szuletesi_ev']){
        return $a['szuletesi_ev']>$b['szuletesi_ev'] ? 1 : -1;
    }
    return 0;
});

$node_dolgozok = $xml->addChild('dolgozok');
$beforeuzlethelyseg = '';
$id = 0;
$eladok = array();
foreach($dolgozos as $dolgozo){
    if($dolgozo['uzlethelyseg']!=$beforeuzlethelyseg){
        $node_uzlethelyseg = $node_dolgozok->addChild('uzlethelyseg');
        $node_uzlethelyseg->addAttribute('id',$dolgozo['uzlethelyseg']);
        $beforeuzlethelyseg = $dolgozo['uzlethelyseg'];
        $beforemunkakor = '';
    }
    if($dolgozo['munkakor']!=$beforemunkakor){
        $node_munkakor = $node_uzlethelyseg->addChild('munkakor');
        $node_munkakor->addAttribute('id',$dolgozo['munkakor']);
        $beforemunkakor = $dolgozo['munkakor'];
        $beforeheti_munkaora = '';
    }
    if($dolgozo['heti_munkaora']!=$beforeheti_munkaora){
        $node_heti_munkaora = $node_munkakor->addChild('heti_munkaora');
        $node_heti_munkaora->addAttribute('ora',$dolgozo['heti_munkaora']);
        $beforeheti_munkaora = $dolgozo['heti_munkaora'];
        $beforenem = '';
    }
    if($dolgozo['nem']!=$beforenem){
        $node_nem = $node_heti_munkaora->addChild($dolgozo['nem']);
        $beforenem = $dolgozo['nem'];
        $beforeallampolgarsag = '';
    }
    if($dolgozo['allampolgarsag']!=$beforeallampolgarsag){
        $node_allampolgarsag = $node_nem->addChild($dolgozo['allampolgarsag']);
        $beforeallampolgarsag = $dolgozo['allampolgarsag'];
        $beforeorszag = '';
    }
    if($dolgozo['lakcim']['ország']!=$beforeorszag){
        $node_orszag = $node_allampolgarsag->addChild($dolgozo['lakcim']['ország']);
        $beforeorszag = $dolgozo['lakcim']['ország'];
        $beforevaros = '';
    }
    if($dolgozo['lakcim']['város']!=$beforevaros){
        $node_varos = $node_orszag->addChild($dolgozo['lakcim']['város']);
        $beforevaros = $dolgozo['lakcim']['város'];
        $beforeszuletesi_ev = '';
    }
    if($dolgozo['szuletesi_ev']!=$beforeszuletesi_ev){
        $node_szuletesi_ev = $node_varos->addChild('szuletesi_ev');
        $node_szuletesi_ev->addAttribute('szuletesi_ev',$dolgozo['szuletesi_ev']);
        $beforeszuletesi_ev = $dolgozo['szuletesi_ev'];
        $beforecsaladi_statusz = '';
    }
    if($dolgozo['csaladi_statusz']!=$beforecsaladi_statusz){
        $node_csaladi_statusz = $node_szuletesi_ev->addChild($dolgozo['csaladi_statusz']);
        $beforecsaladi_statusz = $dolgozo['csaladi_statusz'];
        $beforefizetes_tipusa = '';
    }
    if($dolgozo['fizetes_tipusa']!=$beforefizetes_tipusa){
        $node_fizetes_tipusa = $node_csaladi_statusz->addChild($dolgozo['fizetes_tipusa']);
        $beforefizetes_tipusa = $dolgozo['fizetes_tipusa'];
    }
    $node_dolgozo = $node_fizetes_tipusa->addChild('dolgozo');
    $node_dolgozo->addAttribute('id',++$id);
    $node_dolgozo->addChild('vezeteknev',$dolgozo['nev']['vezetéknév']);
    $node_dolgozo->addChild('keresztnev',$dolgozo['nev']['keresztnév']);
    
    if($munkakorok[$dolgozo['munkakor']]=='eladó'){
        $eladok[$id] = $dolgozo['uzlethelyseg'];
    }
    
    $node_vegzettsegek = $node_dolgozo->addChild('vegzettsegek');
    $voltmar = array();
    foreach($dolgozo['vegzettsegek']  as $vegzettseg){
        if(isset($vegzettsegek[$vegzettseg['tipus'].'|'.$vegzettseg['szint']]) && !isset($voltmar[$vegzettsegek[$vegzettseg['tipus'].'|'.$vegzettseg['szint']]])){
            $voltmar[$vegzettsegek[$vegzettseg['tipus'].'|'.$vegzettseg['szint']]]='y';
            $node_vegzettseg = $node_vegzettsegek->addChild('vegzettseg');
            $node_vegzettseg->addAttribute('id',$vegzettsegek[$vegzettseg['tipus'].'|'.$vegzettseg['szint']]);
            $node_vegzettseg->addAttribute('vegzesi_ev',rand($dolgozo['szuletesi_ev']+18,date('Y')));
        }
    }
    unset($voltmar);
    
    $node_dolgozo->addChild('fizetes',$fizetesek[$munkakorok[$dolgozo['munkakor']]]*rand(80,120/100));
}

$node_termek_tipusok = $xml->addChild('termek_tipusok');
$termeks= getAllTermek();

$id = 0;
$termektipusok = array();
foreach($termeks as $tipuskey => $tipus){
    $node_termektipus = $node_termek_tipusok->addChild($tipuskey);
    foreach($tipus as $kategoriakey => $kategoria){
        $node_kategoriatipus = $node_termektipus->addChild($kategoriakey);
        foreach($kategoria as $termek){
            $node_termek = $node_kategoriatipus->addChild($termek);
            $node_termek->addAttribute('id',++$id);
            $termektipusok[$id] = $termek;
        }
    }
}
unset($termeks);

$markas = getAllMarka();

$id = 0;
$markak = array();
$node_markak = $xml->addChild('markak');
foreach($markas as $marka){
    $node_marka = $node_markak->addChild($marka);
    $node_marka->addAttribute('id',++$id);
    $markak[$id] = $marka;
}

unset($markas);


$termekek = array();
$id = 0;
$node_termekek = $xml->addChild('termekek');
foreach(array_keys($termektipusok) as $id){
    $node_tipus = $node_termekek->addChild('tipus');
    $node_tipus->addAttribute('id',$id);
    $voltmarka = array();
    for($i=rand(1,count($markak));$i>0;--$i){
        while(in_array($actmarka = $markak[array_rand($markak)], $voltmarka));
        $voltmarka[] = $actmarka;
        $node_marka = $node_tipus->addChild('marka');
        $node_marka->addAttribute('id', array_search($actmarka, $markak));
        $node_termek  = $node_marka->addChild('termek');
        $node_termek->addAttribute('id',++$id);
        $ar = rand(500,12000);
        $node_termek->addAttribute('ajanlott_ar',$ar*1.1);
        $node_termek->addAttribute('beszerzesi_ar',$ar);
        $termekek[$id] = $ar*1.1;
    }
}

$node_termek_uzletben = $xml->addChild('termek_uzletben');
$termekarak = array();
foreach($uzlethelysegek as $id){
    $node_uzlethelyseg = $node_termek_uzletben->addChild('uzlet');
    $node_uzlethelyseg->addAttribute('id',$id);
    $volttermekek = array();
    $voltakciok = array();
    $aktakcio = '';
    for($i= rand(5,count($termekek)%2);$i>0;--$i){
        if($aktakcio == '' || rand(1,50) ==5){
            while(in_array($aktakcio =array('mertek' => rand(1,60), 'kezdes' => rand(2014,2017).'-'.rand(1,11).'-'.rand(1,28), 'vég' => rand(2018,2085).'-'.rand(1,12).'-'.rand(1,28)),$voltakciok));
            $node_aktakcio = $node_uzlethelyseg->addChild("akcio");
            $node_aktakcio->addAttribute('mertek',$aktakcio['mertek']);
            $node_aktakcio->addAttribute('kezdete',$aktakcio['kezdes']);
            $node_aktakcio->addAttribute('befejezese',$aktakcio['vég']);
            $voltakciok[] = $aktakcio;
        }
        $stop = 0;
        while(in_array($akttermek = array_rand($termekek),$volttermekek)){
            if(++$stop == 100){
                continue 3;
            }
        }
        $volttermekek[] = $akttermek;
        $node_termek = $node_aktakcio->addChild('termek');
        $node_termek->addAttribute('id',$akttermek);
        $node_termek->addAttribute('jelenlegi_ar',$termekek[$akttermek]*$aktakcio['mertek']/100);
        $termekarak[$id][$akttermek] = $termekek[$akttermek]*$aktakcio['mertek']/100;
    }
}

$dontagain = array();
$torzsvasarlos = array();
for($i=count($uzlethelysegek)*rand(10,20)+rand(-10,15);$i>0;--$i){
    $torzsvasarlo = array();
    $x = 0;
    do{
        if(++$x==15){
            break;
        }
        $torzsvasarlo['kedvezmeny_tipusa'] = getTorzsvasarloKedvezmenytipus();
        $torzsvasarlo['nem'] = getNem();
        $torzsvasarlo['allampolgarsag'] = getAllampolgarsag();
        $torzsvasarlo['csaladi_statusz'] = getCsaladiAllapot();
        $torzsvasarlo['szuletesi_ev'] = getSzuletesiEv();
    } while(isset($dontagain[$torzsvasarlo['kedvezmeny_tipusa'].$torzsvasarlo['nem'].$torzsvasarlo['allampolgarsag'].$torzsvasarlo['csaladi_statusz'].$torzsvasarlo['szuletesi_ev']]));
    $dontagain[$torzsvasarlo['kedvezmeny_tipusa'].$torzsvasarlo['nem'].$torzsvasarlo['allampolgarsag'].$torzsvasarlo['csaladi_statusz'].$torzsvasarlo['szuletesi_ev']] = 'x';

    $x = 0;
    do{
        if(++$x==100){
            break;
        }
        $torzsvasarlo['nev'] = getNev($torzsvasarlo['nem']);
        $torzsvasarlo['lakcim'] = getRandomOrszagVaros();
    } while(isset($dontagain[implode(',',$torzsvasarlo['nev']).implode(',',$torzsvasarlo['lakcim'])]));
    $dontagain[implode(',',$torzsvasarlo['nev']).implode(',',$torzsvasarlo['lakcim'])] = 'y';
    
    $torzsvasarlos[] = $torzsvasarlo;
}

unset($dontagain);
usort($torzsvasarlos, function($a,$b){
    if($a['kedvezmeny_tipusa']>$b['kedvezmeny_tipusa'] || $a['kedvezmeny_tipusa']<$b['kedvezmeny_tipusa']){
        return $a['kedvezmeny_tipusa']>$b['kedvezmeny_tipusa'] ? 1 : -1;
    }
    if($a['lakcim']['ország']>$b['lakcim']['ország'] || $a['lakcim']['ország']<$b['lakcim']['ország']){
        return $a['lakcim']['ország']>$b['lakcim']['ország'] ? 1 : -1;
    }
    if($a['lakcim']['város']>$b['lakcim']['város'] || $a['lakcim']['város']<$b['lakcim']['város']){
        return $a['lakcim']['város']>$b['lakcim']['város'] ? 1 : -1;
    }
    if($a['nem']>$b['nem'] || $a['nem']<$b['nem']){
        return $a['nem']>$b['nem'] ? 1 : -1;
    }
    if($a['allampolgarsag']>$b['allampolgarsag'] || $a['allampolgarsag']<$b['allampolgarsag']){
        return $a['allampolgarsag']>$b['allampolgarsag'] ? 1 : -1;
    }
    if($a['csaladi_statusz']>$b['csaladi_statusz'] || $a['csaladi_statusz']<$b['csaladi_statusz']){
        return $a['csaladi_statusz']>$b['csaladi_statusz'] ? 1 : -1;
    }
    if($a['szuletesi_ev']>$b['szuletesi_ev'] || $a['szuletesi_ev']<$b['szuletesi_ev']){
        return $a['szuletesi_ev']>$b['szuletesi_ev'] ? 1 : -1;
    }
    return 0;
});

$node_torzsvasarlok = $xml->addChild('torzsvasarlok');
$beforekedvezmeny_tipusa = '';
$torzsvasarlok = array();
foreach($torzsvasarlos as $torzsvasalo){
    if($torzsvasalo['kedvezmeny_tipusa']!=$beforekedvezmeny_tipusa){
        $node_kedvezmeny_tipusa = $node_torzsvasarlok->addChild('kedvezmeny_tipusa');
        $node_kedvezmeny_tipusa->addAttribute('tipus',$torzsvasalo['kedvezmeny_tipusa']);
        $beforekedvezmeny_tipusa = $torzsvasalo['kedvezmeny_tipusa'];
        $beforeorszag = '';
    }
    if($torzsvasalo['lakcim']['ország']!=$beforeorszag){
        $node_orszag = $node_kedvezmeny_tipusa->addChild($torzsvasalo['lakcim']['ország']);
        $beforeorszag = $torzsvasalo['lakcim']['ország'];
        $beforevaros = '';
    }
    if($torzsvasalo['lakcim']['város']!=$beforevaros){
        $node_varos = $node_orszag->addChild($torzsvasalo['lakcim']['város']);
        $beforevaros = $torzsvasalo['lakcim']['város'];
        $beforeszuletesi_ev = '';
    }
    if($torzsvasalo['szuletesi_ev']!=$beforeszuletesi_ev){
        $node_szuletesi_ev = $node_varos->addChild('szuletesi_ev');
        $node_szuletesi_ev->addAttribute("ev", $torzsvasalo['szuletesi_ev']);
        $beforeszuletesi_ev = $torzsvasalo['szuletesi_ev'];
        $beforenem = '';
    }
    if($torzsvasalo['nem']!=$beforenem){
        $node_nem = $node_szuletesi_ev->addChild($torzsvasalo['nem']);
        $beforenem = $torzsvasalo['nem'];
        $beforeallampolgarsag = '';
    }
    if($torzsvasalo['allampolgarsag']!=$beforeallampolgarsag){
        $node_allampolgarsag = $node_nem->addChild($torzsvasalo['allampolgarsag']);
        $beforeallampolgarsag = $torzsvasalo['allampolgarsag'];
        $beforecsaladi_statusz = '';
    }
    if($torzsvasalo['csaladi_statusz']!=$beforecsaladi_statusz){
        $node_csaladi_statusz = $node_allampolgarsag->addChild($torzsvasalo['csaladi_statusz']);
        $beforecsaladi_statusz = $torzsvasalo['csaladi_statusz'];
    }
    $node_torzsvasarlo = $node_csaladi_statusz->addChild('torzsvasarlo');
    while(in_array($kartyaazonosito = md5(rand(1,100000000000000000)*rand(1,1000000000000000)),$torzsvasarlok));
    $torzsvasarlok[] = $kartyaazonosito;
    $node_torzsvasarlo->addAttribute('kartyaazonosito',$kartyaazonosito);
    $node_torzsvasarlo->addChild('vezeteknev',$torzsvasalo['nev']['vezetéknév']);
    $node_torzsvasarlo->addChild('keresztnev',$torzsvasalo['nev']['keresztnév']);
    $node_torzsvasarlo->addChild('kezdeti_datum',rand(2014,2017).'-'.rand(1,11).'-'.rand(1,28));
    $node_torzsvasarlo->addChild('lejarati_datum',rand(2018,2057).'-'.rand(1,12).'-'.rand(1,28));
}

unset($torzsvasarlos);

$vasarlass=array();
for($i=$alma=count($uzlethelysegek)*rand(10,20)+rand(-10,15);$i>0;--$i){
    $vasarlas = array();
    $vasarlas['penztaros'] = array_rand($eladok);
    $vasarlas['uzlethelyseg'] = $eladok[$vasarlas['penztaros']];
    $vasarolt_termekek = array();
    $lehetseges_termekek = $termekarak[$vasarlas['uzlethelyseg']];
    for($j=rand(1,count($lehetseges_termekek));$j>0;--$j){
        $vasarolt_termekek[array_rand($lehetseges_termekek)]='';
    }
    $vasarlas['vasarolt_termekek'] = $vasarolt_termekek;
    $vasarlas['ev'] = rand(2014,2017);
    $vasarlass[] = $vasarlas;
}

usort($vasarlass, function($a,$b){
    if($a['uzlethelyseg']>$b['uzlethelyseg'] || $a['uzlethelyseg']<$b['uzlethelyseg']){
        return $a['uzlethelyseg']>$b['uzlethelyseg'] ? 1 : -1;
    }
    if($a['penztaros']>$b['penztaros'] || $a['penztaros']<$b['penztaros']){
        return $a['penztaros']>$b['penztaros'] ? 1 : -1;
    }
    if($a['ev']>$b['ev'] || $a['ev']<$b['ev']){
        return $a['ev']>$b['ev'] ? 1 : -1;
    }
    return 0;
});

$node_vasarlasok = $xml->addChild('vasarlasok');
$beforeuzlethelyseg = '';
foreach($vasarlass as $vasarlas){
    if($vasarlas['uzlethelyseg']!=$beforeuzlethelyseg){
        $node_uzlethelyseg = $node_vasarlasok->addChild('vasarlasi_uzlethelyseg');
        $node_uzlethelyseg->addAttribute('id',$vasarlas['uzlethelyseg']);
        $beforeuzlethelyseg = $vasarlas['uzlethelyseg'];
        $beforepenztaros = '';
    }
    if($vasarlas['penztaros']!=$beforepenztaros){
        $node_penztaros = $node_uzlethelyseg->addChild('penztaros');
        $node_penztaros->addAttribute('id',$vasarlas['penztaros']);
        $beforepenztaros = $vasarlas['penztaros'];
        $beforeev = '';
    }
    if($vasarlas['ev']!=$beforeev){
        $node_ev = $node_penztaros->addChild('vasarlasi_ev');
        $node_ev->addAttribute('ev',$vasarlas['ev']);
        $beforeev = $vasarlas['ev'];
    }
    $node_vasarlas = $node_ev->addChild('vasarlas');
    
    $node_vasarolt_termekek = $node_vasarlas->addChild('vasarolt_termekek');
    $sum = 0;
    foreach(array_keys($vasarlas['vasarolt_termekek']) as $termek){
        $db = rand(1,25);
        $node_vasarolt_termek = $node_vasarolt_termekek->addChild('vasarolt_termek');
        $node_vasarolt_termek->addAttribute('id', $termek);
        $node_vasarolt_termek->addAttribute('db',$db);
        $sum+= $termekarak[$vasarlas['uzlethelyseg']][$termek] * $db;
    }
    
    $node_vasarlas->addChild('fizetendo',$sum);
    $node_vasarlas->addChild('vasarlas_ideje',$vasarlas['ev'].'-'.rand(1,11).'-'.rand(1,28));
}

unset($vasarlass);
$xml->asXML('uzletlanc.xml');