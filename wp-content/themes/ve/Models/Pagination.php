<?php 
/**
 * Rhino Pagination.
 * 
 * The repeater loops through any object and allows you to set a template filled with 
 * expressions that need to be evaluated to populate the the repeater.
 * 
 * You can additionally handle your own expressions by extending the class
 */


class Pagination_Model{
    var $max;
    var $min = 1;
    var $current;
    var $ntd = array();
    
    
    
    function __construct($max,$current,$show){

       
        $this->setMaxPages($max);
        $this->setCurrentPage($current);
        $this->setShow($show);
     
        $this->setPadding();
        $this->configureParams();
        
        return $this;
    }
    function setMaxPages($max){
        
        $this->max = $max;
        return $this;
    }
    function setCurrentPage($current){
        $this->current = $current;
        return $this;
    }
    
    function setShow($show){
        $this->show = $show;
        return $this;
    }
    
    function setPages(){
        
        $this->pages = array_combine(range($this->min,$this->max), range($this->min,$this->max));
        return $this;
        
    }
   
    private function setPadding(){
        
        if($this->show%2==0){
            $this->padding = ($this->show/2);
        }else {
            $this->padding = ($this->show-1)/2;
        }
        return $this;
    }
    
    
    
    private function configureParams(){
        
        $this->no = array();
        $this->left  = $this->current - $this->padding;
        $this->right = $this->current + $this->padding;

        //if 0
        if($this->left <= 0){
            $this->right += abs($this->left)+1;
            $this->left = 1;
        }
        
        if($this->right > $this->max){
            $this->left -= $this->right-$this->max;
            $this->right = $this->max;
        }
        
        $this->range = range($this->left,$this->right);
        
        if($this->max > $this->show){
         
            for($i=1;$i<=$this->max;$i++)
            {
                if($this->range[0] > 2 And $i == $this->range[0]) 
                    $this->no[] = '...';
                
                if($i==1 Or $i==$this->max Or in_array($i,$this->range))
                {
                    $this->no[] .= $i;
                }
                if($this->range[$this->show-1] < $this->max-1 And $i == $this->range[$this->show-1]) 
                    $this->no[] .= '...';
            }
            
        }
        return $this;
    }
    
    
}


