<?php

/**
 * Smrtr Class helper for wordpress items
 */

class smrtr {
    
    
    
    /**
    * Are we in the admin section of wordpress
    * @param type $file_handler
    * @param type $post_id
    * @param type $setthumb 
    */
    
    public static function slug($string,$separator='-'){

        
        if(!in_array($separator,array('-','_')))
                return false;

        $replace = $separator==='-'?'_':'-';

        $string = str_replace($replace,$separator,$string);

        $string =  preg_replace('/[^A-Za-z0-9-]+/', $separator, strtolower($string)); 
        
        return trim($string,$separator);

    }
    
    
    public static function isBetween($int,$min,$max){
        return ($int>$min && $int<$max);
    }
    
    
        
    public static function printPHParr($arr){

        $ret = array();

        foreach($arr as $key => $val){

            if($key)
            $ret[$key] = "'$key' => $val";
        }
        $concat = implode(',',$ret);
        return "array($concat)";
    }
    

    public static function strtime($datetime_str)
    {
        list( $y, $m, $d, $h, $i, $s ) = preg_split( '([^0-9])', $datetime_str);

        return mktime($h, $i, $s, $m, $d, $y);
    }

    public static function redirect($param='redirect'){
            
         return isset($_REQUEST[$param])?$_REQUEST[$param]:'';
    }
    public static function datetime(){
        return date('Y-m-d H:i:s');
    }
    
    function shortenText($string,$max_size)
    {
        $new_str = $string;
        $len = strlen($string);
        if($len>$max_size)
        {
            $new_str = substr($string,0,$max_size-3)."<small>...</small>";
        }
        return $new_str;
    }
    
    static function isSerialised($str){
        
        $data = @unserialize($str);
        if ($str === 'b:0;' || $data !== false) {
            return true;
        }
        return false;
    }
}


 function args($pairs=array()){
        

    $urlParts = getURLparts();
    $path  = isset($urlParts['path'])?$urlParts['path']:'';
    $query = isset($urlParts['query'])?$urlParts['query']:'';
    $parts = queryToArray($query);

    unset($parts['paged']);
    $parts = array_merge($parts,$pairs);
    $string = arrayToQuery($parts);

    return sprintf('%s?%s',$path,$string);
}
 
function argsArr(){
        

    $urlParts = getURLparts();
    $query = isset($urlParts['query'])?$urlParts['query']:'';
    
    
   
    return queryToArray($query);

}

function queryToArray($string){

    $arr = explode('&',$string);


    $pairs = array();
    foreach($arr as $pair){
        
        
        $parts = explode('=',$pair);
       
        $pairs[$parts[0]] = isset($parts[1])?$parts[1]:'';

    }
    return $pairs;
}


function arrayToQuery($arr){


    $pairs = array();

    foreach($arr as $key => $val){

            if(isset($val)&&!empty($val))
            $pairs[$key] = "$key=$val";
            else $pairs[$key] = "$key";
        }
        return implode('&',$pairs);
}


function getURLparts(){

    return parse_url($_SERVER['REQUEST_URI']);  
}





function generateHiddenFields($arr=array(),$remove = array()){
    
    $hidden = array();
    foreach($arr as $key => $val){
        if(!in_array($key,$remove))
        $hidden[] = sprintf('<input type="hidden" name="%s" value="%s"/>',$key,$val);

    }
    return implode('',$hidden);
    
}




class smrtrCSV{

    
    /* 
     * getCSVLine
     * makes a line of CSV's 
     */
    private static function getCSVLine( &$vals, $key, $filehandler ){
        fputcsv( $filehandler, $vals );
    }
    
    /*
     * 
     * 
     */
    public static function serve($filename,$rows){
        
        header( 'Content-type: text/csv' );
	header( 'Content-Disposition: attachment; filename="'.$filename.'-('.date( 'YmdHis' ).').csv"' );
	header( 'Pragma: no-cache' );
	header( 'Expires: 0' );
        
        $outstream = fopen( "php://output", 'w' );
        array_walk( $rows, 'smrtrCSV::getCSVLine', $outstream );
        fclose( $outstream );
        die;
    }
    
}
