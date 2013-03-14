<?php

/* 
 * Set up standard wordpress items
 * 
 * Map a request to a controller 
 * 
 */
function custom_post_init() {


    RR_new_post_type('news','News',array('category'));
    RR_new_post_type('events','Events',array('category'),true);
    RR_new_post_type('publications','Publications',array('society-groups','category'),true);
    RR_new_post_type('schools','Schools',array('category'),true);
    
}




function remove_footer_admin () {
echo 'Thank you for creating with <a href="http://readingroom.com" target="_blank">Reading Room</a>';
}
function add_global_style(){
    wp_register_style( 'extend_global', url_temp . '/_inc/_assets/css/global.css', false, '1.0.0' );
    wp_enqueue_style( 'extend_global' );
}


function my_admin_init()
{  
    // Remove extra scary buttons
    add_filter("mce_buttons", "extended_editor_mce_buttons", 0);

    function extended_editor_mce_buttons($buttons) {
    
	return array(
	    "formatselect","separator",
	    "bold", "italic", "underline", "strikethrough", "separator",
	    "bullist", "separator",
	    "charmap", "separator",
	    "link", "unlink", "anchor", "separator",
	    "undo", "redo", "separator",
	    "fullscreen"
	);
    }
/*
    function extended_editor_mce_buttons_2($buttons) {
	// the second toolbar line
	return array();
    }

    // Remove un-neccesary metaboxes for non admin users
    if ( !current_user_can( 'manage_options' ) || true ) {
	$unused_meta_boxes = array(
	    'trackbacksdiv',  'authordiv',  'postcustom',
	    'revisionsdiv'//'commentstatusdiv', 'commentsdiv',
	);
	foreach( $unused_meta_boxes as $box ){
	    //remove_meta_box( $box,'post','normal' );
		//remove_meta_box( $box,'page','normal' );
	    //remove_meta_box( $box,'news','normal' );
		//remove_meta_box( $box,'events','normal' );
	}
    }*/
    
	
    global $menu,$submenu;
   /* unset($menu[2]);
    unset($menu[4]);
    unset($menu[5]);
    unset($menu[25]);
    unset($menu[75]);
    unset($menu[80]);
    unset($menu[65]);

    unset($submenu['themes.php'][5]);
    unset($submenu['themes.php'][7]);
    unset($submenu['themes.php'][11]);
    
*/
    
    $register_pages = array("post.php","post-new.php");
    $current_script = end(explode("/",$_SERVER["PHP_SELF"]));
    
    if(in_array($current_script,$register_pages))
    {        
        wp_deregister_script( "jquery" );
        wp_register_script( "jquery",'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', false, '1.0.0' );
        wp_enqueue_script( "jquery" );
    }  
    wp_register_script( "admin_scripts",url_js.'/admin.js?v=3', false, '1.0.0',true );
    wp_enqueue_script( "admin_scripts" );
     
} // admin_init


function wp_admin_bootstrap(){
    wp_register_style('css_bootstrap',url_assets.'/css/wp.admin.bootstrap.css?v=2.1.1');
    wp_enqueue_style('css_bootstrap');
    wp_register_script('js_bootstrap',url_assets.'/assets/js/bootstrap.min.js', false, '1.0.0',true );
    wp_enqueue_script('js_bootstrap');
}

function uwsu_init_vars(){
    
    $event_id = 4;//get_term_by('slug', 'event', 'category')->term_id;
    
    $addevent = $event_id?', eventID = '.$event_id:',eventID = 0';
     
    
    $register_pages = array("post.php","post-new.php");
    $current_script = end(explode("/",$_SERVER["PHP_SELF"]));
    
    if(in_array($current_script,$register_pages))
    {         
        $json = RR_page_objects();

        $jsonencode = count($json)>0?json_encode($json):'[{}]';
        printf('<script type="text/javascript">  var link_objects =%s%s;</script>',$jsonencode,$addevent);
    }
}




/**
 * Add admin global variables
 */
/**
 * Enqueue a css file for admin, must be run on admin_init
 * @param string $css_file
 * @return bool 
 */

function add_admin_js_dir(){
?>

    <script type="text/javascript" charset="utf-8" src="<?php echo url_jquery ?>"></script>
    <script type="text/javascript" charset="utf-8"> var directory = "<?php bloginfo('template_directory'); ?>"</script>
    <script type="text/javascript" charset="utf-8" src="<?php echo url_inc; ?>/_assets/js/alert.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo url_inc; ?>/_assets/js/slug.js"></script>
    <script type="text/javascript" charset="utf-8"> $(".alert").alert()</script>
<?php
}

function add_admin_style( $css_file )
{
    $url = get_bloginfo('template_url') . $css_file;
    $file = TEMPLATEPATH . $css_file;
    if ( file_exists($file) ) {
        wp_register_style('rr_admin_styles', $url,'1.0');
        wp_enqueue_style( 'rr_admin_styles');
	return true;
    }else{
	return false;
    }
}

/**
 * Enqueue a script for admin, must be run on admin_init
 * @param string $js_file
 * @param string $handle
 * @return bool 
 */
function add_admin_script( $js_file, $handle = 'rr_admin_scripts' )
{
    
    $url = get_bloginfo('template_url') . $js_file;
    $file = TEMPLATEPATH . $js_file;
    if ( file_exists($file) ) {
        wp_register_script($handle, $url,'1.0');
        wp_enqueue_script( $handle);
	return true;
    }else{
	return false;
    }
}


function my_db_metaboxes()
{
    global $wp_meta_boxes;
 
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_plugins']);

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_incoming_links']);
 
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_comments']);

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_right_now']);
	
}


/*
 * Keep paragraphs, add to admin action
 * This is a wordpress thing, Paragraphs get removed in the editor, causing blocky text
 * not cool.
 */
function keep_paragraphs() {
?>

<script type="text/javascript">
if ( typeof(jQuery) != 'undefined' ){
  jQuery('body').bind('afterPreWpautop', function(e, o){
    o.data = o.unfiltered
    .replace(/caption\]\[caption/g, 'caption] [caption')
    .replace(/<object[\s\S]+?<\/object>/g, function(a) {
      return a.replace(/[\r\n]+/g, ' ');
    });
  }).bind('afterWpautop', function(e, o){
    o.data = o.unfiltered;
  });
}
</script>
<?php

}