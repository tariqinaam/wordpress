<?php 

class sideNav extends Pages_Model
{
    
    function __construct(){
        
        parent::__construct();
       
      
        if(!isset($this->page->children))
            return false;
        $this->printNav($this->page->children,0);
    }
    
    function printNav($pages, $depth = -1,$current=' ')
    {
        $subdepth = $depth + 1;
        
        printf ('<ul%s>',$current);
        foreach ($pages as $ID){
            if($this->count_ancestors($ID)==$subdepth):
                
                $page = $this->pages->data[$ID];

                $current = $this->is_current($ID)?' class="selected" ':' ';
                printf('<li%s><a href="%s">%s</a>',$current,$page->url,$page->title);
                if($this->hasChildren($ID))
                   $this->printNav($page->children, $subdepth,$current);
                
                echo '</li>';
            endif;
        }
        echo '</ul>';
        
    }
}



class printMenu extends Menu_Model{
    
    /*
     * 
     * custom menus for 
     */
    function main(){
        echo '<ul>';

        foreach($this->menu->data as $ID => $page){
            if(!$this->hasParent($ID)){
                
                if($this->is_current($ID))
                     printf('<li class="selected"><a href="%s">%s</a>',$page->url,$page->title);
                else printf('<li><a href="%s" %s>%s</a>',$page->url,$page->target,$page->title);
            }
        }
        echo '</ul>';
    }
    
    
    
    
}

class printFooter extends Menu_Model{
    
    /*
     * 
     * custom menus for 
     */
    function main(){
        
        echo '<ul>';

        foreach($this->menu->data as $ID => $page){
                printf('<li><a href="%s">%s</a>',$page->url,$page->title);
        }
        echo '</ul>';
    }
    
}



?>