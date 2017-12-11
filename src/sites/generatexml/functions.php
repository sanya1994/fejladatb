<?php
set_time_limit(0);

function getNev($nem){
    $lastname = array('Molnár','Váradi','Kovács','Postás','Alma','Banán','Citrom','Nagy','Egér','Billentyűzet','Lámpa','Fejlesztő','Tesztelő','Tervező','Vásárló','Boltos','Tulajdonos','Takarító','Kerekes','Földi','Marsi','Plútói','Kód');
    if($nem=='férfi'){
        $firstname = array('László','Sándor','Géza','Gergely','József','Dávid','Ferenc','Tóbiás','Antal','Attila','Dániel','Mihály','Ödön','Ákos','Viktor','Tamás','Kristóf','Elemér','Károly','Gábor','Dénes','Ádám');
    } else{
        $firstname = array('Éva','Tünde','Ildikó','Alexandra','Krisztina','Katalin','Kitti','Anita','Petra','Erika','Olga','Viktória','Barbara','Dóra','Dia','Andrea','Mónika','Erzsébet','Gyöngyi','Dorottya','Gabriella');
    }
    return array('vezetéknév' => $lastname[array_rand($lastname,1)], 'keresztnév' => $firstname[array_rand($firstname,1)]);
}

function getAllOrszag(){
    return array('Magyarország','Ausztria','Románia');
}

function getAllOrszagVaros(){
    $varosok = array(
        'Magyarország' =>
            array(
                'Debrecen','Debrecen','Debrecen','Debrecen','Debrecen','Debrecen','Debrecen','Debrecen','Debrecen',
                'Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest','Budapest',
                'Újfehértó','Újfehértó',
                'Szolnok','Szolnok','Szolnok','Szolnok','Szolnok','Szolnok','Szolnok',
                'Geszteréd',
                'Érpatak',
                'Nyíregyháza','Nyíregyháza','Nyíregyháza','Nyíregyháza','Nyíregyháza','Nyíregyháza','Nyíregyháza',
                'Szeged','Szeged','Szeged','Szeged','Szeged','Szeged','Szeged','Szeged','Szeged','Szeged','Szeged','Szeged',
                'Hajdúhadház',
                'Bocskaikert',
                'Téglás',
                'Apafa',
                'Püspökladány',
                'Záhony',
                'Kisvárda',
                'Cegléd'),
        'Ausztria' =>
            array(
                'Bécs'
            ),
        'Románia' =>
            array(
                'Kolozsvár'
            )
    );
    return $varosok;
}
function getRandomOrszagVaros(){
    $varosok = getAllOrszagVaros();
    $rand = rand(1,1000);
    if($rand<856){
        return array('ország' => 'Magyarország', 'város' => $varosok['Magyarország'][array_rand($varosok['Magyarország'],1)]);
    } else if($rand <911){
        return array('ország' => 'Ausztria', 'város' => 'Bécs');
    } else{
        return array('ország' => 'Románia', 'város' => 'Kolozsvár');
    }
}
function getKozter(){
    $name = array('Árpád','Kassai','Nagyerdei','Hadházi','Laktanya','Csapó','Székely','Virág','Faraktár','Bem','Kossuth','Füredi','Böszörményi','Debreceni','Fő','Mellék','Kert','Tó','Ceglédi','Saját');
    $jelleg = array('utca','út','tér');
    return array('kozternev' => $name[array_rand($name,1)], 'kozterjelleg' => $jelleg[array_rand($jelleg,1)]);
}
function getAllMunkakor(){
    return array(
        'informatikus' => array(
            'programozó','programozó','programozó',
            'tesztelő',
            'tervező',
            'rendszergazda'
        ),
        'bolti munkás' => array(
            'árufeltöltő','árufeltöltő','árufeltöltő','árufeltöltő','árufeltöltő',
            'eladó','eladó','eladó','eladó','eladó','eladó','eladó','eladó','eladó','eladó','eladó',
            'pénztáros','pénztáros','pénztáros','pénztáros','pénztáros',
        ),
        'egyéb' => array(
            'takarító','takarító','takarító','takarító','takarító','takarító',
            'karbantartó','karbantartó','karbantartó','karbantartó',
        ),
        'logisztika' => array(
            'logisztikai megbízott',
        ),
        'vezetés' => array(
            'vezető',
        )
    );
}

