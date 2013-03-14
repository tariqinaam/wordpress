<?php
/**
 * A simple form class 
 * - Validates,
 * @Author Mwayi E. Dzanjalimodzi
 */

define('smrtrFormAbsPath',str_replace('\\','/',dirname(__FILE__)));

function smrtrFormLoad($folder){
    
    $not_allowed = array('.','..');

    $library_folder = smrtrFormAbsPath.$folder;

    $files = scandir($library_folder);

    foreach($files as $file){
        if(!in_array($file, $not_allowed)){

            if($file!='readme.txt'){
                require_once($library_folder.'/'.$file);
            }
        }

    }
    
}


include smrtrFormAbsPath.'/core/base.php';
include smrtrFormAbsPath.'/core/form.php';
include smrtrFormAbsPath.'/core/element.php';
include smrtrFormAbsPath.'/core/validation.php';


smrtrFormLoad('/elements');
?>