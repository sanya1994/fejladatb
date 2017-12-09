<?php

class MyWords{
    static function unaccent($string){
        $string = str_replace(array('ő','Ő','ű','Ű'),array('o','O','ü','Ű'), $string);
        if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false)
        {
            $string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
        }
        return $string;
    }
}