<?php

class smrtrFormBase{
    
    private $formClass = 'smrtrForm';
    var $data = '';
    var $before;
    var $after;
    var $ignore_attributes = array();
    
    
    
    public function __construct(){
    }
    
    /**
     * Get the filter methods and their parameters
     * @param type $rules
     * @return $this
     */
    function setFilter($rules=array()){
        
        foreach($rules as $rule => $arg){
            //$this->field->model->{$this->current}->filter[$rule]['arg'] = $arg;
            //$this->field->model->{$this->current}->filter[$rule]['result'] = false;
        }
        return $this;
        
    }
    
    /**
     * Generic initialise this property
     * @param string $key, string $property, string $default
     * @return mixed 
     */
    public function setVar($attr,$name,$default=''){
        if(is_array($attr))
        return isset($attr[$name])?$attr[$name]:$default;
    }

    /**
     * Generic initialise this property
     * @param string $key, string $property, string $default
     * @return mixed 
     */
    public function setProp($object,$property,$default=''){
        
        if(property_exists($object,$property)){
            $prop = $object->{$property};
        }else $prop = false;
        
        return isset($prop)?$prop:$default;
    }
    
    
    
    
    
    /**
     * This method is poorly implemented
     */
    public function __call($method,$attr){
        
        if($type = $this->_methodType($method)){
        
            //get the attribute bieng tinkered with, i.e. name, value
            $attribute = strtolower($this->_getSetProperty($method));
            
            $this->currentAttribute = $attribute;
            
            switch($attribute){
                

                case 'attributes':
                    $name = $attr[0];
                    
                    foreach($attr[1] as $property=>$val){
                        $this->_set($property,$val,$name);
                    }
                    
                    return $this;

                break;
                
                
                default:
                    //get the attribute get or setter
                    if($type==='set'){
                        $part = isset($attr[0])?$attr[0]:'';
                        $value = isset($attr[1])?$attr[1]:$part;
                        $name  = isset($attr[1])?$attr[0]:$part;
                        
                        
                        $this->getCalledMethodParams($attr);
                        
                        if($this->currentCount==1)
                        $this->_set($attribute,$this->currentValue);
                        else $this->_set($attribute,$this->currentValue,$this->currentName);

                        return $this;
                    }
                    
                    
                    if($type==='get'){

                        if(isset($attr[0])){

                            return $this->_get($attribute,$attr[0]);
                        }
                        else return $this->_get($attribute);
                    }
                    
                break;
            }
        }
        
        return false;
    }
   
    /*
     * Get the called methods attributes
     */
    
    public function getCalledMethodParams($attr){
        
        if(isset($attr)&&is_array($attr)){
            $this->currentCount = $count = count($attr);

            switch ($count){
                case 1:
                    $this->currentValue = $attr[0];
                    
                    break;
                case 2:
                    $this->currentName  = $attr[0];
                    $this->currentValue = $attr[1];
                    break;

            }
        }
        return $this;
    }
    /*
     * Get field attribute
     * : Make sure we can retrieve when in field or form mode
     * @param string $name
     * @param string $attribute
     * @return $this
     */
    protected function _get($attribute,$name=''){
        
        if($this->isFormClass()){
            echo $this->field->data->{$name}->{$attribute};
            
            if(isset($this->field->data->{$name}->{$attribute}))
                return $this->field->data->{$name}->{$attribute};
        }else{
            if(isset($this->{$attribute}))
                return $this->{$attribute};
        }
    }
    
    /*
     * Set field attribute
     * : Make sure we can retrieve when in field or form mode
     * @param string $name
     * @param string $attribute
     * @param string $value
     * @return $this
     */
    protected function _set($attribute,$value,$name=''){
        
        if($this->isFormClass()){
        
            if($name&&isset($this->field->data->{$name})){
                
                
                //echo "<h2>we are setting $name's attribute:$attribute to $value</h2>";
                $this->field->data->{$name}->{$attribute} = $value;
                
                $this->setHTML();
            }
            return $this;
        }
        else {
            $this->{$attribute} = $value;
            $this->setHTML();
            return $this;
        }
    }
    
    /**
     * Is this the form class
     * @return bool
     */
    private function isFormClass(){

        if(get_called_class()===$this->formClass)
            return true;
        else return false;
    }
    /**
     * Is this call a getSet property
     * @param string $method
     * @return string bool
     */
    protected function _methodType($method){
        
        $arr = array('set','get');
        
        
        if(strlen($method)<3)
            return false;
        
        $type = substr($method,0,3);
        
        if(in_array($type,$arr)){
            
            return $type;
        }
        
    }
    /**
     * what property wants to get get or set
     * @param string $method
     * @return string bool
     */
    protected function _getSetProperty($method){
        
        return substr($method,3);
    }
    
    /**
     * Set attribute style by key value pairs
     * @param array $styles
     * @return $this
     */
    public function setStyle($styles=array(),$name=''){
        
        
        $s = array();
        foreach($styles as $style=>$val){
            
            if($style&&$val)
            $s[] = "$style:$val";
        }
        
        if($this->isFormClass()){
            if(isset($name))
            $this->field->data->{$name}->style = implode(';',$s);
        }else{
            $this->style = implode(';',$s);
        }
        
        return $this;
    }
   
    /**
     * Set attribute style by key value pairs
     * @param array $styles
     * @return $this
     */
    public function setData($type,$value,$name=''){
        
        if($this->isFormClass()){
            if(isset($name))
            $this->field->data->{$name}->data['data-'.$type] = $value;
        }else{
            $this->data['data-'.$type] = $value;
        }
        
        return $this;
    }
   
