<?php

class MyTable{    
    private $data = array();
    private $htmlencoding = array();
    
    private $headers = array();
    
    private $havefilter;
    
    private $id;
    
    private $filterinput;
    private $pagerinput;
    
    private $formID;
    
    private $limit;
    private $page;
    private $count;
    
    private $bottombuttons = array();

    function __construct($id) {
        $this->id = $id;
        $this->filterinput = "mytable_filter_".$id;
        $this->pagerinput = "mytable_pager_".$id;
        $this->page = isset($_POST[$this->pagerinput]) && !isset($_POST[$this->filterinput.'_update']) ? $_POST[$this->pagerinput] : 1;
    }
    
    function addHeader(MyTableHeader $header){
        if($header->getFilter()){
            $this->havefilter = true;
        }
        $this->headers[$header->getName()] = $header;
        return $this;
    }
    
    function getValue($row, $col){
        return isset($this->data[$row][$col]) ? $this->data[$row][$col] : NULL;
    }
    
    function setValue($row, $col, $value,$htmlencoding = true){
        if(!$htmlencoding){
            $this->htmlencoding[$row][$col] = false;
        }
        $this->data[$row][$col] = $value;
        return $this;
    }
    
    function deleteCol($col){
        foreach($this->data as &$row){
            if(isset($row[$col])){
                unset($row[$col]);
            }
        }
    }
    
    function getRowNum(){
        return count($this->data);
    }
    
    function haveFilterTo($name){
        return isset($_POST[$this->filterinput.'_'.$name]) && $_POST[$this->filterinput.'_'.$name]!='';
    }
    
    function getHeaders() {
        return $this->headers;
    }
    
    function setLimit($limit) {
        $this->limit = $limit;
    }

    function setCount($count) {
        $this->count = $count;
    }
    function setFormID($formID) {
        $this->formID = $formID;
    }
    
    function addBottomButton($bottomButtonname){
        $this->bottombuttons[] = $bottomButtonname;
    }
    
    function getPage() {
        return $this->page;
    }
    
    function generate(){
        $table =
                '<div class="BlockFull">
                    <div style="float:right;">
                    '.InputCreators::inputcreator($this->filterinput."_update", $this->filterinput."update", "submit",array('value'=>"Frissítés", 'notFromUserInput' => true)).'
                    </div>
                    <div class="BlockSpacer"></div>
                </div>';
        $table .= '<table class="MyTable">';
        $table .= '<thead><tr>';
        foreach($this->headers as $header){
            $table.='<th>'.htmlspecialchars($header->getValue(),ENT_QUOTES).'</th>';
        }
        $table .= '</tr></thead><tbody>';
        if($this->havefilter){
            $table .= '<tr class="filter">';
            foreach($this->headers as $header){
                $table.='<td ';
                if($header->getFilter()){
                    $table.='data-th="'.$header->getValue();
                }
                $table.='">';
                if($header->getFilter()){
                    if($header->getType()=='list'){
                        $table.=InputCreators::selectcreator(
                                $this->filterinput.'_'.$header->getName(),
                                $this->filterinput.'_'.$header->getName(),
                                $header->getParam());
                    } else{
                        $inputattrs = $header->getInputType();
                        $table.=InputCreators::inputcreator(
                                $this->filterinput.'_'.$header->getName(),
                                $this->filterinput.'_'.$header->getName(),
                                $inputattrs['type'],
                                isset($inputattrs['attributes']) ? array_merge($inputattrs['attributes'],$header->getParam()):$header->getParam());
                    }
                }
                $table.='</td>';
            }
            $table .= '</tr>';
        }
        if(!empty($this->data)){
            foreach($this->data as $rid => $row){
                $table.='<tr>';
                foreach($row as $cid => $cell){
                    if(isset($this->htmlencoding[$rid][$cid]) && !$this->htmlencoding[$rid][$cid]){
                        $value = $cell;
                    } else{
                        $value = htmlspecialchars($cell,ENT_QUOTES);
                    }
                    $table.='<td>'.$value.'</td>';
                }
                $table.='</tr>';
            }
        } else{
            $table.='<tr><td colspan="'.count($this->headers).'">Nem sikerült találni találatot. Próbálja meg más keresési feltételekkel.</td></tr>';
        }
        $table .= '</tbody></table>';
        if(isset($this->limit)){
            $table .=
                '<div class="BlockSpacer"></div>
                    <div class="BlockFull">';
                    foreach($this->bottombuttons as $bb){
                        $table.='<div style="float:left;">
                            '.InputCreators::inputcreator($bb['name'], $bb['name'], "submit",array('value'=>$bb['alias'], 'notFromUserInput' => true)).'
                            </div>';
                    }
             $table.='<div style="float:right;">
                     / '.(intval(($this->count-1)/$this->limit)+1).'
                    </div>
                    <div style="float:right; max-width:50px">
                    '.InputCreators::inputcreator($this->pagerinput, $this->pagerinput, "number",array('step'=>1, 'min'=>1,'value'=>$this->page,'style'=>'text-align:right;','max'=>(intval(($this->count-1)/$this->limit)+1))).'
                    </div>
                    <div class="BlockSpacer"></div>
                </div>';
        }
        return $table;
    }
}

class MyTableHeader{
    private $name;
    private $type = 'string';
    private $filter = false;
    private $value;
    private $param = array();
    
    function __construct($name,$value) {
        $this->name = $name;
        $this->value = $value;
    }
    
    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getFilter() {
        return $this->filter;
    }
    
    function getValue() {
        return $this->value;
    }
    
    function getParam() {
        return $this->param;
    }
        
    function setType($type) {
        $this->type = $type;
        return $this;
    }

    function setFilter($filter) {
        $this->filter = $filter;
        return $this;
    }
    
    function setParam($param) {
        $this->param = $param;
        return $this;
    }

    function getInputType(){
        switch ($this->type) {
            case 'string':
                return array('type'=>'textbox');
            case 'integer':
                return array('type'=>'number','attributes' => array('step'=>1));
            case 'real':
                return array('type'=>'number');
            case 'date':
                return array('type'=>'date');
            default:
                return array('type'=>'textbox');
        }
    }
}