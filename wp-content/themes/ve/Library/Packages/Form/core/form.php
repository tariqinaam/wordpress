<?php

class smrtrForm extends smrtrFormBase{

    var $errors = 0;
    var $submit;
    var $begin,$end;
    
    function __construct($action='',$method='POST',$attr=array()){

        $this->form->action = $action?$action:$_SERVER['REQUEST_URI'];
        $this->form->method = $method;
        $this->form->attr   = $attr;
       
        $this->submit->name  = 'smrtrSubmit';
        $this->submit->type  = 'submit';
        $this->submit->value = 'submit';
       
        $this->renderSubmit();
       
         
        return $this;
    }
    
    
    public function renderSubmit(){
        
        $attr =  get_object_vars($this->submit);
        
        $attributes = $this->constructAttributes($attr,array('html'));
        $this->submit->html = sprintf('<input %s/>',implode(' ',$attributes));
        return $this;
    }
    
    
     /**
     * set up form 
     * @return string
     */
    public function begin($echo=true){
        
        $arr = $attributes =  array();
        
        $arr['action'] = $this->form->action;
        $arr['method'] = $this->form->method;
        
        if(isset($this->form->ID))
        $arr['id'] = $this->form->ID;
        
        if(isset($this->form->enctype))
        $arr['enctype'] = $this->form->enctype;
        
        if(isset($this->form->class))
        $arr['class'] = $this->form->class;
        
        $arr = array_merge($arr,$this->form->attr);
        
        foreach($arr as $key => $val){ 
            $attributes[] = sprintf('%s="%s"',$key,$val);
        }
        
        $this->attributes = count($attributes>0)?implode(' ',$attributes):'';
        
        
        $begin = sprintf('<form %s><input type="hidden" name="smrtrFormSubmitted"/>',$this->attributes);
        
        
        if($echo)
        echo $begin;
        else return $begin;
    }
    
    /**
     * End form
     *  
     */
    public function end($echo=true){
        
        $end ='</form>';
        
        if($echo)
        echo $end;
        else return $end;
    }
   
    /*
     * Add a field to the form
     * @param object 
     */
    function addField($fieldObject){
        if(isset($fieldObject->name))
        $this->field->names[$fieldObject->name] = $fieldObject->name;
        
        $properties = get_object_vars ($fieldObject );
        
        foreach($properties as $property => $value){
            
            if(isset($fieldObject->name)&&isset($property))
            $this->field->data->{$fieldObject->name}->{$property} = $value;
        }
        
        return $this;  
        
    }
    
    
    /*
     * Add fields to the form object
     * @param array $fields
     */
    public function addFields($fields=array()){
        
        foreach($fields as $fieldObject){
            
            if(is_object($fieldObject))
            $this->addField($fieldObject);
            
        }
        return $this;
    }
    
    
    /*get for elements*/
    
    public function getElements(){
        
        
        return $this->field->data;
        
        
    }
    /*
     * Set ID of this form
     * @param array $fields
     */
    public function setID($id){
        $this->form->ID = $id;
        return $this;
    }
    
    /*
     * Set Method of this form
     * @param array $fields
     */
    public function setMethod($method){
        $this->form->method = $method;
        
        return $this;
    }
    /*
     * Set action of this form
     * @param array $action
     * @return $this
     */
    public function setClass($class){
        $this->form->class = $class;
        return $this;
    }
    
    /*
     * Set action of this form
     * @param array $action
     * @return $this
     */
    public function setAction($action){
        $this->form->action = $action;
        return $this;
    }
    
    
    /*
     * Set enctype
     * @param array $action
     * @return $this
     */
    public function setEnctype($enctype){
        $this->form->enctype = $enctype;
        return $this;
    }
    
    /*
     * Has this form got errors
     * @return bool
     */
    public function hasErrors(){
        
        if($this->errors==0)
            return false;
        else return true;
    }
    
    
    /**
     * Get field names
     * @return array $data
     */
    function getFieldNames(){
        
        return is_array($this->field->names)?$this->field->names:array();
    }
    
   
    
    /**
     * Handle any validation
     * @param type $rules
     * @return $this
     */
    function onSubmit(){
        
        
        $data   = $this->getPostValues();
        $fields = $this->getFieldNames();
        
        if(count($data)==0)
            return false;// post hasnt been posted or there are no fields
            
        //commence validation
        
        $validate = new smrtrValidation($data); //use 
        
        foreach($fields as $name){
            
            $val = isset($data[$name])?$data[$name]:'';
            
            $this->field->data->{$name}->errors = '';
            if(!$rules= $this->getRules($name))
                continue;    
            
            $field = $this->field->data->{$name};
            
          
            //new collection of messages
            $msgs = array();
            
            foreach($rules as $rule => $arg){
                
               $message = $this->getErrorMessages($name,$rule);
               
         
               $message = $message?$validate->{$rule}($val,$arg,$field,$message):$validate->{$rule}($val,$arg, $field );
               
               if($message){
                   
                   $this->errors++;
                   $this->field->data->{$name}->errors = $message;
                 
                   $msgs[$rule] = sprintf('<li class="error-%s">%s</li>',$rule,$message);
               }
            }
            if(count($msgs)>0){
                $this->field->data->{$name}->errors = sprintf('<ul id="error-%s" class="errorMessage">%s</ul>',$name,implode('',$msgs));
            }
                
        }
        return $this;
    }
    
