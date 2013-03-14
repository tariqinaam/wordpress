<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
 
 class Smrtr_WordPressImages extends smrtrElement{
    
    var $type='text';
    var $size= 50;
    
    function setHTML(){
        
        //get valid attributes
        $attributes = $this->getAttributes();
        $attr       = $this->constructAttributes($attributes);
        $val        = isset($attributes['value'])?$attributes['value']:0;
        $value      = $this->setVar($attr,'value');   //pre selected
        $img = wp_get_attachment_image_src($val);
                                    
        
        
        $html = sprintf('<table>
               <tr><td>
                        <a class="suggest suggest_img" value="%s">Choose an image</a>
                        <input id="%s" type="hidden" name="%s" %s/>
                   </td>
                   <td>
                    <div id="%s_img" class="gallery_thumb">',$this->name,$this->name,$this->name,$value,$this->name);
        
        if($img)
        $html .= sprintf('<img src="%s" width="50px" height="50px">',$img[0]);
        
        
        
        $html .='</div></td></tr></table>';
                    
        //return $return;
        //$return
        
        
        return $this->html = $html;
        
    }
    
    
    function gallery($name)
    {
        
        $name = "{$this->unique_name}_{$this->name_space}_$name";
        $name = $this->group($name);
        
        $postmeta = (int) get_post_meta($this->post_id,$name,true);
        
  
        $img = wp_get_attachment_image_src($postmeta);
                                    
                                   
        $return = '<table><tr><td><a class="suggest suggest_img" value="'.$name.'">Choose an image</a>
                <input id="'.$name.'" type="hidden" name="'.$name.'" value="'.$postmeta.'"/></td>
                <td><div id="'.$name.'_img" class="gallery_thumb">';
        if($postmeta)
        $return .="<img src='{$img[0]}' width='50px' height='50px'>";
        $return .='</div></td></tr></table>';
                    
        return $return;
                    
    }
    
}

?>
