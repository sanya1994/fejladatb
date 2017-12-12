<?php

$table = '<table class="MyTable"><thead><tr>';
$table.='<th>ID</th>';
foreach($name[$_GET['type']] as $key => $column){
    $table.='<th>'.$column.'</th>';
}
$table.='</tr></thead><tbody>';
$processedresult= simplexml_load_string($results[0]);
foreach($processedresult->children() as $result){
    $table.='<tr>';
    $table.='<td>'.$result->attributes()->id.'</td>';
    foreach($name[$_GET['type']] as $key => $column){
        $table.='<td>'.$result->$key.'</td>';
    }
    $table.='</tr>';
}
$table .="</tbody></table>";

$content = <<<CONTENT
<form method="GET" enctype="multipart/form-data">
    <div class="BlockFull">
        $table
    </div>
</form>
CONTENT;
