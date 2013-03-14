<?php 
/**
 * Rhino Repeater.
 * 
 * The repeater loops through any object and allows you to set a template filled with 
 * expressions that need to be evaluated to populate the the repeater.
 * 
 * You can additionally handle your own expressions by extending the class
 */


class Repeater_Model{
    
    var $callers = array();
    var $repeaterHTML = '';
    var $keys = array();
    var $obj;
    
    
    function __construct( $obj){
        
        $this->obj = $obj;
        return $this;
    }

    function __call($method,$obj){
        
        $obj = $obj[0];
        //unset the o offest
        $method = substr_replace($method,'',0,1);
        
        if(isset($obj->$method))
            return $obj->$method;
        
        return '';
    }
    
    function setType($type){
        
        $this->type = $type;
        return $this;
    }

    function render(){
        
        $html = array();
        foreach($this->obj as $obj){
            
            $repeater = $this->repeaterHTML;
            
            foreach($this->callers as $caller => $replace){
                
                $method = "_$caller";
                
                $res = $this->{$method}($obj);
                $repeater = str_replace($replace,$res,$repeater);
                
            }
            
            $html[] = $repeater;
        }
        
        return implode('',$html);
    }
    
    function setTemplate($html){
        
        $this->repeaterHTML = $html;
        $this->split();
        return $this;
    }
    
    function split(){
        
        preg_match_all('/{{[a-z_]+}}/',$this->repeaterHTML,$matches);
        
     
        $matches = $matches[0];
  
        foreach($matches as $match){
            
            $caller  = str_replace('{{','',$match);
            $caller  = str_replace('}}','',$caller);
            $this->callers[$caller] = $match;
        }
        
        
        return $this;
    }
    //function 
}