$fizetesek = array(
    'programozó' => 400000,
    'tesztelő' => 350000,
    'tervező' => 300000,
    'rendszergazda' => 250000,
    'árufeltöltő' => 160000,
    'eladó' => 160000,
    'pénztáros' => 180000,
    'takarító' => 140000,
    'karbantartó' => 140000,
    'logisztikai megbízott' => 200000,
    'vezető' => 500000
);

function getMunkakor($munkakortszeretnek = true, $tipus = NULL, $dont = NULL){
    $munkakorok = getAllMunkakor();
    if($dont!==NULL){
        $munkakorok = array_filter($munkakorok, function($key) use($dont){return !in_array($key,$dont);},ARRAY_FILTER_USE_KEY);
    }
    if($munkakortszeretnek){
        $rand = rand(1,1000);
        if($rand<100){
            $munkakor = $tipus!=NULL ? $tipus : 'informatikus';
            return array('tipus' => $munkakor, 'munkakor' => $munkakorok[$munkakor][array_rand($munkakorok[$munkakor],1)]);
        } else if($rand < 812){
            $munkakor = $tipus!=NULL ? $tipus : 'bolti munkás';
            return array('tipus' => $munkakor, 'munkakor' => $munkakorok[$munkakor][array_rand($munkakorok[$munkakor],1)]);
        } else if($rand < 912){
            $munkakor = $tipus!=NULL ? $tipus : 'logisztika';
            return array('tipus' => $munkakor, 'munkakor' => $munkakorok[$munkakor][array_rand($munkakorok[$munkakor],1)]);
        } else if($rand < 963){
            $munkakor = $tipus!=NULL ? $tipus : 'logisztika';
            return array('tipus' => $munkakor, 'munkakor' => $munkakorok[$munkakor][array_rand($munkakorok[$munkakor],1)]);
        } else{
            $munkakor = $tipus!=NULL ? $tipus : 'vezetés';
            return array('tipus' => $munkakor, 'munkakor' => $munkakorok[$munkakor][array_rand($munkakorok[$munkakor],1)]);
        }
    }
}

function getHetiOra(){
    $rand = rand(1,1000);
    if($rand<900){
        return 40;
    } else if($rand > 899 && $rand < 950) {
        return 30;
    } else{
        return 20;
    }
}

function getNem(){
    return rand(1,2) == 1 ? 'férfi' : 'nő';
}

function getAllampolgarsag(){
    $allampolgarsagok = array('magyar','magyar','magyar','magyar','magyar','magyar','magyar','magyar','magyar','magyar','osztrák','román', 'szlovák', 'cseh', 'német', 'angol', 'horvát', 'szerb', 'szlovén');
    return $allampolgarsagok[rand(0,count($allampolgarsagok)-1)];
}

function getCsaladiAllapot(){
    $csallapot = array('házas','házas','elvált','egyedülálló','egyedülálló');
    return $csallapot[rand(0,count($csallapot)-1)];
}
global $szuletesi_idok;
$szuletesi_idok = array_merge(
            range(1900,1999),
            range(1940,1999),range(1940,1999),range(1940,1999),
            range(1952,1999),range(1952,1999),range(1952,1999),range(1952,1999),range(1952,1999),
            range(1962,1996),range(1962,1996),range(1962,1996),range(1962,1996),range(1962,1996),range(1962,1996),range(1962,1996),range(1962,1996),
            range(1982,1992),range(1982,1992),range(1982,1992),range(1982,1992),range(1982,1992),range(1982,1992),range(1982,1992),range(1982,1992)
    );
function getSzuletesiEv(){
    global $szuletesi_idok;
    return $szuletesi_idok[rand(0,count($szuletesi_idok)-1)];
}

function getFizetesTipusa(){
    $fizetestipusok = array('átutalás','készpénz');
    return $fizetestipusok[array_rand($fizetestipusok)];
}

