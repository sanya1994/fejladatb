<?php
$torzsvasarlojs='';
foreach($fields['torzsvasarlo'] as $fieldname => $fieldvalue){
    $fieldLabel = '<label for="torzsvasarlo_'.$fieldname.'">'.(isset($name['torzsvasarlo'][$fieldname]) ? $name['torzsvasarlo'][$fieldname] : $fieldname).':&nbsp;</label>';
    $torzsvasarlojs .= '<div class="BlockSixth RightAlign">'.$fieldLabel.'</div>';
    if(is_array($fieldvalue) && $fieldname!='varos'){
        $fieldSelect = InputCreators::selectcreator('torzsvasarlo_'.$fieldname, 'torzsvasarlo_'.$fieldname, array_combine($fieldvalue,$fieldvalue));
        $torzsvasarlojs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    } else if($fieldname =='varos'){
        $mindenvaros = array();
        foreach($fieldvalue as $orszag => $varosok){
            $mindenvaros = array_merge($mindenvaros, $varosok);
        }
        $fieldSelect = InputCreators::selectcreator('torzsvasarlo_'.$fieldname, 'torzsvasarlo_'.$fieldname, array_combine($mindenvaros, $mindenvaros));
        $torzsvasarlojs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    }else if($fieldvalue=='biggerint'){
        $fieldSelect = InputCreators::selectcreator('torzsvasarlo_'.$fieldname.'_operator', 'torzsvasarlo_'.$fieldname.'_operator', $booloperators);
        $fieldInput = InputCreators::inputcreator('torzsvasarlo_'.$fieldname, 'torzsvasarlo_'.$fieldname, 'number');
        $torzsvasarlojs .= '<div class="BlockSixth">'.$fieldSelect.'</div>';
        $torzsvasarlojs .= '<div class="BlockTwoThird">'.$fieldInput.'</div>';
    } else if($fieldvalue=='string'){
        $fieldInput = InputCreators::inputcreator('torzsvasarlo_'.$fieldname, 'torzsvasarlo_'.$fieldname, 'textbox');
        $torzsvasarlojs .= '<div class="BlockFiveSixth">'.$fieldInput.'</div>';
    }
    $torzsvasarlojs .= '<div class="BlockMiniSpacer"></div>';
}

$torzsvasarlojs='$("#fields").html(\''.$torzsvasarlojs.'\');';

$orders = array();
foreach(array_keys($fields['torzsvasarlo']) as $field){
    $orders[$field] = $name['torzsvasarlo'][$field];
}
foreach(array('first_order','second_order','third_order') as $order){
    $torzsvasarlojs.='var $el = $("#'.$order.'");
$el.empty();
$el.append($("<option></option>")
         .attr("value","").text("Kérem válasszon!"));';
    foreach($orders as $key => $value){
    $torzsvasarlojs.='
    $el.append($("<option></option>")
         .attr("value", "'.$key.'").text("'.$value.'"));';
    }
}

return $torzsvasarlojs;