<?php

class Data{
    static function getCostumers(){
        $citys = array('Debrecen','Budapest','Téglás','Újfehértó','Hajdúhadház','Bocskaikert','Budapest','Békéscsaba','Debrecen','Nyíregyháza','Nyíregyháza','Nyíregyháza','Debrecen','Nyíregyháza');
        $lastnames = array('Kovács','Kiss','Balogh','Szabó','Kocsis','Molnár','Kalapos','Király','Nagy','Lakatos','Kanalas','Hosszú','Tóth');
        $firstnames = array('Péter','Béla','Ildikó','Mónika','Dávid','Eszter','László','Evelin','Anna','Petra','Mihály','Károly András', 'Géza','Tünde','Nóra','Olga');
        $costumers = array();
        for($i = 0; $i < 250; ++$i){
            $costumer = array();
            $costumer['city'] = $citys[$i % count($citys)];
            $costumer['name'] = $lastnames[$i % count($lastnames)].' '. $firstnames[$i % count($firstnames)];
            $costumer['count'] = strlen($citys[$i % count($citys)]) + $lastnames[$i % count($lastnames)] * $firstnames[$i % count($firstnames)] + $i%12 -$i%10;
            $costumers[] = $costumer;
        }
        return $costumers;
    }
}