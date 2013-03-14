<?php


class smrtrElement extends smrtrFormBase{
    
    var $name;
    var $type = 'text';
    var $value = '';
    var $errors = '';
    var $description = '';
    var $title = '';
    var $required = '';
    var $validation = '';
    var $data = '';
    var $class ='';
    
    
    function __construct($name){
        
        if(!$name)
            return false;
        $this->setName($name);
        $this->setID($name);
        
        $this->setHTML();
        return $this;
    }
    
    
    
    /**
     * Not yet implemented
     * Add label to an input. checkboxes and radios to the right
     * Everything else to the left. Elemenst can still be floated tho
     
    function addLabel($field,$input){

        $type     = $this->setProp($field,'type');
        $id       = $this->setProp($field,'id');
        $label    = $this->setProp($field,'label');
        //TODO: id not really important
        
        if(isset($label)&&strlen($label)>0){
            $id = sprintf(' for="%s"',$id);
        }else return $input;
        
        if(in_array($type,array('checkbox','radio'))){ 
            $input = sprintf('<label class="%s"%s>%s<span>%s</span></label>',$type,$id,$input,$label);
        }else{
            $input = sprintf('<label class="%s"%s><span>%s</sapn>%s</label>',$type,$id,$label,$input);
        }
        return $input;
    }*/
}




?>
