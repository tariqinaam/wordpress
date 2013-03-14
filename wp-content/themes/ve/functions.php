<?php  
session_start(); 
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
include_once 'inc/lib/class/paginator.class.php';
include 'demo.php';

//Absolute path of template directory
define('abs_temp',   str_replace('\\','/',dirname(__FILE__)));

/*
 * Lets automaticaly Load uo the scripts
 */
function Rhino_Load_Scripts($files,$path,$require=true){
 
    foreach($files as $file){

        $file = $path.'/'.$file;
 
        if(file_exists($file)){
            
            $parts = pathinfo($file);
            
            if($parts['extension']==='php'){
                if($require===false)
                include_once($file);
                else require_once($file);
            }
        }

    }
}



//Autoload up the the scripts directory.
//The script direcory should not contain any instantiations
define('abs_configs',abs_temp.'/Configs');
define('abs_scripts',abs_temp.'/Library/Scripts');
define('abs_core'   ,abs_temp.'/Library/Core');

require_once abs_configs.'/Admin.php';
require_once abs_configs.'/Globals.php';

   // This theme uses wp_nav_menu() in one location.
    register_nav_menu('primary', __('Primary Menu', 've'));
    
 
 
//Rhino_Load_Scripts(scandir(abs_configs),abs_configs);
Rhino_Load_Scripts(scandir(abs_core),abs_core);

if(is_dir(abs_scripts))
Rhino_Load_Scripts(scandir(abs_scripts),abs_scripts);

//Lets sort out the routing

new Rhino_Router();

Rhino::Packages(array('Form','Wordpress_Interfaces','VE-Admin'));

require_once abs_configs.'/Wordpress_Setup.php';



//add_action( 'send_headers', 'site_router');


add_filter('show_admin_bar', '__return_false');             
add_action('template_include','Rhino_Dispatch_Template');
remove_filter('template_redirect', 'redirect_canonical');
// add actions and filters

add_action('init', 'custom_post_init' );

add_action('after_setup_theme', 'uwsu_setup_theme' );
add_filter('manage_pages_columns', 'add_template_columns');
add_action('manage_pages_custom_column', 'manage_custom_template_column', 10, 2);
add_filter('manage_posts_columns', 'add_parent_page');
add_action('manage_posts_custom_column', 'manage_parent_page_columns', 10, 2);  
add_action('wp_dashboard_setup', 'my_db_metaboxes', 20, 0 );

// admin actions
add_action('admin_init', 'my_admin_init' );
add_action('admin_head', 'uwsu_init_vars' );
add_action('admin_head', 'add_admin_js_dir' );
add_action('admin_enqueue_scripts','add_global_style');
add_action('admin_enqueue_scripts','wp_admin_bootstrap');
add_action('save_post', 'Pages_Model::structureAltered');
add_action('save_post', 'Menu_Model::structureAltered');

add_action('wp', 'action_get_postmeta');
// add shortcodes

add_action( 'admin_notices', 'hide_update_notice', 1 );

add_shortcode( 'em','shortcode_em' );
add_shortcode( 'b', 'shortcode_strong' );
add_shortcode( 'p', 'ptag' );

add_action( 'after_wp_tiny_mce', 'keep_paragraphs' );
add_filter('admin_footer_text', 'remove_footer_admin'); //change admin footer text



    register_taxonomy('demography', array(
        0 => 'post',
            ), array('hierarchical' => false, 'label' => 'demography', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'demogrpahic'));

 
//experience page post type
    function experience_post_type() {
        register_post_type('experience', array(
            'label' => __('Experiences'),
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'public'=>false,
            'taxonomies' => array('demography')
        ));
    }
    add_theme_support('post-thumbnails');
    add_action('init', 'experience_post_type');
    
    //image gallery post type
     function gallery_post_type() {
        register_post_type('gallery', array(
            'label' => __('gallery'),
            'supports' => array('title','thumbnail'),
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'public'=>false,
            
        ));
    }
    add_theme_support('post-thumbnails');
    add_action('init', 'gallery_post_type');
 

    
