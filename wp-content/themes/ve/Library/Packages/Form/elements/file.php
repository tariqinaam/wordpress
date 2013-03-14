<?php
class Smrtr_File extends smrtrElement{
    
    var $type='file';
    var $class='file-uploader';
    var $value = 'no-file-selected';
    var $formats=array('doc','docx','pdf','txt');
    var $description ='';
    var $max_filesize =2000000;
    var $min_filesize =2000000;
    
    
    function setHTML(){
        
        
        $this->description = sprintf('<b>Allowed formats (%s); max file size %s</b>',implode(', ',$this->formats),$this->bytes($this->max_filesize));
        //get valid attributes
        $attributes = $this->getAttributes();
        
        
        
        //arrange attributes
        $attributes = $this->constructAttributes($attributes);
        
        
        $file_selected = $this->value!='no-file-selected'?
                sprintf('<input name="stored_%s" type="text" readonly class="file-name disabled" value="Uploaded File: %s"/>
                        <br/><br/><input type="checkbox" name="check_%s" checked value="true"/>&nbsp;&nbsp;Uncheck to discard. Or overwrite by uploading another. <br/><br/>',$this->name,$this->value,$this->name):'';
        $this->html    = sprintf('%s<input %s/>',$file_selected,implode(' ',$attributes)); 
        
        
        if($this->before)
        $this->html = $this->before. $this->html;
        
        if($this->after)
        $this->html.= $this->after;
        
        if($this->after||$this->before)
        $this->html = sprintf('<div class="input-append">%s</div>',$this->html); 
        
        return $this->html;
        
    }
    
    
    
    public static function bytes($bytes, $force_unit = NULL, $format = NULL, $si = TRUE)
    {
        // Format string
        $format = ($format === NULL) ? '%d%s' : (string) $format;

        // IEC prefixes (binary)
        if ($si == FALSE OR strpos($force_unit, 'i') !== FALSE)
        {
            $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
            $mod   = 1024;
        }
        // SI prefixes (decimal)
        else
        {
            $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
            $mod   = 1000;
        }

        // Determine unit to use
        if (($power = array_search((string) $force_unit, $units)) === FALSE)
        {
            $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
        }

        return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
    }
}

?>
