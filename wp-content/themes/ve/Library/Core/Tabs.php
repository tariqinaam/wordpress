<?php
/*
 * Re
 */

class smrtrTabs{
        
    var $tabs=array();
    var $tab = 'smrtrTabs';
    var $page= 'pages';
    var $blockLocation;
    var $content;
    var $current;
    var $loaded = array();
    var $queryStrings = array();


    function __construct($tabs,$blockLocation,$level='smrtrTabs',$auto=true){
        $this->tabs = $tabs;
       
        $this->tab = $level;
        $this->blockLocation = $blockLocation;


        if(isset($_REQUEST[$this->tab]))
        {
             $this->current = $_REQUEST[$this->tab];
             
        }
        elseif(count($this->tabs)>0) {
            $this->current = $arr = array_shift(array_keys($this->tabs));
        }
     
        if($auto){
            if(isset($this->current)&&is_string($this->current))
            $this->{$this->current}();
        }
        return $this;
    }

    function addPages(){
        //$

    }

    function makeSlug(){

    }
    
    function keepQueryStrings($array){
        
        $this->queryStrings = $array;
        return $this;
    }

    function menu($attr=array(),$specialTabs=array()){

        $i=0;

        $acceptedTabs = array_keys($this->tabs);
        $page = isset($_REQUEST[$this->tab])?$_REQUEST[$this->tab]:'';
        $attributes = array();
        foreach($attr as $key=>$val){
            
            $attributes[] = sprintf('%s="%s"',$key,$val);
        }

        $atts = count($attributes)>0?sprintf(' %s',implode(' ',$attributes)):'';
        
        printf('<ul%s>',$atts);
        
            foreach($this->tabs as $slug => $menu):

                $url = $this->makeURL(array($this->tab=>$slug));
                if(in_array($page,$acceptedTabs)){

                    $current = $slug==$page?' class="active"':'';
                    $url = $slug==$page?'#!':$url;

                }elseif($i==0){
                    $current = ' class="active"';
                    $url = '#!';
                }else $current='';

                if(in_array($slug,array_keys($specialTabs)))
                {
                    if(isset($specialTabs[$slug]))
                    printf('<li%s ><a href="%s" style="%s">%s</a></li>',$current,$url,$specialTabs[$slug],$menu);
                }
                else printf('<li%s><a href="%s">%s</a></li>',$current,$url,$menu);
                $i++;

            endforeach;
        printf('</ul>');

    }

    function makeURL($pairs=array()){

        $urlParts = $this->getURLparts();
        $path = isset($urlParts['path'])?$urlParts['path']:'';
        $query= isset($urlParts['query'])?$urlParts['query']:'';
        $parts = $this->querytoArray($query);

        $parts = array_merge($parts,$pairs);
        $string = $this->arrayToQuery($parts);

        return sprintf('%s?%s',$path,$string);
    }


    function querytoArray($string){

        $arr = explode('&',$string);

        $pairs = array();
        foreach($arr as $pair){

            $parts = explode('=',$pair);
            
    
            if(count($this->queryStrings)>0){
                if(in_array($parts[0],$this->queryStrings))
                $pairs[$parts[0]] = isset($parts[1])?$parts[1]:'';
            }else{
                $pairs[$parts[0]] = isset($parts[1])?$parts[1]:'';
            }
        }
        
        
        return $pairs;
    }


    function arrayToQuery($arr){


        $pairs = array();

        foreach($arr as $key => $val){

                if(isset($val)&&!empty($val))
                $pairs[$key] = "$key=$val";
                else $pairs[$key] = "$key";
            }
            return implode('&',$pairs);
    }

    private function get_contents($file){
        ob_start();

        include $file;
        return ob_get_clean();
    }

    function __call($method,$attr){

        if(!in_array($method,array_keys($this->tabs)))
            $method = array_shift(array_keys($this->tabs));
        
        $this->loaded[] = $method;
        
        if(!is_dir($this->blockLocation)){
            printf('<div class="error"><p>Please add a block directory such as <code>%s</code></p></div>',$this->layoutLocation);
            return false;
        }
        

        $path = $this->blockLocation.'/'.$method.'.php';

        if(file_exists($path)){
            if(count($this->loaded)===1)
            $this->content = $this->get_contents($path);
        }
        else {
            printf('<div class="error"><p>Please add a block <code>%s</code></p></div>',$path);
            return false;
        }

    }

    function canvas(){
        
        
        echo $this->content;

    }

    function getURLparts(){

        return parse_url($_SERVER['REQUEST_URI']);  
    }
        
    
}
    



class smrtrURL{
    
    
    
    function __construct(){

        
    }

    static function args($pairs=array()){
        
        
        $urlParts = self::getURLparts();
        $path = isset($urlParts['path'])?$urlParts['path']:'';
        $query= isset($urlParts['query'])?$urlParts['query']:'';
        $parts = self::queryToArray($query);

        $parts['paged']=1;
        $parts = array_merge($parts,$pairs);
        $string = self::arrayToQuery($parts);
        
        return sprintf('%s?%s',$path,$string);
    }

    function queryToArray($string){

        $arr = explode('&',$string);

        
        $pairs = array();
        foreach($arr as $pair){

            $parts = explode('=',$pair);

            $pairs[$parts[0]] = isset($parts[1])?$parts[1]:'';
            
        }
        return $pairs;
    }


    function arrayToQuery($arr){


        $pairs = array();

        foreach($arr as $key => $val){

                if(isset($val)&&!empty($val))
                $pairs[$key] = "$key=$val";
                else $pairs[$key] = "$key";
            }
            return implode('&',$pairs);
    }


    function getURLparts(){

        return parse_url($_SERVER['REQUEST_URI']);  
    }
    
}
?>