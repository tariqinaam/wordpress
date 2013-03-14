<?php 


class Blog_Controller extends Rhino_Controller{
    
    
    public function rhino(){
 
        
        $this->post_title = 'Blog';
        $this->slug  = isset($this->params[1])&&strtolower($this->params[1])!='page'?$this->params[1]:false;
        $this->post  = $this->getPost($this->slug);
        
       
        if($this->post){
            
            $this->setLayout('/blog/detail');
            
            $this->post_title = $this->post->post_title;
            
        }elseif(!isset($this->params[1])||(isset($this->params[1])&&$this->params[1]=='page')){
        
            $this->setLayout('/blog/listing');
            $this->setListing();
            
        }else{
            
            $this->setLayout('/blog/404');
        }
    }
    
    
    
    public function setListing(){
        
        global $wpdb;
         
        
        $this->rrp   = 4;
        $this->page  = isset($this->params[2])?$this->params[2]:1;
        $this->count = $wpdb->get_var("select count(1) from {$wpdb->prefix}posts where post_type='post' ");#
        $this->pages = ceil($this->count/$this->rrp);


        //lets work out the limits
        $this->offset= ($this->page-1)*$this->rrp;
        $this->range = $this->rrp;


        $pagination = new Pagination_Model($this->pages,$this->page,3);
        $this->pagination = $pagination->no;

        $this->posts = (object)$wpdb->get_results("select * from {$wpdb->prefix}posts where post_type='post' limit $this->offset,$this->range");
        
        
    }
    
    /*
     * Get the post
     */
    public function getPost($slug){
        
        if($slug)
        $res = new WP_Query( "name=$slug" );
        
        if(isset($res->post))
            return $res->post;
        else return false;
    }
    
    
}