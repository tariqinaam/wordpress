<?php

/*
 * smrtrMsg using twiter bootstrap
 */
class smrtrMsg{
    
    static $dismiss = '<a class="close" data-dismiss="alert" href="#">&times;</a>';
    
    public static function success($msg){
        
        self::base($msg,'success');  
    }
    
    
    public static function danger($msg){
        
        self::base($msg,'danger');  
    }
    
    public static function fail($msg){
        
        self::base($msg,'danger');  
    }
    public static function alert($msg){
        
        self::base($msg);  
    }
    
    public static function info($msg){
        
        self::base($msg,'info');  
    }
    
    private static function base($msg,$class=''){
        
        $class = $class?" alert-$class":'';
        printf('<div class="alert alert-block%s">%s%s</div>',$class,$msg,self::$dismiss);
        
    }
    
    private static function baseFront($msg,$class=''){
        
        $class = $class?" alert-$class":'';
        printf('<div class="msg alert-block%s">%s</div>',$class,$msg);
        
    }
    
    public static function oops($title,$msg){
        
        printf('<div class="hero-unit"><h1>%s</h1>%s</div>',$title,$msg);
    }
    
    public static function hero($title,$msg, $class=''){
        
        printf('<div class="hero-unit %s"><h1>%s</h1>%s</div>',$class,$title,$msg);
    }
    
    
    public static function progress($pos){
    
        echo '<div class="progress"><div class="bar bar-success" style="width:'.$pos.'%;">'.$pos.'%</div></div>';
    }
    
    
    public static function error($msg){
        
        printf('<p class="errorMessage">%s</p>',$msg);
    }
    
    public static function ibro_success($msg){
        
        self::baseFront($msg,'success');  
    }
    public static function ibro_info($msg){
        
        self::baseFront($msg,'info');  
    }
    public static function ibro_fail($msg){
        
        self::baseFront($msg,'danger');  
    }
}
?>
