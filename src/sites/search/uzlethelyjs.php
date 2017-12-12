<?php
$uzlethelyjs='';
foreach($fields['uzlethely'] as $fieldname => $fieldvalue){
    $fieldLabel = '<label for="uzlethely_'.$fieldname.'">'.(isset($name['uzlethely'][$fieldname]) ? $name['uzlethely'][$fieldname] : $fieldname).':&nbsp;</label>';
    $uzlethelyjs .= '<div class="BlockSixth RightAlign">'.$fieldLabel.'</div>';
    if(is_array($fieldvalue) && $fieldname!='varos'){
        $fieldSelect = InputCreators::selectcreator('uzlethely_'.$fieldname, 'uzlethely_'.$fieldname, array_combine($fieldvalue,$fieldvalue));
        $uzlethelyjs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    } else if($fieldname =='varos'){
        $mindenvaros = array();
        foreach($fieldvalue as $orszag => $varosok){
            $mindenvaros = array_merge($mindenvaros, $varosok);
        }
        $fieldSelect = InputCreators::selectcreator('uzlethely_'.$fieldname, 'uzlethely_'.$fieldname, array_combine($mindenvaros, $mindenvaros));
        $uzlethelyjs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    }else if($fieldvalue=='biggerint'){
        $fieldSelect = InputCreators::selectcreator('uzlethely_'.$fieldname.'_operator', 'uzlethely_'.$fieldname.'_operator', $compareoperators);
        $fieldInput = InputCreators::inputcreator('uzlethely_'.$fieldname, 'uzlethely_'.$fieldname, 'number');
        $uzlethelyjs .= '<div class="BlockSixth">'.$fieldSelect.'</div>';
        $uzlethelyjs .= '<div class="BlockTwoThird">'.$fieldInput.'</div>';
    } else if($fieldvalue=='string'){
        $fieldInput = InputCreators::inputcreator('uzlethely_'.$fieldname, 'uzlethely_'.$fieldname, 'textbox');
        $uzlethelyjs .= '<div class="BlockFiveSixth">'.$fieldInput.'</div>';
    }
    $uzlethelyjs .= '<div class="BlockMiniSpacer"></div>';
}

$uzlethelyjs='$("#fields").html(\''.$uzlethelyjs.'\');';

$orders = array();
foreach(array_keys($fields['uzlethely']) as $field){
    $orders[$field] = $name['uzlethely'][$field];
}
foreach(array('first_order','second_order','third_order') as $order){
    $uzlethelyjs.='var $el = $("#'.$order.'");
$el.empty();
$el.append($("<option></option>")
         .attr("value","").text("Kérem válasszon!"));';
    foreach($orders as $key => $value){
    $uzlethelyjs.='
    $el.append($("<option></option>")
         .attr("value", "'.$key.'").text("'.$value.'"));';
    }
}

return $uzlethelyjs;