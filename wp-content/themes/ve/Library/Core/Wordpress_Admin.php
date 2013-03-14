<?php
/**
 * Add_Admin_Pages
 * @Author Mwayi E. Dzanjalimodzi
 */

class Add_Admin_Pages
{

    var $adminPage; //store top level pages 
    var $adminSubPages; //store admin pages
    var $layoutLocation;
    var $blockLocation;//set block location
    
    public function __construct()
    {
    
        return $this;
    }

    /**
     * Used to create new pages
     * @param string $method
     */
    function __call($method,$attr){
        
        
        $method = str_replace('_','-',$method);
        $filename = str_replace('view-','',$method);
        
        $file = $this->layoutLocation.'/admin-'.$filename.'.php';
        
        if(!is_dir($this->layoutLocation)){
            printf('<div class="error"><p>Please add an admin layout directory such as <code>%s</code></p></div>',$this->layoutLocation);
            return false;
        }
        
        if(file_exists($file))
        include($file);
        else {
            printf('<div class="error"><p>Please add a file called admin-%s in admin layout directory <code>%s</code></p></div>',$filename,$file);
            return false;
        }
    }
    /**
     * setLayoutLocation
     * @param type $representation 
     */
    public function setLayoutLocation($location){
        
        
        if(!is_dir($location)){
            printf('<div class="error"><p>Admin layout directory <code>%s</code> does not exist</p></div>',$location);
            return false;
        }
        
        $this->layoutLocation = $location;
        return $this;
        
    }
    
    
    public function setBlockLocation($location){
        
        
        if(!is_dir($location)){
            printf('<div class="error"><p>Admin layout directory <code>%s</code> does not exist</p></div>',$location);
            return false;
        }
        
        $this->blockLocation = $location;
        return $this;
        
    }
    public function addPage($name,$slug,$capability){
        
        $this->adminPage->{$slug}->name = ucwords($name);
        $this->adminPage->{$slug}->slug = $slug;
        $this->adminPage->{$slug}->caps = $capability;
        $this->adminPage->{$slug}->control = 'view_'.str_replace('-','_',$slug);
        $this->adminPage->{$slug}->type = 0;
        return $this;
    }
    
    /**
     * addSubPage
     * @param type $representation 
     */
    public function addSubPage($name,$slug,$capability,$parent){
        
        if(!isset($this->adminPage->{$parent}))
        return false;
        
        $slug = $parent.'-'.$slug;
        $this->adminPage->{$slug}->name = ucwords($name);
        $this->adminPage->{$slug}->slug = $slug;
        $this->adminPage->{$slug}->caps = $capability;
        $this->adminPage->{$slug}->control = 'view_'.str_replace('-','_',$slug);
        $this->adminPage->{$slug}->parent = $parent;
        $this->adminPage->{$slug}->type = 1;
        
        return $this->adminPage->{$slug};
        
    }
    
    public function createPages(){
        
        
        foreach($this->adminPage as $page){
            
            if($page->type==0)
            add_object_page($page->name,$page->name,$page->caps,$page->slug, array(&$this,$page->control));
            else 
            add_submenu_page($page->parent,$page->name,$page->name,$page->caps,$page->slug,array(&$this,$page->control));
        
        }
    }
    
    public function getBlock($file,$subFolders='',$vars=array(),$ext='.php'){
        
        
        $path = $this->blockLocation.$subFolders.'/'.$file.$ext;
        
        if(!is_dir($this->blockLocation)){
            printf('<div class="error"><p>Please add a block directory such as <code>%s</code></p></div>',$this->layoutLocation);
            return false;
        }
        
        if(file_exists($path))
        include($path);
        else {
            printf('<div class="error"><p>Please add a block <code>%s</code></p></div>',$path);
            return false;
        }
        
    }
    
    
    public function setAdminPage($slug){
        
        if($slug)
        return $this->currentPage = @$this->adminPage->{$slug};
    }
    
    
    public function getAdminPage($slug){
        
        return $this->adminPage->{$slug};
    }
    
    public function getCurrentPage(){
        
        return $this->getUrlVar('page');
        
    }
    
    public function getUrlVar($var){
        
        if($var){
            return isset($_GET[$var])?$_GET[$var]:false;
        }else return false;
    }

}