<?php 


class PageHome_Controller extends Rhino_Controller{
    
    
    public function rhino(){
        
        global $wpdb;
        $this->mwayi = 'mwayi';
        
        $this->page_model = new Page_Model();
        //$this->all_posts = $wpdb->get_results("select * from {$wpdb->prefix}posts");
        
        //pre_dump($this);
    }
    
    
    public function getPostID(){
        
        return $this->ID;
             
    }
    
    
    public function getCarousel(){
        #
        global $wpdb;
        //$this->all_posts = $wpdb->get_results("select * from {$wpdb->prefix}posts");
        
        echo 'tariq';
        //
    }
}