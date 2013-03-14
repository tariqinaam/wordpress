<?php 

class Metaboxes_Interface{
    
    var $capability="administrator";
    var $position=8;
    var $remove_meta=array();
    var $post_type="post";
    var $post_context="normal";
    var $post_priority="default";
    var $cmd_path;
    var $cmd_action; 
    var $cmd_features; 
    var $cmd_from;
    var $cmd_contexts;
    var $cmd_title;
    var $meta_params=array();
    var $cmd_action_ref=array();
    var $cmd_context_ref=array();
    var $cmd_type_keys=array();
    
    /*
     * Make a slug
     * @param string(string),separator(string)
     */
    public function make_slug($string,$sep="-"){
 
        return preg_replace("/ /","$sep",trim(strtolower($string)));
    }
    
    
    public function __construct() {

        global $post;
        
        $this->post = $post;
        $this->cmd_type_keys   =array('i'=>'id','p'=>'post_type','t'=>'template','l'=>'title','o'=>'output','s'=>'slug','c'=>'context','f'=>'feature');
        $this->cmd_context_ref =array('s'=>'side','n'=>'normal','a'=>'advanced');
        $this->cmd_action_ref  =array('a'=>'add','r'=>'remove');
        $this->inbuiltmetaboxes=array('title','editor','author','thumbnail',
            'excerpt','trackbacks','custom-fields','comments','revisions','post-formats','slugdiv',
            'commentstatusdiv','commentsdiv','revisionsdiv','authordiv','postcustom','postexcerpt',
    'trackbacksdiv','page-attributes','postimagediv','formatdiv','tagsdiv-post_tag','categorydiv','pageparentdiv');
        
        $this->cmd_path = abs_packages."/Wordpress_Interfaces/Meta/metaboxes.txt";
     
        $this->cmd_read();
        add_action('admin_init', array(&$this,'add_builtin_meta_boxes' ));
        add_action('admin_enqueue_scripts', array(&$this,"global_style"));
        add_action('add_meta_boxes',array(&$this,'metaReset'));
        add_action('add_meta_boxes',array(&$this,'metaAdd'));
        add_action("add_meta_boxes",array(&$this,"metaRemove"));
        add_action('save_post',array(&$this,'savePostmeta'));

       
    }
    
    function metaReset(){
        
        foreach($this->inbuiltmetaboxes as $post_type){
            remove_meta_box($post_type, 'page', 'normal' );
            remove_meta_box($post_type, 'post', 'normal' );
        }
    }
    
    function add_builtin_meta_boxes()
    {
        global $_wp_post_type_features;
       
        $_wp_post_type_features=array();
        
        
       
        foreach($this->meta_params['add'] as $params)
        {

           
            $a_feature  = isset($params['feature'])?$params['feature']:'';//has to loop
            $v_inc_type = isset($params['inc_type'])?$params['inc_type']:'';
            
  
            if($v_inc_type=='inc'){
                
                foreach($a_feature as $v_feature){
                    
                    if(in_array($v_feature,$this->inbuiltmetaboxes))
                        $_wp_post_type_features[$v_post_type][$v_feature]=true;
                }
            }elseif($params['inc_type']=='all'){
                
                $pts = array_diff(get_post_types('','names'),array('revision','nav_menu_item','attachment'));
                
                foreach ($pts as $v_post_type) {
                    foreach($a_feature as $v_feature){
                        if(in_array($v_feature,$this->inbuiltmetaboxes))
                            $_wp_post_type_features[$v_post_type][$v_feature]=true;
                    };
                }
            }elseif($params['inc_type']=='exc')
            {
                //add to everything but dont add to the post types
                $pts = array_diff(get_post_types('','names'),array('revision','nav_menu_item','attachment'));

                foreach ($pts as $v_post_type) {

                    foreach($a_feature as $v_feature){
                        if(in_array($v_feature,$this->inbuiltmetaboxes))
                            $_wp_post_type_features[$v_post_type][$v_feature]=true;
                    }
                }
            }
            $v_feature= '';
            
        }

    }
    public function global_style(){
     
        wp_register_style( 'extend_metaboxes', url_packages.'/Wordpress_Interfaces/Meta/style.css', false, '1.0.0' );
        wp_enqueue_style( 'extend_metaboxes' );
        
    }
   

    private function cmd_get_lines(){
        if(!file_exists($this->cmd_path))
        die("Create commandline file");
        
        $file = fopen($this->cmd_path, "r");

        $i=$j=0;
        
        //get entire cmd contents
        $cmd_str = file_get_contents($this->cmd_path);
        $cmd_str = preg_replace('/[[:space:]]/',' ',$cmd_str);
        
        $cmd_str = trim(preg_replace("/[ ]{2,}/"," ",$cmd_str));
        
        $cmd_str = str_replace(' +','+',$cmd_str);
        $cmd_str = str_replace(' + ','+',$cmd_str);
        $cmd_str = str_replace('+ ','+',$cmd_str);
        $cmd_str = str_replace(' ,',',',$cmd_str);
        $cmd_str = str_replace(' , ',',',$cmd_str);
        $cmd_str = str_replace(', ',',',$cmd_str);
        //echo  $cmd_str ;
        
        return explode(';',$cmd_str);
    }
    
