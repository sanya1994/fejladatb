<?php

class InputCreators{
    private static function secureAttrs(&$attributes){
        $unset_attrs = array('id','name','value','type','default_value','multiple_value','parameter','validator','notFromUserInput');
        foreach(array_keys($attributes) as $key ){
            if(in_array($key, $unset_attrs)){
                unset($attributes[$key]);
            } else{
                if(is_array($attributes[$key])){
                    $attributes[$key] = array_map(function($value){return htmlspecialchars($value,ENT_QUOTES);},$attributes[$key]);
                } else{
                    $attributes[$key] = htmlspecialchars($attributes[$key],ENT_QUOTES);
                }
            }
        }
    }
    
    private static function getValue($userInputs,$form_type,$name,$attributes){
        if(!(isset($attributes['notFromUserInput']) && $attributes['notFromUserInput']) && isset($userInputs[$form_type][$name])){
            if(is_array($userInputs[$form_type][$name])){
                return array_map(function($value){return htmlspecialchars($value,ENT_QUOTES);},$userInputs[$form_type][$name]);
            } else{
                return htmlspecialchars($userInputs[$form_type][$name],ENT_QUOTES);
            }
        } else if(isset($attributes['value'])){
            if(is_array($attributes['value'])){
                return array_map(function($value){return htmlspecialchars($value,ENT_QUOTES);},$attributes['value']);
            } else{
                return htmlspecialchars($attributes['value'],ENT_QUOTES);
            }
        } else{
            return '';
        }
    }
    
    static function inputcreator($id, $name, $type, $attributes=array()){
        $multiple_value = isset($attributes['multiple_value']) ? $attributes['multiple_value'] : false;
        $form_type = isset($attributes['form_type']) ? $attributes['form_type'] : 'POST';
        switch ($type) {
            case 'file':
                $form_type = 'FILES';
                break;
            default:
                break;
        }
        $userInputs = array('POST' => &$_POST, 'GET' => &$_GET);
        
        $value = InputCreators::getValue($userInputs, $form_type, $name, $attributes);
        InputCreators::secureAttrs($attributes);
        
        $input ='<input id="'.$id.'" name="'.$name.($multiple_value ? '[]' : '').'" type="'.$type.'" value="'.$value.'"';
        foreach($attributes as $key => $val){
            $input.=' '.$key.'="'.$val.'"';
        }
        $input.='>';
        if($type=='file'){
            $input = '<div class="fileUpload">
                <span>Fájl feltöltése</span>
                '.$input.'</div>';
        }
        return $input;
    }
    
    static function selectcreator($id, $name, $attributes=array()){
        $multiple_value = isset($attributes['multiple_value']) ? $attributes['multiple_value'] : false;
        $form_type = isset($attributes['form_type']) ? $attributes['form_type'] : 'POST';
        
        $list = array_map(function($value){return htmlspecialchars($value,ENT_QUOTES);},$list);
        
        $userInputs = array('POST' => &$_POST, 'GET' => &$_GET);
        
        $value = InputCreators::getValue($userInputs, $form_type, $name, $attributes);
        if($multiple_value && !is_array($value)){
            $value = array($value);
        }
        InputCreators::secureAttrs($attributes);
        $select = '<select id="'.$id.'" name="'.$name.($multiple_value ? '[]" multiple' : '"');

        foreach($attributes as $key => $val){
            $select.=' '.$key.'="'.$val.'"';
        }
        $select.='><option value="">Kérem válasszon</option>';
        foreach($list as $key => $val){
            $select.= '<option value="'.$key.'" '.($multiple_value ?(in_array($key, $value) ? 'selected' : '') :($value==$key ? 'selected' : '')).'>'.$val.'</option>';
        }
        $select.='</select>';
        return $select;
        
    }
    
    static function textareacreator($id, $name, $attributes = array()){
        $multiple_value = isset($attributes['multiple_value']) ? $attributes['multiple_value'] : false;
        $form_type = isset($attributes['form_type']) ? $attributes['form_type'] : 'POST';
        
        $userInputs = array('POST' => &$_POST, 'GET' => &$_GET);
        
        $value = InputCreators::getValue($userInputs, $form_type, $name, $attributes);
        InputCreators::secureAttrs($attributes);
        
        $textarea = '<textarea id="'.$id.'" name="'.$name.($multiple_value ? '[]' : '').'"';
        foreach($attributes as $key => $val){
            $textarea.=' '.$key.'="'.$val.'"';
        }
        $textarea.='>'.$value.'</textarea>';
        return $textarea;
    }
    
    static function disabledInputCreator($id,$name,$attributes=array()){
        $form_type = isset($attributes['form_type']) ? $attributes['form_type'] : 'POST';
        $value = InputCreators::getValue(array(), $form_type, $name, $attributes);
        InputCreators::secureAttrs($attributes);
        
        $div = '<div id="'.$id.'" class="LikeInput"';
        foreach($attributes as $key => $val){
            $div.=' '.$key.'="'.$val.'"';
        }
        $div .= '>'.$value.'</div>';
        return $div;
    }
}