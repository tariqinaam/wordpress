<?php


class Smrtr_Checkbox extends smrtrElement{
    
    var $type='checkbox';
    
    public function setHTML() {
        
        //set up attributes
        $return    = '';
        $attr      = $this->getAttributes();
        $selected  = $this->setVar($attr,'value');   //pre selected
        $options   = $this->setVar($attr,'options',array()); //grab option pairs
        
        
        if(!isset($this->id))
            return false;
        
        
        //render the attribute pairs
        $attributes = $this->constructAttributes($attr,array('value'));
   
        
        if(is_array($options)&&count($options)>0){
            
            $no_checkboxes = count($options);
            
            
            //loop through options 
            foreach($options as $val => $label){
                
                //set up basic attributes for each checkbox
                $attributes['value'] = sprintf('value="%s"',$val);
                $attributes['id']    = sprintf('id="%s_%s"',$this->id,$val);
                
                //for label
                $for = sprintf('for="%s_%s"',$this->id,$val);
                
                switch($no_checkboxes){
                    
                    case 1:// if there is only one checkbox
                        $attributes['name'] = sprintf('name="%s"',$this->name);
                        $attributes['checked'] = $val==$selected?'checked="checked"':'';
                        $active = $val==$selected?'active':'';
                        $return.= sprintf('<li class="field-item %s"><label %s><input %s/>%s</label></li>',$active,$for,implode(' ',$attributes),$label);
                        break;
                    
                    default:
                        $attributes['name'] = sprintf('name="%s[]"',$this->name);
                   
                        if(is_array($selected)){
                            $attributes['checked'] = in_array($val,$selected)?'checked="checked"':'';
                            $active = in_array($val,$selected)?'active':'';
                        }else $active ='';
                        $return.= sprintf('<li class="field-item %s"><label %s><input %s/>%s</label></li>',$active,$for,implode(' ',$attributes),$label);
                        break;
                }
            }
        }
        
        $html = $this->html = sprintf('<ul id="%s" class="field-group %s">%s</ul>',$this->id,$this->class,$return);
        
        if($this->before)
        $html =$this->before.$html;
        
        if($this->after)
        $html.=$this->after;
        
        return $html;
    }
}



?>