    private function cmd_format($line){
        $line = trim(preg_replace("/[ ]{2,}/"," ",$line));
        $line = trim(preg_replace('/#.*/','', $line));
        
        return $line;
    }
    
    private function cmd_set_args($line){
        $cmd_args = explode(' ',$line);
        @list($this->cmd_action,$this->cmd_features,$this->cmd_from, $this->cmd_contexts) = $cmd_args;
        $arg_size = count($cmd_args);
        $this->cmd_title = implode(' ',array_slice($cmd_args,4, $arg_size));
        $this->cmd_title = substr($this->cmd_title, 2, strlen($this->cmd_title));
        
        $this->cmd_action = trim($this->cmd_action);
        $this->cmd_features = trim($this->cmd_features);
        $this->cmd_contexts = trim($this->cmd_contexts);
        $this->cmd_from = trim($this->cmd_from);
        $this->cmd_title = trim($this->cmd_title);
        return $this;
    }
    
    private function cmd_asign_keys(&$meta,$obj){ 
        
        foreach($obj as $part):
            $parts = explode(':', $part);
            $key = $parts[0];
            $and = $parts[1]; 
            $ands  = explode('+',$and);
            $meta[$this->cmd_type_keys[$key]]=$ands;
        endforeach;
    }
    
    static function set_context($n){
        
        $cmd_context_ref =array('s'=>'side','n'=>'normal','a'=>'advanced');
        if($n)
        return $cmd_context_ref[$n];
    }
    
    private function cmd_asign_single_key(&$meta,$str,$nokey=false){
        if($nokey==false){
            $parts = explode(':', $str);
            $key = $parts[0];
            $and = @$parts[1];
            $ands  = explode('+',$and);
            $ands = array_map("Metaboxes_Interface::set_context", $ands);
            $meta['context']=$ands;
        }else {
            $ands  = explode('+',$str);
            $meta['feature']=$ands;
        }
        
       
    }                   
    private function cmd_read(){
        
        $i=$j=0;
        
        $cmd_lines = $this->cmd_get_lines();    //get command lines
        
        foreach($cmd_lines as $line) {

           $line = $this->cmd_format($line);    //format lines
           
           if($line):
               
               $this->cmd_set_args($line);      //set arguments
               
               
               if(preg_match('/^[*]$/',$this->cmd_from)):
                    $this->cmd_from = str_replace('*','',$this->cmd_from);
                    $this->cmd_from=array();
                    $inclusion_type='all';
               elseif(preg_match('/^[*]\^/',$this->cmd_from)):
                    $inclusion_type="exc";
                    $this->cmd_from = str_replace('*^','',$this->cmd_from);
                    $this->cmd_from = @explode(',',$this->cmd_from);
               else:
                    $inclusion_type="inc";
                    $this->cmd_from = @explode(',',$this->cmd_from);
               endif;

               
               switch($this->cmd_action){
                   case 'a':
                       $this->cmd_asign_single_key($this->meta_params['add'][$j],$this->cmd_features,true);
                       if($this->cmd_from)
                       $this->cmd_asign_keys($this->meta_params['add'][$j]['from'],$this->cmd_from);
                       $this->cmd_asign_single_key($this->meta_params['add'][$j],$this->cmd_contexts);
                       $this->meta_params['add'][$j]['inc_type']=$inclusion_type;
                       $this->meta_params['add'][$j]['title']=$this->cmd_title;
                       break;
                   case 'r':
                       $this->cmd_asign_single_key($this->meta_params['remove'][$i],$this->cmd_features,true);
                       if($this->cmd_from)
                       $this->cmd_asign_keys($this->meta_params['remove'][$i]['from'],$this->cmd_from);
                       $this->cmd_asign_single_key($this->meta_params['remove'][$i],$this->cmd_contexts);
                       $this->meta_params['remove'][$i]['inc_type']=$inclusion_type;
                       break;
               }
               $i++;$j++;
           endif;

        }
     
    }
    