    /**
     * contstruct Attributes
     * @param string $key, array $exclude
     * @return array $return
     */
    public function constructAttributes($kvp=array(),$ignore=array()){
        
        //set all attributes bar 
        $attr = array();
        foreach($kvp as $key => $val){

            if(!is_array($val)){
                if($val!=''&&!is_object($val)){
                    if(!in_array($key,$ignore))
                    $attr[$key] = sprintf('%s="%s"',$key,$val);
                }
            }
        }
        
        return $attr;
    }
    
    
    
    
    
    /**
     * Get submitted values
     * @return array $data
     */
    public function getSubmitValues(){
        
        //get the method requested
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $data = $method=='post'?$_POST:$_GET;
        return is_array($data)?$data:array();
    }
    
    /**
     * Get submitted values
     * @return array $data
     */
    public function getSubmitValue($name){
        
        //get all the submitted values
        $data = $this->getSubmitValues();
        return isset($data[$name])?$data[$name]:false;
    }
    
    /**
     * Does the field exist in the post?
     * @return bool
     */
    public function fieldExists($name){
        
        $data = $this->getSubmitValues();
        return isset($data[$name])?true:false;
    }
    
    
    /**
     * Filter this input by applying htmlentities and remove slashes
     * @return bool
     */
    public function filterInput($key,$value){
        
        if(!$key==='value'&&!is_string($value))
            return $value;
        
        
        if(!isset($this->type))
            return $value;
        
        //no need to do anything to arrays
        $filterList = array('text','textarea');
        
        //if $value is not strinf, not on filter list or empty
        if(!in_array($this->type,$filterList)&&$value!='')
            return $value;
        
        if(is_array($value)){
            
            return $value;
        }
        //prevent the slashes from being added too
        
        return str_replace("\\",'',htmlspecialchars($value,ENT_QUOTES));//htmlentities($value));
        
    }
    
    /**
     * Get the post values
     * @return array $data
     */
    public function getPostValues(){
        
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $data = $method=='post'?$_POST:$_GET;
        return is_array($data)?$data:array();
    }
    
    
    
    public function getFieldValue($name){
        
        
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $data = $method=='post'?$_POST:$_GET;
        return isset($data[$name])?$data[$name]:false;
    }
    
    public function processDataTypes($attributes){
        
        if(!isset($attributes['data']))
            return $attributes;
        if(!is_array($attributes['data']))
            return $attributes;  
        
        $merge = array();
        foreach ($attributes['data'] as $key => $val){
            
            if(!is_array($val))
            $merge[$key] = $val;
        }
        
        return array_merge($merge,$attributes);
        
    }
    
    /**
     * Generate Valid attributes
     * @return array $attr 
     */

    public function getAttributes($name=''){
     
        $attributes = array();
        if(isset($this->currentName)&&$this->isFormClass()){
            if(isset($this->field->data->{$this->currentName}))
                $attributes = get_object_vars($this->field->data->{$this->currentName});
        }
        else $attributes = get_object_vars($this);
    
        $data = $this->getSubmitValues();
        
        $attributes = $this->processDataTypes($attributes);
        
        $ignoreList[] ='validation';
        $ignoreList[] ='ignore_attributes';
        $ignoreList[] ='html';
        $ignoreList[] ='formClass';
        $ignoreList[] ='currentValue';
        $ignoreList[] ='currentCount';
        $ignoreList[] ='currentName';
        $ignoreList[] ='currentAttribute';
        $ignoreList[] ='data';
        $ignoreList[] ='title';
        $ignoreList[] ='errors';
        $ignoreList[] ='description';
        $ignoreList[] ='before';
        $ignoreList[] ='after';
        $attr = array();
        
        foreach($attributes as $key => $val){
            
            if(is_object($val))
                continue;
            //
            $ignoreList = array_merge($this->ignore_attributes,$ignoreList);
            
            
            if(!in_array($key,$ignoreList)){
                
                    if($key==='value')
                    {
                        if(isset($this->name))
                        {
                            
                            //if checkboxes are unset it is because they have been unchecked
                            
                            if($this->isSubmit()){//Check if form has been set
                              
                                if($this->type==='checkbox')
                                    $val = isset($data[$this->name])?$data[$this->name]:'';

                                else $val = isset($data[$this->name])?$data[$this->name]:$val;
                               
                            }
                    
                        //echo "<p>this is the name:$this->name which has a value:$val</p>";
                        
                        }
                    }
                    $attr[$key]  = $this->filterInput($key,$val);
            }
        }
        
        return $attr;
    }
    
    
    /**
     * Set the HTML of the 
     * @return array $attr 
     */
    public function setHTML($name=''){
        
        $attr = $this->getAttributes($name);
        $attributes = $this->constructAttributes($attr);
        
        
        if(!$this->isFormClass()){
            $html = $this->html = sprintf('<input %s/>',implode(' ',$attributes)); 
        }else {
            $html = $this->field->data->{$this->currentName}->html = sprintf('<input %s/>',implode(' ',$attributes)); 
        }
        
        if($this->before)
        $html =$this->before.$html;
        
        if($this->after)
        $html.=$this->after;
        
        return $html;
    }
    
    
    /*
     * Form has been submitted
     * @return bool
     */
    public function isSubmit(){
        
        
        if(isset($_POST['smrtrFormSubmitted']))
            return true;
        
        if(isset($_GET['smrtrFormSubmitted']))
            return true;
        
        return false;
            
    }
    
}



?>
