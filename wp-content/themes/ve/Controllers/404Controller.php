<?php 


class _404_Controller extends Rhino_Controller{
    
    public function rhino(){
        $this->ID           = -404;
        $this->post_title   = 'Page not Found';
        $this->post_content = 'Sorry this page was not found';
        
        //pre_dump($this);
    }
    
    public function getPostID(){

        //$this->getBlock('header',array('wow'=>'great'));
        return $this->ID;
             
    }
    
}