    public function metaRemove(){

        global $post,$_wp_post_type_features;

        $temp = get_post_meta($post->ID,'_wp_page_template',true);
        
        $v_id       = $post->ID;
        $v_slug     = $post->post_name;//?$post->post_name:'no-slug';
        $v_post_type= $post->post_type;
        $v_template = $temp?$temp:'default';
        
        if(!isset($this->meta_params['remove']))
                return false;
        
        foreach($this->meta_params['remove'] as $params)
        {
            
            //loop through 
            $a_feature  = isset($params['feature'])?$params['feature']:'default';
            $a_id       = isset($params['from']['id'])?$params['from']['id']:array($v_id);
            $a_slug     = isset($params['from']['slug'])?$params['from']['slug']:array($v_slug);
            $a_template = isset($params['from']['template'])?$params['from']['template']:array($v_template);
            $a_post_type= isset($params['from']['post_type'])?$params['from']['post_type']:array($v_post_type);
            $v_context  = isset($params['context'][0])?$params['context'][0]:'normal';
    
            $in_post_types=in_array($v_post_type,$a_post_type);
            $in_slugs=in_array($v_slug,$a_slug);
            $in_templates=in_array($v_template,$a_template);
            $in_ids=in_array($v_id,$a_id);
           
            
            $v_inc_type = isset($v_inc_type)?$v_inc_type:'';
            $v_feature = isset($v_feature)?$v_feature:'';

         
            if($in_ids&&$in_templates&&$in_slugs&&$in_post_types&&$v_inc_type=='inc'){
                
                foreach($a_feature as $v_feature)
                {
                    if(in_array($v_feature,$this->inbuiltmetaboxes)){
                        unset($_wp_post_type_features[$v_post_type][$v_feature]);
                        foreach (array('normal', 'advanced', 'side') as $v_context)
                            remove_meta_box($v_feature, $v_post_type, $v_context );
                    }
                }
            }elseif($in_ids&&$in_templates&&$in_slugs&&$in_post_types&&$v_inc_type=='all'){
                
                $pts = array_diff(get_post_types('','names'),array('revision','nav_menu_item','attachment'));

                foreach ($pts as $v_post_type) {
                    foreach($a_feature as $v_feature){
                        if(in_array($v_feature,$this->inbuiltmetaboxes)){
                        unset($_wp_post_type_features[$v_post_type][$v_feature]);
                        foreach (array('normal', 'advanced', 'side') as $v_context)
                            remove_meta_box($v_feature, $v_post_type, $v_context );
                        }
                    };
                }
            }elseif($params['inc_type']=='exc')
            {
                //add to everything but dont add to the post types
                $pts = array_diff(get_post_types('','names'),array('revision','nav_menu_item','attachment'));
               
              
                if(!$in_ids&&!$in_templates&&!$in_slugs&&!$in_post_types){
                    
                    foreach ($pts as $v_post_type) {
                        
                        foreach($a_feature as $v_feature):
                            if(in_array($v_feature,$this->inbuiltmetaboxes)){

                            unset($_wp_post_type_features[$v_post_type][$v_feature]);
                            foreach (array('normal', 'advanced', 'side') as $v_context)
                                remove_meta_box($v_feature, $v_post_type, $v_context );
                            //check to see if on allowed list

                            }
                        endforeach;
                    }
                }
            }
            $v_context = $v_title = $v_feature= '';
            $v_post_type=$post->post_type;
            
        }

    }
   
    
   
