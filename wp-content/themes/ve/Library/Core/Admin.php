<?php
// =============================================================================
// Admin Pages
// =============================================================================





/**
 * Get names of pages/posts in an array, with the id as the key
 * @param array (optional) $query_args
 * @return array 
 */

function get_post_names( $query_args = array() )
{
    $args = array( 
        'post_type' => RR_get_post_types(),
        'posts_per_page'=> -1,
        'orderby'=>'title',
        'order'=>'ASC',
        'post_status'=>'publish'
    );
    
    if( is_array( $query_args ) ){
	$args = array_merge($args, $query_args);
    }
    
    $data = get_posts( $args );

    $ret = array();
    foreach( $data as $item ){
        
        //if($item->post_parent!=0)
        //$parent_post = get_post($item->post_parent);
        //$parent_page = $item->post_type=="page"&&$item->post_parent!=0?" under: ".$parent_post->post_title:"";
	$ret[$item->ID] = $item->post_title." (".str_replace("-post","",$item->post_type).")";
    }
    
    return $ret;
    
}




function wp_category_array($attr=null,$top_only=false)
{
    $categories = $attr==null?get_categories():get_categories($attr);

    $rendered_array=array();
    
    
    if($top_only==false)
    {
        foreach($categories as $category)
        {
            $rendered_array[$category->cat_ID]=$category->name;
        }
        
    }else
    {
        foreach($categories as $category)
        {
            if($category->parent==0)
            $rendered_array[$category->cat_ID]=$category->name;
        }
    }
    
    return $rendered_array;
}


function wp_list_posts($args)
{
     // Build a WP_Query
    /*$args = array(
	'post_type' => 'femtino-gallery',
	'tax_query' => array( ),
	'posts_per_page'=> -1
    );*/

    // Make new Query Object
    $query = new WP_Query( $args );
    
    $ret=array();
    
    foreach($query->posts as $post)
    {
        $ret[$post->ID] = $post->post_title;
    }
    
    return $ret;
}

function wp_list_terms($tax,$args=null)
{
    
    $ret=array();
    
    $terms = $args==null?get_terms($tax):get_terms($tax,$args);
    
    //pre_dump($terms);
    
    foreach ($terms as $term)
    {
        $ret[$term->term_id] = $term->name;
    }
    return $ret;
}

/*
 * List users of type arg
 * by id => display
 */
function wp_list_users()
{
    $ret=array();
    
    $users = $attr==null? get_users():get_users($attr);
    
    foreach ($users as $user)
    {
        $ret[$user->ID] = $user->display_name;
    }
    return $ret;
}

//list posts

//list users




function action_get_postmeta(){
    
    global $meta,$post;
    
    if($post)
    $meta = RR_get_all_postmeta($post->ID);
    else $meta = array();
}


function RR_get_all_postmeta($id){
    
    global $wpdb;

    $metas = $ret = array();
    $metas = $wpdb->get_results(sprintf('select meta_key,meta_value 
    from wp_postmeta where meta_value!="" and post_id=%s and meta_key regexp "^[a-zA-Z0-9]"',$id));

    foreach($metas as $key => $meta){
        
        $ret[$meta->meta_key] = $meta->meta_value;
    }
    
    return $ret;
    
}