<?php

/**
 * Add global css styles to admin
 */


/**
 * Smrtr Class helper for wordpress items
 */

class smrtrWP {
    
    
    
    /**
    * Are we in the admin section of wordpress
    * @param type $file_handler
    * @param type $post_id
    * @param type $setthumb 
    */
    
    public static function isSite(){
        
        if($_SERVER['PHP_SELF']==='index.php')
            return true;
        
        
    }
    
    public static function stringGen($length=7,$chars=''){
        
        $length =$length==0?10:$length;
        
        $c = "";
        $chars = $chars?$chars:"abcdefghijklm78y98npqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < $length; $i++)
        {
            $c .= substr($chars, rand() % strlen($chars), 1);
        }
        return $c;
        
    }
    
    
    public static function login( $user ) {
        $username   = $user;
        // log in automatically
        if ( !is_user_logged_in() ) {
            $user = get_userdatabylogin( $username );
            $user_id = $user->ID;
            $user_login = $user->user_login;
            wp_set_current_user( $user_id, $user_login );
            wp_set_auth_cookie( $user_id );
            do_action( 'wp_login', $user_login );
        }     
    }

    
    public static function logout() {
        
        
        if(isset($_REQUEST['logout'])){
            
            wp_logout();
            header('location: /login');
            die;
        }
        
    }

    
    public static function get_table_var($table,$field,$where,$key){
        
        global $wpdb;
        $sql = sprintf('select %s from %s where %s="%s"',$field,$table,$where,$key);
        return $wpdb->get_var($wpdb->prepare($sql));
      
    }
    
    
 
    
    
    
    public static function genUniqueID($col,$table,$length=7,$chars=''){
     
        global $wpdb;
        
        $chars = $chars?$chars:'12493875642956';
        $exists = true;
        do{

            $guid = self::stringGen($length,$chars);

            $sql = "SELECT COUNT($col) as the_amount FROM $table WHERE $col = '$guid'";
         
            $exists = (boolean) $wpdb->get_var($wpdb->prepare($sql));
            //$wpdb->show_errors();
            //$wpdb->print_error();

        } while (true == $exists);

        return $guid;
    }
    
    
    public static function rowExists($table,$col,$data,$format='%d'){

        global $wpdb;
        
        $sql = sprintf("select count(1) from $table where $col='$data'");
        
        
        $result = $wpdb->get_var($wpdb->prepare($sql));
        
        //$wpdb->show_errors();
        //$wpdb->print_error();
        return (boolean)$result;
    }
    public static function updateRow($table,$set,$where,$set_format,$where_format){

        global $wpdb;
        $result = $wpdb->update($table,$set,$where,$set_format,$where_format);
  
        return $result;
    }
    public static function getRow($table,$col,$data,$select='*'){

        global $wpdb;
        $res =  $wpdb->get_row($wpdb->prepare("select $select from $table where $col='$data'"));
       
        if($res){
            
            
            if(count(get_object_vars($res))==1){
                
                return $res->{$select};
                
            }else return $res;
        }
        
        return false;
        
    }
    
    
   
    
    function image($id,$size,$class=false)
    {

        if(!$id)
            return;

        $attr = wp_get_attachment_image_src($id,$size);


        if(!$attr)
            return;

        $img_post = get_post($id);

        $name   = str_replace(" ","_",$img_post->post_name);
        $width  =  $attr[1];
        $height =  $attr[2];
        $src    =  $attr[0];

        //The following fails on php platforms 
        //if(!is_array(getimagesize($src)))
        //return;
        $content = $img_post->post_content;

        $classes = $class==false&&count($class)>0?"":"class='".implode(" ",$class)."'";
        return "<img $classes src='$src' alt='$content' height='$height' width='$width'/>";
    }

}




function uwsu_setup_theme()
{
    
    //Register Image dimensions
    add_image_size( 'avatar',128,128,true);
    add_image_size( 'thumbnail',45,45,true);
    add_image_size( 'homeCarousel',609,301,true);
    add_image_size( 'homeSpotlight',310,301,true);
    add_image_size( 'landingCarouselLarge',573,371,true);
    add_image_size( 'landingCarouselThumb',126,85,true);
    add_image_size( 'pageImg',709,267,true);
    add_image_size( 'commentsAvatar',94,94,true);
    add_image_size( 'listingThumb',160,108,true);
    
    // Register Nav Menus
    register_nav_menus(
      array(

          //'tools' => 'Tools',
          'main' => 'Main',
          'footer1' => 'Footer1',
          'footer2' => 'Footer2',
          'footer3' => 'Footer3',
          'footer4' => 'Footer4',
          'footer5' => 'Footer5'
      ));

    
}





function add_template_columns($defaults) {
    $defaults['template'] = __('Template In Use');
    return $defaults;
}

function manage_custom_template_column($column_name, $id) {
    
   
    if( $column_name == 'template' ) {
  
        $template_name = get_post_meta($id, '_wp_page_template', true);
        $i=0;
       
        if(strlen($template_name)==0||$template_name==''||$template_name=='default')
        {
            $arr = array(
                'home',
                'sitemap');
            $post_name = get_post($id)->post_name;
            $temps = in_array($post_name,$arr)?'<b>Special Page</b>':'Default';
                    
            printf("<p>%s</p>",$temps);
            return;
            
        }else{
            
           
            if($template_name=='template-listing.php')
            {
           
               $posts_listing = unserialize(get_post_meta($id,'uwsu_listing_options_post_type', true));
               $posts_cats = unserialize(get_post_meta($id,'uwsu_listing_options_post_cats', true));

               $posts = array();
               $taxes = array();

               foreach($posts_listing as $item){

                   $posts[] = sprintf('<a href="%s/wp-admin/edit.php?post_type=%s">%s</a>',get_bloginfo('url'),$item,$item);
               }

               foreach($posts_cats as $item){

                   $term = get_term_by("slug",$item,"category");

                   $taxes[] = sprintf('%s',$term->name);
               }

               $post_string = (count($posts)>0)?implode(', ',$posts):'any';
               $taxs_string = (count($taxes)>0)?implode(', ',$taxes):'none';    


               printf('<p>Listing <b>posts</b>:<i>%s</i> | <b>cats</b>:<i>%s</i></p>',$post_string,$taxs_string);
                  
               
            }else
            {
                $template_name = str_replace("template", "",$template_name);
                $template_name = str_replace("-", " ",$template_name);
                $template_name = str_replace(".php", "",$template_name);
                
                $template_name = ucwords($template_name);

                echo "<p>$template_name page</p>";

            }
        }
       
    }
}


function add_parent_page($defaults) {
    $defaults['parent_page'] = __('Parent Page');
    return $defaults;
}
function manage_parent_page_columns($column_name, $id) {
    if( $column_name == 'parent_page' ) {
        $post = get_post($id);
        $post_parent = $post->post_parent;
        $i=0;
       
        if($post_parent==$id||$post_parent==0)
        {
            echo "<p>Orphan</p>";
            return;
            
        }else{
            
               
            $post_parent_object = get_post($post_parent);
            $a = get_bloginfo('url')."/wp-admin/post.php?post={$post_parent_object->ID}&action=edit";

            echo "<p> <a href='$a'>$post_parent_object->post_title</a></p>";
            return;
        }
       
    }
}

/**
 * Allow uploads
 * @param type $file_handler
 * @param type $post_id
 * @param type $setthumb 
 */
function insert_attachment($file_handler,$post_id) {
	// check to make sure its a successful upload
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) 
        return false;
        
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$attach_id = media_handle_upload( $file_handler, $post_id );

	return $attach_id;
}


?>
