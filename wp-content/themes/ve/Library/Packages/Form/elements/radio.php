<?php


class Smrtr_Radio extends smrtrElement{
    
    var $type='radio';
    
    public function setHTML() {
        
        $return ='';
        $attr = $this->getAttributes();
        
        //get selected items
        $selected  = $this->setVar($attr,'value');
        $options   = $this->setVar($attr,'options',array());
        $id        = $this->setVar($attr,'id');
        
        $attributes = $this->constructAttributes($attr,array('value'));
        
        foreach($options as $val => $label){
            //reset the values 
            $attributes['value'] = sprintf('value="%s"',$val);
            $attributes['id']    = sprintf('id="%s_%s"',$id,$val);
            $for = sprintf('for="%s_%s"',$id,$val);

            //check selected values
            
            $attributes['checked'] = $val==$selected?'checked="checked"':'';

            //collect fields
            $return.= sprintf('<li class="field-item"><label %s><input %s/>%s</label></li>',$for,implode(' ',$attributes),$label);
        }

        return $this->html = sprintf('<ul id="%s" class="field-group %s">%s</ul>',$id,$this->class,$return);
    }
}

?>
