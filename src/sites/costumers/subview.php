<?php

$mytable = new MyTable("costumers");
$nevHeader = new MyTableHeader("nev",'Név');
$nevHeader->setFilter(true)->setType('string');
$varosHeader = new MyTableHeader('varos','Város');
$varosHeader->setFilter(true)->setType('string');
$vasarlasokSzamaHeader = new MyTableHeader("vasarlasok_szama",'Vásárlások száma');
$vasarlasokSzamaHeader->setFilter(true)->setType('integer');

$mytable->addHeader($nevHeader)
        ->addHeader($varosHeader)
        ->addHeader($vasarlasokSzamaHeader);

$formURL = actualURL(); 
$result = Data::getCostumers();
$mytable->setCount(count($result));
$mytable->setLimit(20);
for($i=0;$i<20;++$i){
    if(($mytable->getPage()-1)*20+$i >= count($result)){
        break;
    }
    $mytable->setValue($i, 'nev', $result[($mytable->getPage()-1)*20+$i]['name']);
    $mytable->setValue($i, 'varos', $result[($mytable->getPage()-1)*20+$i]['city']);
    $mytable->setValue($i, 'vasarlasok_szama', $result[($mytable->getPage()-1)*20+$i]['count']);
}
$table = $mytable->generate();

$content = <<<CONTENT
<form method="POST" enctype="multipart/form-data">
    $table
</form>
CONTENT;
