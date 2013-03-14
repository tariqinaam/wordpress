<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
 
 class Smrtr_Hidden extends smrtrElement{
    
    var $type='hidden';
    var $size= 50;
    
    function setHTML(){
        
        //get valid attributes
        $attributes = $this->getAttributes();
        
        //arrange attributes
        $attributes = $this->constructAttributes($attributes);
        $this->html = sprintf('<input %s/>',implode(' ',$attributes)); 
        
        
        return $this->html;
        
    }
    
    
    
}

?>
