<?php

class Smrtr_Textarea extends smrtrElement{
    
    var $type='textarea';
    var $cols=50;
    var $rows=3;
    
    
    public function setHTML() {
        
        //get valid attributes
        $attr = $this->getAttributes();
        
        $value  = $this->setVar($attr,'value');
        
        $attributes = $this->constructAttributes($attr,array('value'));
        
        return $this->html = sprintf("<textarea %s>%s</textarea>", implode(' ',$attributes),$value);
    }
    
}
 
?>
