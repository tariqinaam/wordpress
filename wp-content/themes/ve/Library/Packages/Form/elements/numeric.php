<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
 
 class Smrtr_Numeric extends smrtrElement{
    
    var $type='text';
    var $size= 50;
    
    function setHTML(){
        
        //get valid attributes
        $attributes = $this->getAttributes();
        
        //arrange attributes
        $attributes = $this->constructAttributes($attributes);
        $this->html = sprintf('<input %s/>',implode(' ',$attributes)); 
        
        
        if($this->before)
        $this->html = $this->before. $this->html;
        
        if($this->after)
        $this->html.= $this->after;
        
        if($this->after||$this->before)
        $this->html = sprintf('<div class="input-append">%s</div>',$this->html); 
        
        return $this->html;
        
    }
    
    
    
}

?>
