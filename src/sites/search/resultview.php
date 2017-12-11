<?php

$table = '<table class="MyTable"><thead><tr>';
$table.='<th>ID</th>';
foreach($name[$_GET['type']] as $key => $column){
    $table.='<th>'.$column.'</th>';
}
$table.='</tr></thead><tbody>';
foreach($results as $result){
    $processedresult = simplexml_load_string($result);
    $table.='<tr>';
    $table.='<td>'.$processedresult->attributes()->id.'</td>';
    foreach($name[$_GET['type']] as $key => $column){
        $table.='<td>'.$processedresult->$key.'</td>';
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
