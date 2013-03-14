<?php
/*
 * Wordpress menu, clean data
 */

class Menu_Model{

    var $page;
    var $pages;
    var $menu;
    
    public function __construct($menu='main',$limit=50){
        
        wp_reset_postdata();
        
        global $post;
    
        $this->menu->name = $menu;
        $this->page->ID         = isset($post->ID)?$post->ID:0;
        $this->page->title      = isset($post->post_title)?$post->post_title:'';
        $this->page->ancestor   = isset($post->ancestors)&&is_array($post->ancestors)&&count($post->ancestors)>0?end($post->ancestors):$this->page->ID ;
    
        if(!$this->hasCache()){
            
        
            $pages = new getPages();
         
            $this->pages->data      = $pages->pages->data;

            $this->menu->objectIDs  = $this->get_objectIDs($menu);

            $this->setMeta();
            
            $this->setCache();
            
        }
       
        
        
        return $this;
        
        
    }
  
    /**
     * Set page cache
     */
    function setCache(){
    
        $cache = get_option('smrtr_mn_cache');
            //delete_option('smrtr_mn_cache');
        
      
        $cache['menu_'.$this->menu->name] = $this->menu->data;
        
        
        add_option('smrtr_mn_cache', $cache, '', 'yes');
    }
    
    
    /**
     * Have we got a cache in the db
     */
    function hasCache()
    {
        if(!get_option('smrtr_mn_cache'))
            return false;
        
        $cache = get_option('smrtr_mn_cache');
        
        if($cache['menu_'.$this->menu->name])
        {
           
            $this->menu->data = $cache['menu_'.$this->menu->name];
            return true;
        }else return false;
                

    }
    
    static function structureAltered(){
        
        delete_option('smrtr_mn_cache');
    }
    
    
    function setMeta(){

        $OIDs = $this->menu->objectIDs;
        $meta = $this->menu->data = array();
        
        
        foreach($OIDs as $OID){
    
            $ID     = getMenu::get_page_ID($OID);
            $post   = get_post($ID);
            $object = get_post($OID);
            $parent = getMenu::get_object_meta($OID,'_menu_item_menu_item_parent');
            $type   = getMenu::get_object_meta($OID,'_menu_item_object');
            $url    = get_permalink($ID);
            
      
            $meta[$ID]->ID     = $ID ;
            
            $meta[$ID]->url    = $type=='custom'?getMenu::get_object_meta($OID,'_menu_item_url'):str_replace(url_host,'',$url);
            $meta[$ID]->target = $type=='custom'?'target="_blank"':'';
            $meta[$ID]->title  = $object->post_excerpt?$object->post_excerpt:$post->post_title;
            $meta[$ID]->slug   = $post->post_name;
            $meta[$ID]->order  = $object->menu_order;
            $meta[$ID]->parent = $post->post_parent?$post->post_parent:0;
            $meta[$ID]->object_parent = $parent?$parent:0;
            
            //children both native and menu
            $meta[$ID]->children = array_merge($this->getMenu_children($OID),$this->get_children($ID));
            $meta[$ID]->descendants = isset($this->pages->data[$ID]->descendants)?
                    $this->pages->data[$ID]->descendants:array();
            $meta[$ID]->ancestors  = isset($this->pages->data[$ID]->ancestors)?
                    $this->pages->data[$ID]->ancestors:array();
            $meta[$ID]->ancestor  = isset($this->pages->data[$ID]->ancestor)?
                    isset($this->pages->data[$ID]->ancestor):0;
            
        }
        
        $this->menu->data = $meta;
        return $this;
    }
    
    static function get_objectIDs($menu){
        global $wpdb;
        
        $res=$wpdb->get_results(sprintf("
            select tr.object_id from wp_terms t 
            left join wp_term_taxonomy tt on t.term_id = tt.term_id
            left join wp_term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id
            left join wp_posts p on p.ID=tr.object_id
            left join wp_postmeta m on m.post_id=tr.object_id
            where t.slug ='%s' and tt.taxonomy='nav_menu' and p.post_status='publish' group by(ID)
            order by p.menu_order asc LIMIT 200;
            ",$menu));
        
        
        if(!$res)
        return array();

        array_walk ($res, 'getMenu::de_object','object_id' );

        return $res;
    }
    
    static function de_object(&$item,$key,$property){
        $item=$item->$property;
        
        return $item;
    }
    
    
    static function get_page_ID($OID){
        global $wpdb;
        
        return $wpdb->get_var("select meta_value as ID from wp_postmeta where meta_key='_menu_item_object_id' and post_id=$OID");
    }
    
    

    static function get_object_meta($id,$key){
        global $wpdb;
        $res =$wpdb->get_var("
                select meta_value
                from   {$wpdb->prefix}postmeta 
                where  post_id=$id
                and meta_key='$key'" );
        return $res;
    }
    
    static function get_children($ID){
        global $wpdb;
        if(!$ID)
            return array();
        
        $res=$wpdb->get_results("select ID from wp_posts 
            where post_status='publish' and
            post_parent=$ID and post_type not in ('revision','attachment','nav_menu_item','mediapage')");
        
        array_walk ( $res, 'getMenu::de_object','ID' );
        
        if(is_array($res))
        return $res;
        else return array();
    }
    function getMenu_children($OID){

        global $wpdb;
        $res=$wpdb->get_results("
            select meta_value as ID from wp_postmeta
            where post_id in
            (select m.post_id as object_id from wp_postmeta m
            left join wp_posts p on m.post_id=p.ID
            where m.meta_key='_menu_item_menu_item_parent' and m.meta_value=$OID
            group by(p.ID)
            order by p.menu_order asc) 
            and meta_key='_menu_item_object_id' 
            ");
        
        if(!$res)
        return array();

        array_walk ( $res, 'getMenu::de_object','ID' );

        if(is_array($res))
        return $res;
        else return array();
    }
    
    function hasParent($id){

        if(isset($this->menu->data[$id]->object_parent)&&$this->menu->data[$id]->object_parent>0)
            return true;
        else return false;
    }
    
    public function setDescendants(){

        foreach($this->pages->data as $page):
            $this->pages->data[$page->ID]->descendants = array();
            
            foreach($this->menu->data as $child):
                if(isset($child->ancestors)&&count($child->ancestors)>0):
                    if(in_array($this->pages->data[$page->ID]->ID,$child->ancestors))
                    $this->menu->data[$page->ID]->descendants[] = $child->ID;
                endif;
            endforeach;
        endforeach;
        
    }
    
    public function is_current($ID){

        if(is_search()&&is_404())
            return false;
       
        if($this->page->ID==$ID)
            return true;
        
        if(isset($this->pages->data[$ID])){
            if(in_array($this->page->ID,$this->pages->data[$ID]->descendants))
                return true;

        }
        return false;
    }
}

?>