    public function handleFiles($upload_path,$form,$fields,$formname,$user_id){
        
        $message = '';
        $errors  = true;
        $file_to_upload = false;
        
        
        foreach($_FILES as $name => $data){
            
           
            
            foreach($fields as $field){
                
                //pre_dump($field->name);
                
                if($field->name==$name){
                    
                    
                    $filename  = basename($_FILES[$name]['name']);
                    
                    $size = $_FILES[$name]['size'];
                    $path_info = pathinfo($filename );
                    
                    //if(!$_FILES['cv']['name'])
                    //$_POST['stored_'.$name] = '';
                    
                    /*
                     * Check file extensions
                     */
                  
                    if(!isset($_POST['check_'.$name])){
                        $_POST['stored_'.$name] = '';
                        
                        Ibro_Attachments::delete($user_id.'-'.$formname.'-'.$name);
                    }else{
                        
                        
                        
                    }
                    
                 
                    if(isset($path_info['extension'])&&in_array($path_info['extension'],$field->formats)&&$field->max_filesize>$size){
                    
                        
                        $filename = str_pad($user_id, 7, "0", STR_PAD_LEFT).
                                '-'.$formname.
                                '-'.$name.
                                '-'.str_replace(' ','_',$filename);
                        
                        $filepath = $upload_path.DIRECTORY_SEPARATOR.$filename;
                        
                        
                        $data = array();
                        
                        $data['userID'] = $user_id;
                        $data['fieldSlug'] = $name;
                        $data['formSlug'] = $formname;
                        $data['attachmentPath'] = $filepath;
                        $data['attachmentName'] = $filename;
                        $data['attachmentSlug'] = $user_id.'-'.$formname.'-'.$name;
                        
                        $file_to_upload = true;
                        
                        
                        if(move_uploaded_file($_FILES[$name]['tmp_name'], $upload_path.'/'.$filename)){
                            
                            Ibro_Attachments::insert($data);
                            
                            $message = 'Could not upload ';
                            $errors  = false;
                            
                        }else{
                            
                            $message = 'Could not upload the file. Check filesize and extension.';
                            
                        }
                        
                        
                    }else{
                        //$field->setErrors('<ul id="error-'.$name.'" class="errorMessage"><li class="error-format">Please upload file in correct format</li></ul>');
                        
                    }
                    
                }
            }
            
        }
        
        $return = array();
        $return['fields'] = $fields;
        $return['file_to_upload'] = $file_to_upload;
        $return['errors'] = $errors;
        $return['message']= $message;
     
        return $return;
        
    }
    /**
     * Get error Message 
     * @param string $name
     * @return $this
     */
    public function getErrorMessages($name,$method=false){
    
        if($method){
            if(isset($this->field->data->{$name}->errorMessages[$method])){
                return $this->field->data->{$name}->errorMessages[$method];
            }return '';
        }else{
            if(isset($this->field->data->{$name}->errorMessages)){
                return $this->field->data->{$name}->errorMessages;
            }return array();
        }
        
    }
    
    /**
     * Get the rules of this form if any
     * @param string $name
     * @return $this
     */
    public function getRules($name){
     
        if(isset($this->field->data->{$name}->validation)){
            $rules = $this->field->data->{$name}->validation;
            return is_array($rules)?$rules:array();
        }
        return false;
    }
    
    public function setSubmitClass($class){
        $this->submit->class = $class;
        $this->renderSubmit();
        return $this;
    }
    public function setSubmitStyle($styles=array()){
        
        $s = array();
        foreach($styles as $style=>$val){
            
            if($style&&$val)
            $s[] = "$style:$val";
        }
        
        $this->submit->style = implode(';',$s);
        $this->renderSubmit();
        return $this;
    }
    
    
    public function setSubmitID($ID){
        $this->submit->ID = $ID;
        $this->renderSubmit();
        return $this;
    }
    
    public function setSubmit($name,$value){
        $this->submit->name  = $name;
        $this->submit->value = $value;
        $this->submit->type = 'submit';
        $this->renderSubmit();
        return $this;
    }
    public function getSubmit($echo=true){
        $this->renderSubmit();
        
        if($echo)
        echo $this->submit->html;
        else return $this->submit->html;
    }
    
    
    public function getFieldData($name,$data){
        
        if(isset($this->field->data->{$name})){
            
            if(isset($this->field->data->{$name}->{$data})){

                return $this->field->data->{$name}->{$data};

            }  
        }
        
        return '';
    }
    
    
    function setFieldData($field,$data){
    
        foreach($field as $key => $value){

            $name =  $field[$key]->name;

            if(isset($data[$name])){
                $field[$key]->setValue($data[$name]);
            }
        }
        return $field;
    }
}


?>
