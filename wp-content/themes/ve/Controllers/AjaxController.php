<?php 


class Ajax_Controller extends Rhino_Controller{
    
    public function rhino(){
        //actions on request
        
       // pre_dump($this);
    }
    public function map_action(){
        
        //pre_dump($this);
        
        
    }
    
    public function gallery_action(){
 
        extract($_GET);


        pre_dump($_GET);
        $images = get_posts( array(
            'post_type' => 'attachment', 
            'post_mime_type' => array('image/jpeg','image/png','image/jpeg'), 
            'showposts' => -1 ) );


        $ret = array();
        foreach( $images as $image )
        {
            $src = wp_get_attachment_image_src($image->ID, 'avatars');
            $actual_image_name      = trim(strtolower($image->post_title));
            $actual_image_url       = trim($src[0]);

            if($src[1]!=0||$src[2]!=0)
            {//use data-{whaterver}
            $suggest_results.= "<li class='select' img_title='$actual_image_url' img_id='$image->ID' fbase='$field_base' img_url='$actual_image_url'>
            <img src='$actual_image_url' title='$actual_image_name' width='$src[1]px' height='$src[2]px' />

            </li>";
            }

        }
        echo "<ul class='image-gallery-thumbs'>$suggest_results</ul>";

        die();
    }
    
}