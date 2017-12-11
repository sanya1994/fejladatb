<?php

$typeLabel = '<label for="type">Ajda meg mit keres:&nbsp;</label>';
$typeSelect = InputCreators::selectcreator('type', 'type', $types,array('required'=>'true'));

$searchButton = InputCreators::inputcreator('search','search','submit',array('value'=>'Keresés'));

$uzlethelyjs = include 'uzlethelyjs.php';

$changejs =
'$(document).ready(
    function(){
        $("#orders").hide();
        $("#type").change(function() {
            $("#fields").empty();
            if($(this).val()!=""){
                $("#orders").show();
            } else{
                $("#orders").hide();
            }
            if($(this).val()=="uzlethely"){
                '.$uzlethelyjs.'
            }
        });
});';


addJS($changejs);

$firstOrderLabel = '<label for="firstorder">Első rendezési szempont:&nbsp;</label>';
$firstOrderSelect = InputCreators::selectcreator('first_order', 'first_order', array());
$firstOrderType = InputCreators::selectcreator('first_order_type', 'first_order_type', array("ascending" => "Növekvő", "descending" => "Csökkenő"));

$secondOrderLabel = '<label for="secondorder">Második rendezési szempont:&nbsp;</label>';
$secondOrderSelect = InputCreators::selectcreator('second_order', 'second_order', array());
$secondOrderType = InputCreators::selectcreator('second_order_type', 'second_order_type', array("ascending" => "Növekvő", "descending" => "Csökkenő"));

$thirdOrderLabel = '<label for="thirdorder">Harmadik rendezési szempont:&nbsp;</label>';
$thirdOrderSelect = InputCreators::selectcreator('third_order', 'third_order', array());
$thirdOrderType = InputCreators::selectcreator('third_order_type', 'third_order_type', array("ascending" => "Növekvő", "descending" => "Csökkenő"));
$content = <<<CONTENT
<form method="GET" enctype="multipart/form-data">
    <div class="MyPage">
        <div class="PageTitle">Keresés</div>
        <div class="BlockSpacer"></div>
        <div class="BlockSixth">$typeLabel</div>
        <div class="BlockFiveSixth">$typeSelect</div>
        <div class="BlockSpacer"></div>
        <div id="orders" class="BlockFull">
        <hr/>
        <div class="BlockFull">
            <div class="BlockThird">$firstOrderLabel</div>
            <div class="BlockThird">$secondOrderLabel</div>
            <div class="BlockThird">$thirdOrderLabel</div>
        </div>
        <div class="BlockFull">
            <div class="BlockThird">$firstOrderSelect</div>
            <div class="BlockThird">$secondOrderSelect</div>
            <div class="BlockThird">$thirdOrderSelect</div>
        </div>
        <div class="BlockFull">
            <div class="BlockThird">$firstOrderType</div>
            <div class="BlockThird">$secondOrderType</div>
            <div class="BlockThird">$thirdOrderType</div>
        </div>
        </div>
        <div class="BlockSpacer"></div>
        <hr/>
        <div id="fields" class="BlockFull">
        </div>
        <div class="BlockSpacer"></div>
        <div class="CenterAlign">$searchButton</div>
        <div class="BlockSpacer"></div>
        <div class="BlockEnd"></div>
    </div>
</form>
CONTENT;

$updategetjs = '';
if(isset($_GET['type'])){
    $updategetjs.=
'if($("#type").length){
    $("#type").val("'.$_GET['type'].'").trigger("change");
}';
}
foreach($_GET as $key => $value){
    $updategetjs.=
'if($("#'.$key.'").length){
    $("#'.$key.'").val("'.$value.'").trigger("change");
}';
}
addJS('$(document).ready(
    function(){
        '.$updategetjs.'
});');