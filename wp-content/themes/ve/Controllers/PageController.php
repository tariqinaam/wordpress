<?php 


class Page_Controller extends Rhino_Controller{
    
    
    public function rhino(){
    
        //$this->post_title = 'this is the title for the page';
        //new Listing_Model;
        
        pre_dump($this);
             
    }
    
    public function getPostID(){
        
        return $this->ID;
             
    }
    
}