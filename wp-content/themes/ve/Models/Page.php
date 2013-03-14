<?php
/*
 * 
 */
class Page_Model{

    var $ID;
    var $title;
    var $slug;
    var $ancestor;
    
    public function __construct(){
        wp_reset_postdata();
        global $post;
        $this->ID         = $post->ID;
        $this->title      = $post->post_title;
        $this->slug       = $post->post_name;
        $this->ancestor   = end($post->ancestors);
        return $this;
    }
}

?>