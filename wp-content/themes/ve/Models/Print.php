<?php

class Print_Model{
    
    /**
     * Used to print out a smrtr table and all the associated keys
     * 
     * @meta 
     */
    public static function WPAdmin_Metabox(array $fields,$meta=true) {
     
        $class  = 'odd';
        $content = '';
        
        foreach ($fields as $field){

            $class = $class==='even'?'odd':'even';
            
            $content .= sprintf('<tr id="%s" class="top-border %s">',$field->id,$class);
            $content .= sprintf('<td valign="top" class="table-label">%s</td>',$field->title);
            $content .= sprintf('<td valign="top">%s</td></tr>',$field->html);

            //add a description
            if (isset($field->description)&&$field->description)
                    $content .= sprintf('<tr class="%s"><td class="table-label">&nbsp;</td><td class="desc" valign="top">%s</td></tr>',$class,$field->description);
       
            if($meta)
            $content .= sprintf('<input type="hidden" name="save_meta_data[%s]" value="%s"/>',$field->name,$field->name);
      
        }
        
        return '<table class="form-table">'.$content.'</table>';
    }
    
}