<?php

class Smrtr_Select extends smrtrElement{
    
    var $type='select';
    
    public function setHTML() {
        
        
        //get valid attributes
        $attr    = $this->getAttributes();
        
        $value   = $this->setVar($attr,'value');
        $default = $this->setVar($attr,'default','Please select');
        $options = $this->setVar($attr,'options',array());
       
        $attributes = $this->constructAttributes($attr,array('value'));
        return $this->html = sprintf('<select %s><option value="">%s</option>%s</select>',implode(' ',$attributes),$default,$this->options($options,$value));
       
    }
    
    private function options($values,$current=''){
        
        $ret = '';
        if(!empty($values)){

            foreach($values as $key => $label){
                $selected = $current==$key?" selected='selected' ":" ";
                $ret.= sprintf('<option%s value="%s">%s</option>',$selected,$key,$label);
            }
        }
        return $ret;
    }
    
}

?>
