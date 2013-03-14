<?php

function pre_dump($a){ echo '<pre>'.print_r($a,true).'</pre>'; }


function __autoload($classname) {
    
    
    $parts  = explode('_',$classname);
    $folder = end($parts).'s';
   
    $path = abs_temp.'/'.$folder;
    
    
    if(is_dir($path)){
        
    
        unset($parts[count($parts)-1]);

   
        $classname = $path.'/'.implode('/',$parts);

        $filename = $classname.".php";

        if(file_exists($filename))
        include_once($filename);
    
    }
}


function autoLoadDirectory($library_folder,$not_allowed=array()){
    
    $not_allowed = array_merge(array('.','..','readme.txt'),$not_allowed);

    if(!is_dir($library_folder))
        return false;
    
    //echo $library_folder;
    $files = scandir($library_folder);

    foreach($files as $file){
        if(!in_array($file, $not_allowed)){

            if(!is_dir($library_folder.'/'.$file)){
                include_once($library_folder.'/'.$file);
                $GLOBALS['library'][] = $file;
            }
        }
    }
}




function smrtrLog($msg){
    $log = new KLogger(PRIVATE_PATH, KLogger::INFO);
    $log->logInfo($msg);
}

function encryptKvP($arr){
    
    $ret = array();
    foreach($arr as $key => $val){
        
        $ret[$key] = "$key-$val";
    }
    
    $string = implode('_',$ret);
    $encryption = new encryption(true);
    
    return $encryption->encrypt(ENCRPTION_KEY, $string);
    
}


function decryptKvP($str){
    
    $decryption = new encryption(true);
    $str = $decryption->decrypt(ENCRPTION_KEY, $str);
    
    $ret = array();
    $arr = explode('_',$str);
    
    foreach($arr as $item){
        
        $parts = explode('-',$item);
        $ret[$parts[0]] = $parts[1];
    }
    
    return $ret;
}



function smrtrSlug($sting,$separator='-'){

    if(!in_array($separator,array('-','_')))
            return false;

    $replace = $separator==='-'?'_':'-';

    $string = str_replace($replace,$separator,$string);

    return preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string)); 

}

/*
 * Always ways useful to override these server side settings.
 */
function allocate_memtime($m,$t)
{
    @ini_set("memory_limit",$m."M");//increase limit
    @ini_set('max_execution_time', $t);
}






/**
 * Encrypt Array of Strings into one line
 * 
 * @param $strArr
 * @param $del optional
 * @return string
 */
function encrypto($strArr,$del='/')
{
    $strPiece = implode($del,$strArr);
    $encryption = new encryption();
    return $encryption->encrypt(ENCRPTION_KEY, $strPiece);
}
/**
 * Decrypt Array of Strings into one line
 * 
 * @param $strArr string
 * @param $del optional
 * @return array
 */
function decrypto($strPiece,$del='/')
{
    $dencryption = new encryption();
    $strArr = $dencryption->decrypt(ENCRPTION_KEY, $strPiece);
    return explode($del,$strArr);
}




/*
 * The Rhino Controller
 * 
 * Lets give the layout, view, block or whatever it may be some useful
 * implied functionality off the shelf.
 * 
 */

class Rhino_Controller{

    var $params = array(), $action,$layout,$block,$path;
    
    public function __construct($path, array $vars = array()){
   
        foreach($vars as $key => $var){
            
            $this->{$key} = $var;
        }
        
        if(file_exists($path))
        $this->path = $path;
        else {
            
            $path = abs_temp."/Layouts/$path.php";
            $this->path = $path;
        }
        
   
        if(method_exists($this,'rhino'))
            $this->rhino();
        
        
        
        if(isset($this->action)&&method_exists($this,$this->action.'_action')){
            $action = $this->action.'_action';
            $this->$action();
        }
        
        if(!isset($this->post_title))
            $this->post_title = '';
        
        //lets autoload any associated meta for wordpress
        $this->postmeta =  $this->getPostmeta();//limit 99

        
    }    
    
    
    public function __destruct(){

        //ob_start();
        if((isset($this->params[0])&&$this->params[0]!=='ajax')||!isset($this->params[0]))
            include($this->path);

        
        $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
        $replace = array(
            '>',
            '<',
            '\\1'
            );
        //echo preg_replace($search, $replace, ob_get_clean()).'<!--Rhino-->';
    
        
    }


