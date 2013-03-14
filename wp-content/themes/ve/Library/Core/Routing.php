<?php




/*
 * 
 * Override wordpress requests
 * 
 * 
 */


class Rhino_Router{
    
    var $params, $request, $controller;
    
    public function __construct(){

       
        //pre_dump($this);
        add_action( 'send_headers', array(&$this,'_ini'));
    }
   
    public function _ini() {
        
        if(!file_exists(abs_configs.'/Routing.php'))
                return false;
        
        $routes = include abs_configs.'/Routing.php';
        $routes = is_array($routes)?$routes:array();
        
        $routes['/ajax(./)?']   = 'Ajax_Controller';
        $routes['/ajax/.*']     = 'Ajax_Controller';
        
        
        $this->_configureSettings($routes);
        $this->_resolveRequest();
       
  
    }
    
    /**
     * Check if current requests is valid by our regex
     * Loops throu user defined regex and breaks when true
     * @return bool
     */
    private function _isRequestValid() {
  
        foreach($this->routes as $match => $controller){
            
            if(preg_match('#^'.trim($match,'/').'$#i',$this->request)){
                $this->controller = $controller;
                return true;
            }
        }
        
        
        return false;
        
    }
    
    /**
     * Configure setttings
     * 
     * @param string $slug 
     * @param string $include
     * @param string $default
     */
    private function _configureSettings($matches) {
        
        foreach($matches as $key => $controller){
         
         
            if(is_int($key)){
                $key = $controller;
                $controller = 'Rhino_Controller'; 
            }
            $this->routes["$key"] = $controller;
        }
        
       
    }
    
    /*
     * Resolve the request. 
     * 
     */
    private function _resolveRequest() {
        
        $this->request = trim($_SERVER['REQUEST_URI'],'/');
        $this->params  = explode('/',$this->request);
        
        $this->requestOverride();
       
    }
    
    
    /*
     * Request override
     * All the checks have been made. Redirect wordpress to go where you want.
     * 
     * Tell wordpress that this is not a 404 with a status header of 200
     */
    public function requestOverride() {

        if(!$this->_isRequestValid())
            return false;
  
        global $wp_query;
        unset($wp_query);
   
        $fileName = str_replace('_','',$this->controller);
 
        
        $layout = str_replace('Controller','',$fileName);
       
        $controller = abs_temp.'/Controllers/'.$fileName.'.php';
        
        $layoutParts = preg_split('/(?=[A-Z])/',$layout);
        
        $layout = strtolower(trim(implode('-',$layoutParts),'-'));
        
      
        if(file_exists($controller))
        require_once(abs_temp.'/Controllers/'.$fileName.'.php');
        else{
            
           echo "no $fileName exists";
        }
      
        

        //abs_temp.'/Layouts/'.
        status_header( 200 );
      
        
        $object = array();
        $object['params']  = $this->params;
        $object['request'] = $this->request;
        
        
        if(isset($this->params[0])&&$this->params[0]==='ajax'){
            
            if(isset($this->params[1])){
                
                $arr = explode('?',$this->params[1]);
                $object['action'] = $arr[0];
                
            }
          
        }
        
    
        if(class_exists($this->controller)||
                (class_exists($this->controller)&&
                isset($this->params[0])&&$this->params[0]==='ajax')){
            
                new $this->controller($layout,$object);
        }
        else new Rhino_Controller($layout,$object);

        die;
    }
}



/*
 * Rhino Dispatch Template
 * 
 * Lets hook into the 
 * 
 */
function Rhino_Dispatch_Template($template){
    
    global $post,$meta;

    $object = array();
    
    $parts = explode('/',$template);
        
    $fileName = end($parts);
    $fileName = substr_replace($fileName,'',-4);
    $fileName = preg_replace("#[\-]+#",' ',$fileName);
    $fileName = trim(ucwords($fileName));
    $fileName = preg_replace("#[\s]+#",'',$fileName);
    $className = preg_match("#[\d]#",$fileName[0])?'_'.$fileName:$fileName;

    $class   = ucfirst($className).'_Controller';
    $controllerPath = abs_temp.'/Controllers/'.ucfirst($fileName).'Controller.php';
    
    $object = array();
 
    if($post){
    
        $object = get_object_vars($post);
        $object = is_array($object)?$object:array();
        $object = array_merge($object,$meta);
        
    } 
        
    if(file_exists( $controllerPath )){

        include($controllerPath);

        if(class_exists($class))
            new $class($template,$object);

    }
    else new Rhino_Controller($template,$object);
        
    
}

?>
