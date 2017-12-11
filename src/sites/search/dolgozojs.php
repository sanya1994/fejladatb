<?php
$dolgozojs='';
foreach($fields['dolgozo'] as $fieldname => $fieldvalue){
    $fieldLabel = '<label for="dolgozo_'.$fieldname.'">'.(isset($name['dolgozo'][$fieldname]) ? $name['dolgozo'][$fieldname] : $fieldname).':&nbsp;</label>';
    $dolgozojs .= '<div class="BlockSixth RightAlign">'.$fieldLabel.'</div>';
    if(is_array($fieldvalue) && $fieldname!='munkakor'){
        $fieldSelect = InputCreators::selectcreator('dolgozo_'.$fieldname, 'dolgozo_'.$fieldname, array_combine($fieldvalue, $fieldvalue));
        $dolgozojs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    } else if($fieldname == 'munkakor'){
        $mindenmunkakor = array();
        foreach($fieldvalue as $tipus => $munkakor){
            $mindenmunkakor = array_merge($mindenmunkakor, $munkakor);
        }
        $fieldSelect = InputCreators::selectcreator('dolgozo_'.$fieldname, 'dolgozo_'.$fieldname, array_combine($mindenmunkakor, $mindenmunkakor));
        $dolgozojs .= '<div class="BlockFiveSixth">'.$fieldSelect.'</div>';
    } else if($fieldvalue=='biggerint'){
        $fieldSelect = InputCreators::selectcreator('dolgozo_'.$fieldname.'_operator', 'dolgozo_'.$fieldname.'_operator', $booloperators);
        $fieldInput = InputCreators::inputcreator('dolgozo_'.$fieldname, 'dolgozo_'.$fieldname, 'number');
        $dolgozojs .= '<div class="BlockSixth">'.$fieldSelect.'</div>';
        $dolgozojs .= '<div class="BlockTwoThird">'.$fieldInput.'</div>';
    } else if($fieldvalue=='string'){
        $fieldInput = InputCreators::inputcreator('dolgozo_'.$fieldname, 'dolgozo_'.$fieldname, 'textbox');
        $dolgozojs .= '<div class="BlockFiveSixth">'.$fieldInput.'</div>';
    }
    $dolgozojs .= '<div class="BlockMiniSpacer"></div>';
}

$dolgozojs='$("#fields").html(\''.$dolgozojs.'\');';

$orders = array();
foreach(array_keys($fields['dolgozo']) as $field){
    $orders[$field] = $name['dolgozo'][$field];
}
foreach(array('first_order','second_order','third_order') as $order){
    $dolgozojs.='var $el = $("#'.$order.'");
$el.empty();
$el.append($("<option></option>")
         .attr("value","").text("Kérem válasszon!"));';
    foreach($orders as $key => $value){
    $dolgozojs.='
    $el.append($("<option></option>")
         .attr("value", "'.$key.'").text("'.$value.'"));';
    }
}

return $dolgozojs;