    function getBlock($file,$vars = array(),$folder='Blocks'){

        foreach($vars as $key => $var){
            $this->{$key} = $var;
        }
        
        $path   = abs_temp."/$folder/$file.php";
        include($path);
       
    }
    
    
    function setLayout($layout){
        
        $this->path = abs_temp.'/Layouts/'.$layout.'.php';
    }
    
    /**
     * Breadcrumb
     * 
     * Returns the breadcrum trail of the post.
     * 
     * @TOOD should remove markup DEFFOO
     */
    
    function getBreadcrumb()
    {

        $breadcrumb = array();
        if(!is_search()&&!is_home())
        {
            $breadcrumb[$this->ID] = $this->post_title;
            if(count($this->ancestors)>0)
            {
                foreach($this->ancestors as $post_id)
                {
                    $this_post = get_post($post_id);
                    $breadcrumb[$this_post->ID] = $this_post->post_title;
                }
            }

        }

        elseif(is_search())
        {
            $breadcrumb[] = "Search Results";
        }

        elseif(is_404())
        {
            $breadcrumb[] = "404 Page not found";
        }

        $breadcrumb = array_reverse($breadcrumb,true);
        $return ="<ul><li>You are here: </li>";
        $return.="<li><a href='". get_bloginfo('url') . "'> Home </a></li>";

        $last_id = end($breadcrumb);

        foreach($breadcrumb as $link_id => $text)
        {
            if($last_id==$text)
            $return.="<li class='current'> $text </li>";   
            else
            $return.="<li><a href='". get_permalink($link_id) . "'> $text</a></li>";


        }
        return $return.="</ul>";

    }
    
    
    /*
     * Get all(99) the wordpress meta
     * @TODO we need a better way to do the wordpress limit
     */
    public function getPostmeta($amount=99){
    
        if(!isset($this->ID))
            return array();
        global $wpdb;

        $ret = array();

        $metas = $wpdb->get_results(sprintf("
            select meta_key,meta_value 
            from {$wpdb->prefix}postmeta 
            where meta_value!='' 
            and post_id=%s and meta_key regexp '^[a-zA-Z0-9]' limit %s",$this->ID,$amount));

        foreach($metas as $key => $meta){
            $ret[$meta->meta_key] = $meta->meta_value;
        }
    
        return $ret;

    }
    
}








class Rhino{
    
    public static function Packages($files = array()){
    
        if(count($files)<1)
            return false;

        foreach($files as $file){

            $path = abs_packages.'/'.$file.'/bootstrap.php';

            if(file_exists($path)){
                include_once($path);
                $GLOBALS['packages'][] = $file;
            }

        }

    }
    
    static public function getPostTypes($remove=array())
    {
        $default = array('attachment','revision','nav_menu_item');
        $remove = count($remove)==0?$default:array_merge($remove,$default);


        return array_diff(get_post_types(),$remove);
    }
    
    static public function slugify($str){
        
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        
        return $str;
    }
    
    static public function getLink($id,$internal=true){
    
        if($internal){
            return get_permalink($id);
        }else{
            return get_bookmark($id)->link_url;
        }
    }
    
    static public function RR_get_categories($tax, $arg=array()){
        $args = array(
                'type'                     => 'post',
                'child_of'                 => 0,
                'parent'                   => '',
                'orderby'                  => 'name',
                'order'                    => 'ASC',
                'hide_empty'               => 1,
                'hierarchical'             => 1,
                'exclude'                  => '',
                'include'                  => '',
                'number'                   => '',
                'taxonomy'                 => $tax,
                'pad_counts'               => false );

        $cats = get_categories( $args );

        $categories = array();
        foreach($cats as $cat){

            $categories[$cat->slug] = $cat->name;
        }

        return $categories;
    }

    
    
/*
 * Useful image resource
 * @param int $id
 * @param string $size
 * @return String
 */

function Image($id,$size,$class=false)
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



function RR_get_post_types($remove=array())
{
    $default = array('attachment','revision','nav_menu_item');
    $remove = count($remove)==0?$default:array_merge($remove,$default);
    
    
    return array_diff(get_post_types(),$remove);
}

function RR_make_slug($str){
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
    return $str;
}

function RR_make_extract($string,$splice_from=60){
    
    $string = strip_tags($string);
    $words  = explode(' ',$string);
    //pre_dump($words);
    $number_of_words = count($words);
    
    
    if($number_of_words>100):
        
        $continued = $splice_from<$number_of_words?'...':'';
        $words  = array_splice($words,0,$splice_from);
        
        $string = implode(' ',$words);
    else:
        $continued = '';
    endif;

    return $string.$continued;

}


function RR_timestamp_from_string($datetime_str)
{
    list( $y, $m, $d, $h, $i, $s ) = preg_split( '([^0-9])', $datetime_str);
    
    return mktime($h, $i, $s, $m, $d, $y);
}

function dt2ts($datetime_str)
{
    list( $y, $m, $d, $h, $i, $s ) = preg_split( '([^0-9])', $datetime_str);
    
    return mktime($h, $i, $s, $m, $d, $y);
}





function RR_page_objects(){
    
        global $wpdb;
        $post_types = RR_get_post_types($remove=array());
        
        $post_types[]='links';
    
        foreach($post_types as $type){
            
            if($type!='links')
                $temp[] = "'$type'";
        }
        
        $posts = '('.implode(',',$temp).')';
        $p = $wpdb->get_results('select ID,post_title,post_type from wp_posts where post_status="publish" and post_type in '.$posts);
        
        if(in_array('links',$post_types))
        $l = $wpdb->get_results('select link_id,link_name,link_url,link_target,link_description from wp_links');

        
        $links = array();
        $host = get_bloginfo('url');
        
        if(count($p)>0){
            
            foreach($p as $pl){
                /*
                $links[] = array('id'=>$pl->ID,
                'name'=> $pl->post_title,
                'type'=>$pl->post_type,
                'target'=>'_none',
                'alt'=>$pl->post_name,
                'url'=> get_permalink($pl->ID));
                */
                
                $url = get_permalink($pl->ID);
                $tree = str_replace($host,'',$url);
                $links[$pl->post_title] = array($pl->ID,$pl->post_title,'internal',$pl->post_type,$pl->post_title,$url,$tree);
                
            }
            
            
        }
        
        
        if(count($l)>0){
            
            foreach($l as $ll){
                
                /*
                $links[] = array('id'=> $ll->link_id
                ,'name'=> $ll->link_name
                ,'type'=> 'link'
                ,'target'=> $ll->link_target?$ll->link_target:'_blank'
                ,'alt'=> $ll->link_description?$ll->link_description:$ll->link_name
                ,'url'=>  $ll->link_url);
                */
                
                $tree = str_replace($host,'',$ll->link_url);
                $links[$ll->link_name] = array($ll->link_id, $ll->link_name,'other','link'
                ,$ll->link_description?$ll->link_description:$ll->link_name,$ll->link_url,$tree);
                
            }
            
            
        }
  
        $links;
        return $links;
}



function RR_get_link($id,$internal=true){
    
    if($internal){
        return get_permalink($id);
    }else{
        return get_bookmark($id)->link_url;
    }
}





function RR_get_categories($tax, $arg=array()){
    $args = array(
            'type'                     => 'post',
            'child_of'                 => 0,
            'parent'                   => '',
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hide_empty'               => 1,
            'hierarchical'             => 1,
            'exclude'                  => '',
            'include'                  => '',
            'number'                   => '',
            'taxonomy'                 => $tax,
            'pad_counts'               => false );

    $cats = get_categories( $args );

    $categories = array();
    foreach($cats as $cat){

        $categories[$cat->slug] = $cat->name;
    }

    return $categories;
}



function RR_get_post_categories($id){

    $category = get_the_category($id);

    
    $ret = array();
    foreach($category as $cat){
        
        $ret[$cat->term_id] = $cat->name;
    }
    
    
    return $ret;
}


function RR_get_top_parent_meta($meta){
    
    global $post;
    
    if(!$meta)
        return false;
    
    if(count($post->ancestors))
    $post_parent = end($post->ancestors);
    else $post_parent = $post->ID;

    return get_post_meta($post_parent,$meta,true);
    
}



function RR_make_guid($col,$table='wp_posts')
{
    global $wpdb;
    
    //$alphabet = array_merge( range(0, 9)/*, array('A', 'B', 'C', 'D', 'E', 'F')*/ );
    $alphabet = range(0, 9);
    $already_exists = true;
    do {

        $guidchr = array();
        for ($i=0; $i<32; $i++)
        {
            $guidchr[] = $alphabet[array_rand( $alphabet )];
        }

        $guid = sprintf( "%s",
            implode("", array_slice($guidchr, 0, 12, true))
        );
 
        // check that GUID is unique
        $already_exists = (boolean) $wpdb->get_var("
            SELECT COUNT($col) as the_amount FROM $table WHERE $col = '$guid'
        ");

    } while (true == $already_exists);

    return $guid;
}



 function RR_get_ids_from_meta($meta_key,$meta_value){
            
    global $wpdb;

    $ret = array();
    $results = $wpdb->get_results(
            $wpdb->prepare('select post_id from wp_postmeta where meta_key="%s" and meta_value="%s"',$meta_key,$meta_value));

    foreach($results as $result){
        $ret[$result->post_id] = $result->post_id;
    }

    return $ret;
}
        
        
        
  function RR_get_multi_postmeta($ids=array(),$metas=array()){
            
    $ret = array();
    foreach($ids as $id){

        foreach($metas as $key => $unserialise)
        {

            if($value = get_post_meta($id,$key,true))
            {
                $value = $unserialise?unserialize($value):$value;
                $ret[$id][$key] = $value;
            }
        }
    }
    return $ret;
}
 



function RR_new_taxonomy($slug,$name,array $posts=array(),$hierarchy=true){

   
    $args = array(
            'hierarchical' => $hierarchy ,
            'labels' =>  array(
                            'name' => $name,
                            'singular_name' => $name,
                            'search_items' => "Search $name",
                            'popular_items' => "Popular $name",
                            'all_items' => "All $name",
                            'edit_item' => "Edit $name",
                            'update_item' => "Update $name",
                            'add_new_item' => "Add a new $name",
                            'new_item_name' => "New Items $name",
                            'separate_items_with_commas' => "Separate $name with commas",
                            'add_or_remove_items' => "Add or remove $name",
                            'choose_from_most_used' => "Choose from the most used $name",
                            'menu_name' => $name,
                        ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => false
    );
    
    register_taxonomy( $slug, $posts, $args);
}

function RR_new_post_type($slug,$name,array $taxonomies=array()){

    $labels = array(
        'name' => _x($name, 'post type general name'),
        'singular_name' => _x($name, 'post type singular name'),
        'add_new' => _x('Add new', $name),
        'add_new_item' => __("Add New $name"),
        'edit_item' => __("Edit $name"),
        'new_item' => __("New $name"),
        'view_item' => __("View $name"),
        'search_items' => __("Search $name"),
        'not_found' => __("No $name found"),
        'not_found_in_trash' => __("No $name found in Trash"),
        'parent_item_colon' => '',
        'menu_name' => $name
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        //'rewrite' => array( 'hierarchical' => true ),
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => null,
        'taxonomies' => $taxonomies
    );
    
    register_post_type($slug, $args);
}

?>