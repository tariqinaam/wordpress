<?php
/*
 * Get Raw page data in wordpress
 * 
 */

class Pages_Model{
    
    var $page;
    var $pages;
    
    public function __construct(){
        
        $this->setThis();
        
        //if we have cache then 
        if(!$this->hasCache()){
            //if we dont have cache set 
            $this->setIDs();
            $this->setMeta();
            $this->setDescendants();
            $this->setCache();
            
        }
        $this->setPageChildren();
        return $this;
    }
    
    /**
     * Set page cache
     */
    function setCache(){
    
        if(get_option('smrtr_pg_cache'))
            delete_option('smrtr_pg_cache');
        
        $cache = array();
        
        $cache['data'] = $this->pages->data;
        $cache['ids']  = $this->pages->ids;
        
        add_option('smrtr_pg_cache', $cache, '', 'yes');
    }
    
    
    /**
     * Have we got a cache in the db
     */
    function hasCache()
    {

        if($cache=get_option('smrtr_pg_cache'))
        {
            $this->pages->data = $cache['data'];
            $this->pages->ids  = $cache['ids'];
            return true;
        }else return false;
                

    }
    /**
     * Set page children
     * @return $this;
     */
    function setPageChildren(){
        
        if(isset($this->pages->data[$this->page->ancestor]->children)&&is_array($this->pages->data[$this->page->ancestor]->children))
        $this->page->children = $this->pages->data[$this->page->ancestor]->children;
        else $this->page->children = array();
        return $this;
    }
    
    /**
     * Utlity tool to convert object to an array
     * @params object $item
     * @params string $key
     * @params string $property
     * @return array $items;
     */
    static function deObject(&$item,$key,$property){
        $item=$item->$property;
        
        return $item;
    }
    
    /**
     * Set page ancestor
     * @return $this;
     */
    function setPageAncestor(){
        wp_reset_postdata();
        global $post;
        $this->page->ID         = isset($post->ID)?$post->ID:0;
        $this->page->title      = isset($post->post_title)?$post->post_title:'';
        $this->page->ancestor   = isset($post->ancestors)&&is_array($post->ancestors)&&count($post->ancestors)>0?end($post->ancestors):$this->page->ID ;
    }
    
    
    /**
     * Set IDS
     * @return $this;
     */
    function setIDs(){
        global $wpdb;
        $res = $wpdb->get_results(" 
            select ID,post_title,menu_order  from wp_posts
            where post_status='publish' 
            and post_type not in ('revision','nav_menu_item','attachment')
            order by 
            case when menu_order in('', '0') 
            then 1000000 else 0 end,
            menu_order,post_title asc;
            ");
        
        
        if(!$res)
        return false;

        array_walk ($res, 'getPages::deObject','ID' );

        $this->pages->ids = $res;
        
        return $this;
     
    }
    
    /**
     * Set page children
     * @return $this;
     */
    function setThis(){
        global $post;
        
        $this->page->ID         = isset($post->ID)?$post->ID:0;
        $this->page->title      = isset($post->post_title)?$post->post_title:'';
        $this->page->ancestor   = isset($post->ancestors)&&is_array($post->ancestors)&&count($post->ancestors)>0?end($post->ancestors):$this->page->ID ;
    }
    
    
    /**
     * Set Meta data of pgae node
     * @return $this;
     */
    function setMeta(){

        foreach($this->pages->ids as $id)
        {
            $post = get_post($id);
            $data[$id]->ID    = $id;
            $url = get_permalink($id);
            
            $data[$id]->url   = str_replace(url_host,'',$url);
            $data[$id]->title = $post->post_title;
            $data[$id]->slug  = $post->post_name;
            $data[$id]->order = $post->menu_order;
            $data[$id]->parent= $post->post_parent;
            $data[$id]->template = get_post_meta($post->ID,'_wp_page_template',true);
            if($children=getPages::getChildren($id))
                 $data[$id]->children = $children;
            else $data[$id]->children =array();

            if(isset($post->ancestors)&&count($post->ancestors)>0){
                $data[$id]->ancestors = array_reverse($post->ancestors);
                $data[$id]->ancestor  = $data[$id]->ancestors[0];
            }
            else{
                $data[$id]->ancestors = array();
                $data[$id]->ancestor  = $id;
            }
        }
        $this->pages->data = $data;
        
        
        //pre_dump($this);
        return $this;
    }
    
    /**
     * Has Children
     * @return bool;
     */
    function hasChildren($id){
        if(isset($this->pages->data[$id]->children)&&count($this->pages->data[$id]->children)>0)
            return true;
        else return false;
    }
    
    /**
     * Has Parent
     * @return bool;
     */
    function hasParent($id){
        if(isset($this->pages->data[$id]->parent)&&$this->pages->data[$id]->pre>0)
            return true;
        else return false;
    }
    
    /**
     * Count Ancestors
     * @return int;
     */
    function count_ancestors($id){
        if(isset($this->pages->data[$id]->ancestors)&&is_array($this->pages->data[$id]->ancestors))
            return count($this->pages->data[$id]->ancestors);
        else return 0;
    }
    
    
    /*
     * set menu top level items
     */
    
    static function getChildren($post_id){
        global $wpdb;
        if(!$post_id)
            return false;
        
        $res = $wpdb->get_results("select ID from wp_posts 
            where post_status='publish' and
            post_parent=$post_id and post_type not in ('revision','attachment','nav_menu_item','mediapage')
            order by 
            case when menu_order in('', '0') 
            then 1000000 else 0 end,
            menu_order,post_title asc;");
        
        array_walk ( $res, 'getPages::deObject','ID' );
        
        return $res;
    }
    
    
    /**
     * Set decendants of page nodes
     * @return $this;
     */
    public function setDescendants(){

        foreach($this->pages->data as $page):
            $this->pages->data[$page->ID]->descendants = array();
            
            foreach($this->pages->data as $child):
                if(isset($child->ancestors)&&count($child->ancestors)>0):
                    if(in_array($this->pages->data[$page->ID]->ID,$child->ancestors))
                    $this->pages->data[$page->ID]->descendants[] = $child->ID;
                endif;
            endforeach;
        endforeach;
        
        return $this;
    }
    
    
    public function is_current($ID){

        if(is_search()&&is_404())
            return false;
       
        //we are on this page
        if($this->page->ID==$ID)
            return true;
        
        //echo $this->page->ID;
        if(in_array($this->page->ID,$this->pages->data[$ID]->descendants)){
            
            return true;
        }
        return false;
    }
    
    
    static function structureAltered(){
        
        delete_option('smrtr_pg_cache');
    }
    
}

?>