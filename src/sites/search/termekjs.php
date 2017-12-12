<?php
$termekjs='';
foreach($fields['termek'] as $fieldname => $fieldvalue){
    $fieldLabel = '<label for="termek_'.$fieldname.'">'.(isset($name['termek'][$fieldname]) ? $name['termek'][$fieldname] : $fieldname).':&nbsp;</label>';
    $termekjs .= '<div class="BlockSixth RightAlign">'.$fieldLabel.'</div>';
    if(is_array($fieldvalue) && $fieldname!='tipus' && $fieldname!='termeknev'){
        $fieldSelect = InputCreators::selectcreator('termek_'.$fieldname, 'termek_'.$fieldname, array_combine($fieldvalue,$fieldvalue));
        $termekjs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    } else if($fieldname =='tipus'){
        $mindenvaros = array();
        foreach($fieldvalue as $kategoria => $varosok){
            foreach($varosok as $tipus => $nemisvaros){
                $mindenvaros[] = $tipus;
            }
        }
        $fieldSelect = InputCreators::selectcreator('termek_'.$fieldname, 'termek_'.$fieldname, array_combine($mindenvaros, $mindenvaros));
        $termekjs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    } else if($fieldname =='termeknev'){
        $mindenvaros = array();
        foreach($fieldvalue as $orszag => $varosok){
            foreach($varosok as $nemisvaros){
                $mindenvaros = array_merge($mindenvaros, $nemisvaros);
            }
        }
        $fieldSelect = InputCreators::selectcreator('termek_'.$fieldname, 'termek_'.$fieldname, array_combine($mindenvaros, $mindenvaros));
        $termekjs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    }else if($fieldvalue=='biggerint'){
        $fieldSelect = InputCreators::selectcreator('termek_'.$fieldname.'_operator', 'termek_'.$fieldname.'_operator', $compareoperators);
        $fieldInput = InputCreators::inputcreator('termek_'.$fieldname, 'termek_'.$fieldname, 'number');
        $termekjs .= '<div class="BlockSixth">'.$fieldSelect.'</div>';
        $termekjs .= '<div class="BlockTwoThird">'.$fieldInput.'</div>';
    } else if($fieldvalue=='string'){
        $fieldInput = InputCreators::inputcreator('termek_'.$fieldname, 'termek_'.$fieldname, 'textbox');
        $termekjs .= '<div class="BlockFiveSixth">'.$fieldInput.'</div>';
    }
    $termekjs .= '<div class="BlockMiniSpacer"></div>';
}

$termekjs='$("#fields").html(\''.$termekjs.'\');';

$orders = array();
foreach(array_keys($fields['termek']) as $field){
    $orders[$field] = $name['termek'][$field];
}
foreach(array('first_order','second_order','third_order') as $order){
    $termekjs.='var $el = $("#'.$order.'");
$el.empty();
$el.append($("<option></option>")
         .attr("value","").text("Kérem válasszon!"));';
    foreach($orders as $key => $value){
    $termekjs.='
    $el.append($("<option></option>")
         .attr("value", "'.$key.'").text("'.$value.'"));';
    }
}

return $termekjs;