function getAllVegzettseg(){
    $vegzettsegek = array(
    );
     foreach(array('német','német','német','angol','angol','angol','angol','angol','finn','japán','orosz', 'francia', 'olasz') as $nyelv){
        $vegzettsegek[$nyelv.' nyelvvizsga'] = array();
        foreach(array('A','B', 'C', 'C') as $tipus){
            foreach(array('alapfokú','középfokú','középfokú','középfokú','felsőfokú','felsőfokú') as $fok){
                $vegzettsegek[$nyelv.' nyelvvizsga'][] = $tipus.' típusú '.$fok.' nyelvvizsga';
            }
        }
    }
    foreach(array('üzletvezetői','kereskedelem és marketing','kereskedelem és marketing','programtervező informatikus','gazdasági informatikus', 'mérnök informatikus') as $diploma){
        $vegzettsegek[$diploma.' oktatási oklevél'] = array();
        foreach(array('bsc','bsc','bsc','msc','okj', 'érettségi') as $szint){
            $vegzettsegek[$diploma.' oktatási oklevél'][] = $szint;
        }
    }
    return $vegzettsegek;
}
function getVegzettsegek(){
    $rand = rand(1,200);
    if($rand<85){
        $countVegzettsegek = 1;
    } else if($rand < 134){
        $countVegzettsegek = 2;
    } else if($rand < 185){
        $countVegzettsegek = 3;
    } else if($rand <197){
        $countVegzettsegek = 4;
    } else{
        return array();
    }
    $vegzettsegek = getAllVegzettseg();
    $actvegzettseg = array();
    $tipusok = array();
    foreach ($vegzettsegek as $tipus=>$value)
    {
        $tipusok = array_merge($tipusok, array_fill(0, count($value), $tipus));
    }

    for($i=0; $i<$countVegzettsegek;++$i){
        $tipus = $tipusok[array_rand($tipusok)];
        $szint = $vegzettsegek[$tipus][array_rand($vegzettsegek[$tipus])];
        $actvegzettseg[] = array('tipus' => $tipus, 'szint' => $szint);
    }
    
    return $actvegzettseg;
}


function isDiversedArray($array,$element){
    if(count($array)==0){
        return true;
    }
    if(count($array)<6){
        return !in_array($element, $array);
    } else{
        if(!in_array($element, $array)){
            return true;
        }
        $count = array_count_values($array);
        $countplusz = array_count_values(array_merge($array, array($element)));
        $fMean = array_sum($count) / count($count);
        return rand(1,2) == 1 || array_sum(array_map(function ($x) use ($fMean) { 
                return pow($x - $fMean, 2);
            }, $countplusz)) / count($countplusz) < array_sum(array_map(function ($x) use ($fMean) { 
                return pow($x - $fMean, 2);
            }, $count)) / count($count);
    }
}

function getAllTermek(){
    return array(
        'élelmiszerek' => array(
            'pékáruk' => array(
                'kifli',
                'kenyér',
                'zsömle',
                'kalács'
            ),
            'tejtermékek' => array(
                'tej',
                'gyümölcsös_joghurt',
                'natúr_joghurt'
            ),
            'gyümölcs' => array(
                'alma',
                'banán',
                'eper',
                'körte',
                'őszibarack',
                'gyümölcsös_joghurt',
                'natúr_joghurt'
            ),
            'gyümölcs' => array(
                'alma',
                'banán',
                'eper',
                'körte',
                'őszibarack',
                'gyümölcsös_joghurt',
                'natúr_joghurt'
            ),
            'zöldség' => array(
                'paprika',
                'paradicsom',
                'sárgarépa'
            ),
            'egyéb' => array(
                'csoki',
                'narancs_üdítő',
                'alma_üdítő'
            )
        ),
        'írószerek' => array(
            'papírok' => array(
                'A4-es_papír',
                'toll',
                'ceruza'
            )
        ),
        'bútorok' => array(
            'ágyak' => array(
                'kényelmes_ágy',
                'kényelmetlen_ágy'
            ),
            'fotelek' => array(
                'bőrfotel',
                'nembőr_fotel'
            ),
            'szék' => array(
                'gurulós',
                'nemgurulós'
            )
        )
    );
}

function getAllMarka(){
    return array('Pepsi','Nestle','Apple','Samsung', 'Tolle', 'Univer');
}

function getTorzsvasarloKedvezmenytipus(){
    $kedvezmenyek = array("20%-os","15%os","hetente egyszer fele pénzt fizethet");
    return $kedvezmenyek[array_rand($kedvezmenyek)];
}