    public function metaAdd(){

        global $post,$_wp_post_type_features;;
        
        if(in_array($post->post_name,array('sitemap')))
        return false;
        $temp = get_post_meta($post->ID,'_wp_page_template',true);
        
        $v_id       = $post->ID;
        $v_slug     = $post->post_name;//?$post->post_name:'no-slug';
        $v_post_type= $post->post_type;
        $v_template = $temp?$temp:'default';

       
        foreach($this->meta_params['add'] as $params)
        {
            //loop through 
            
      
            $a_feature  = isset($params['feature'])?$params['feature']:'default';
            $a_id       = isset($params['from']['id'])?$params['from']['id']:array($v_id);
            $a_slug     = isset($params['from']['slug'])?$params['from']['slug']:array($v_slug);
            $a_template = isset($params['from']['template'])?$params['from']['template']:array($v_template);
            $a_post_type= isset($params['from']['post_type'])?$params['from']['post_type']:array($v_post_type);
            $v_context  = isset($params['context'][0])?$params['context'][0]:'normal';
            $v_inc_type = isset($params['inc_type'])?$params['inc_type']:'';
            
            $in_post_types=in_array($v_post_type,$a_post_type);
            $in_slugs=in_array($v_slug,$a_slug);
            $in_templates=in_array($v_template,$a_template);
            $in_ids=in_array($v_id,$a_id);
           
            $v_feature = isset($v_feature)?$v_feature:'';
            $in_builtin_metabox = in_array($v_feature,$this->inbuiltmetaboxes);
          
            //echo "$in_ids&&$in_templates&&$in_slugs&&$in_post_types&&$v_inc_type=='inc'";exit;
           if($in_ids&&$in_templates&&$in_slugs&&$in_post_types&&$v_inc_type=='inc')
            {
              
                foreach($a_feature as $v_feature)
                {
                    if(in_array($v_feature,$this->inbuiltmetaboxes)){
                    
                    $_wp_post_type_features[$v_post_type][$v_feature]=true;
                    //check to see if on allowed list
                   
                    }else{ 
                        $v_title = ucwords(str_replace('-',' ',$v_feature));
                          
                            add_meta_box(
                              $v_feature,
                              $v_title,
                              array(&$this,'getMetaBlock'),
                              $v_post_type,
                              $v_context,
                              'low',
                              array('slug'=>$this->make_slug($v_feature,"-"))
                            );
                    }
                }
            }elseif($in_ids&&$in_templates&&$in_slugs&&$in_post_types&&$v_inc_type=='all')
            {
                $pts = array_diff(get_post_types('','names'),array('revision','nav_menu_item','attachment'));
                
                
                foreach ($pts as $v_post_type) {
                    
                        foreach($a_feature as $v_feature):
                            if(in_array($v_feature,$this->inbuiltmetaboxes)){
                    
                            $_wp_post_type_features[$v_post_type][$v_feature]=true;
                        //check to see if on allowed list
                    
                            }else{ 
                               $v_title = ucwords(str_replace('-',' ',$v_feature));
                      
                               add_meta_box(
                              $v_feature,
                              $v_title,
                              array(&$this,'getMetaBlock'),
                              $v_post_type,
                              $v_context,
                              'low',
                              array('slug'=>$this->make_slug($v_feature,"-"))
                            );
                           }
                        endforeach;
                    
                }
            }elseif($params['inc_type']=='exc')
            {
               
                //add to everything but dont add to the post types
                $pts = array_diff(get_post_types('','names'),array('revision','nav_menu_item','attachment'));
               
                //echo "$in_ids $v_id &&$in_templates $v_template &&$in_slugs $v_slug &&$in_post_types
                //$v_post_type<br/>";
                if($in_ids&&$in_templates&&$in_slugs&&$in_post_types)
                {}    
                else 
                {
                    foreach ($pts as $v_post_type) {
                        
                        foreach($a_feature as $v_feature):
                            if(in_array($v_feature,$this->inbuiltmetaboxes)){

                            $_wp_post_type_features[$v_post_type][$v_feature]=true;
                            //check to see if on allowed list

                            }else{
                                   $v_title = ucwords(str_replace('-',' ',$v_feature));
                               
                              add_meta_box(
                              $v_feature,
                              $v_title,
                              array(&$this,'getMetaBlock'),
                              $v_post_type,
                              $v_context,
                              'low',
                              array('slug'=>$this->make_slug($v_feature,"-"))
                            );
                               }
                        endforeach;
                    }
                }
            }
            $v_context = $v_title = $v_feature= '';
            $v_post_type=$post->post_type;
            
        }

    }
    
    
    /*
     * Save Postmeta
     * Lets save the postmeta
     */
    public function savePostmeta( $post_id )
    {
        //we are not saving the meta
        if(!isset($_POST["save_meta_data"]))
            return false;
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;
        
        if ( isset( $_POST['post_type'] ) ) {
            if ( 'page' == $_POST['post_type'] ) {
                if ( !current_user_can( 'edit_page', $post_id ) )
                    return $post_id;
            } else {
                if ( !current_user_can( 'edit_post', $post_id ) )
                    return $post_id;
            }
        }
        
     
       // Loop through the meta
       foreach($_POST["save_meta_data"] as $key){
           
            //get the meta value
            if(isset($_POST[$key])){
                
                $meta_value = $_POST[$key];
             
                if(is_array($meta_value))
                $meta_value = serialize( $meta_value );
                
                $saved_meta = get_post_meta($post_id,$key,true);
             
                if($saved_meta){   
                    //1. key exists in meta field and post
                    //meta value is not empty
                    if($meta_value)
                        update_post_meta( $post_id, $key, $meta_value );
                    else delete_post_meta( $post_id, $key);
                }
                else{   
                    //2. key doesnt exist in meta field and post
                    if($meta_value)
                        add_post_meta( $post_id, $key, $meta_value,true);
                    else delete_post_meta( $post_id, $key);
                    
                }
            }
        }            

    }
  
    /*
     * Get Meta Block 
     * Lets get the meta block to display inside wordpress admin 
     */
    public function getMetaBlock($post,$args){
  
        echo '<div class="tbswp">';
        $slug = $args['args']['slug'];
        if(file_exists(abs_wpinterfaces."/Meta/Blocks/$slug.php"))
        include abs_wpinterfaces."/Meta/Blocks/$slug.php";
        else echo abs_wpinterfaces."/Meta/Blocks/$slug.php does not exist";
        echo '</div>';
    }
        
}


?>