<?php

/*
 * Hide the admin notice update.
 */


class Ibro_Application_Listing extends Repeater_Model{
    
    function _link($obj){
     
        return get_permalink($obj->ID);
    }
    
    function _title($obj){
     
        return $obj->post_title;
    }
    
    function _date($obj){
     
        return date('d/m/Y',RR_timestamp_from_string($obj->post_date));
    }
    
    function _image($obj){
        
        $id  = get_post_meta($obj->ID,'uwsu_page_image',true);
        $img = smrtrWP::image($id,'listing');
        
        return sprintf('<a href="%s">%s</a>',get_permalink($obj->ID),$img);
    }
    
    function _content($obj){
     
        $html = strip_tags($obj->post_content);
        return smrtr::shortenText($html,300);
    }
}




class Ibro_General_Listing extends Repeater_Model{
    
    function _link($obj){
     
        return get_permalink($obj->ID);
    }
    
    function _title($obj){
     
        return $obj->post_title;
    }
    
    function _date($obj){
     
        return date('d/m/Y',RR_timestamp_from_string($obj->post_date));
    }
    
    function _image($obj){
        
        $id  = get_post_meta($obj->ID,'uwsu_page_image',true);
        $img = smrtrWP::image($id,'listing');
        
        return sprintf('<a href="%s">%s</a>',get_permalink($obj->ID),$img);
    }
    
    function _content($obj){
     
        $html = strip_tags($obj->post_content);
        return smrtr::shortenText($html,300);
    }
}


/*
 * Handle downloads
 */
function ibro_download_file(){
    
    if(isset($_POST['download_file'])){
        
        $path_info = pathinfo($_POST['download_path']);
        
        header('Content-disposition: attachment; filename='.$path_info['filename'].'.'.$path_info['extension']);
        header('Content-type: application/'.$path_info['extension']);
        
        $fsize = filesize($_POST['download_path']);
        header("Content-Length: ".$fsize);
        readfile($_POST['download_path']);
       
    }
   
}