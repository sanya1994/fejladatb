<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$uzletlancAsSimpleNode = simplexml_load_file('uzletlanc.xml');
$uzletlancAsSimpleNode->addAttribute('encoding','UTF-8');
$conn->storeDocument(
    'Uzletlanc/uzletlanc.xml',
    $uzletlancAsSimpleNode->asXML(),
    true
);
