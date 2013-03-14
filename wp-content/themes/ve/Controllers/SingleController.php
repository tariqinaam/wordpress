<?php 


class Single_Controller extends Rhino_Controller{
    
    
    public function rhino(){
        
     
        //pre_dump($this);
        
        header("location: /blog{$_SERVER['REQUEST_URI']}");
        die;
        
    }
    
}