<?php


$typeLabel = '<label for="type">Ajda meg mit keres:&nbsp;</label>';
$typeSelect = InputCreators::selectcreator('type', 'type', $types,array('required'=>'true'));

$searchButton = InputCreators::inputcreator('search','search','submit',array('value'=>'Keresés'));

$uzlethelyjs = include 'uzlethelyjs.php';

$changejs =
'$(document).ready(
    function(){
        $("#type").change(function() {
            $("#fields").empty();
            if($(this).val()=="uzlethely"){
                $("#fields").html(\''.$uzlethelyjs.'\');
            }
        });
});';


addJS($changejs);

$content = <<<CONTENT
<form method="GET" enctype="multipart/form-data">
    <div class="MyPage">
        <div class="PageTitle">Keresés</div>
        <div class="BlockSpacer"></div>
        <div class="BlockSixth RightAlign">$typeLabel</div>
        <div class="BlockFiveSixth">$typeSelect</div>
        <div class="BlockSpacer"></div>
        <hr/>
        <div id="fields">
        </div>
        <div class="BlockSpacer"></div>
        <div class="CenterAlign">$searchButton</div>
        <div class="BlockSpacer"></div>
        <div class="BlockEnd"></div>
    </div>
</form>
CONTENT;
