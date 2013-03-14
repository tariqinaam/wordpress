<?php
/**
 * A simple form class 
 * - Validatation,
 * @Author Mwayi E. Dzanjalimodzi
 */

class smrtrValidation{
    
    var $value;
    var $method;
    var $errors = array();
    //we need a message default
    public function __construct($data) {
        $this->data = $data;
        return $this;
    }
    public function __call($method,$attr){
        
        
        //this validation does not exist
    }
    
    /**
    * Required i.e. cannot be empty
    * @param $value array|string
    * @param $param string
    * @return bool
    */
    public function required($value,$param,$field,$message='This field is required'){

        if($field->type=='file'){
            
            
            
            $value = isset($_POST['stored_'.$field->name])?$_POST['stored_'.$field->name]:'';
            $value = isset($_FILES[$field->name]['name'])?$_FILES[$field->name]['name']:$value;
            
            
        }
        //pre_dump($field);
        //pre_dump($param);
        //pre_dump($_POST);no-file-selected;
        if(!is_array($value)){
            
            if(empty($value)&&$param==true){
                return $message;
            }else {
                return false;
            }
        }else {
           
            if(count($value)<1&&$param==true){
                return $message;
            }else return false;
        }
    }
    
    public function minlength($value,$param){
          pre_dump($value);
        pre_dump($param);
    }
    
    public function maxlength($value,$param,$message='This field has a max length'){
      
        if(is_array($value))
            return false;
        if(empty($value))
            return false;
        elseif(strlen($value)>intval($param))
            return $message;
        else return false;
    }
    
    public function range($value,$param){
          pre_dump($value);
        pre_dump($param);
    }
    
    
    public function date($value,$message="Please enter a date that is in dd/mm/yyyy format"){
        
        
        if(!$value)
            return false;
        $match = preg_match('/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/',$value);
        
        
        
        if($match===0){
            return $message;
        }
        
        return false;
    }
    
    
    public function year($value,$message="Please enter a date that is in yyyy format"){
        
        
        if(!$value)
            return false;
        $match = preg_match('/^\d{4}$/',$value);
        
        
        
        if($match===0){
            return $message;
        }
        
        return false;
    }
    public function email($email,$message="Please enter a valid email"){

        if(!$email)
            return false;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ 
            
            return $message;
        }
        return false;
    }
    
    public function numeric($number,$message="Please enter a number"){

        
        if($number==0){
            return false;
        }
        if(!filter_var($number, FILTER_VALIDATE_INT)){ 
            
            echo $number;
            return $message;
        }
        
     
        return false;
    }
    
    public function float($val,$message="Please enter a number with decimals"){

        if(!is_float($val)){ 
            
            return $message;
        }
        return false;
    }
    
    
    public function format($val,$param){
        
        return $this->{$param}($val);
        
    }
    
    /**
    * Check postcode is valid
    *
    * @param postcode String
    *   Pass a postcode string, will be checked against all postcode RegEx
    * @return Bool
    *   True if postcode is correct, false if not.
    */
    public function checkPostcode($toCheck, $returnValidPostcode=false)
    {
        // Permitted letters depend upon their position in the postcode.
        $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
        $alpha3 = "[abcdefghjkstuw]";                                   // Character 3
        $alpha4 = "[abehmnprvwxy]";                                     // Character 4
        $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5

	// AANA format, this is added first so pattern 1 doesnt trim off the first letter when returning valid postcode
        $pcexp[6] = '^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2}'.$alpha2.'{0,1})([0-9]{1}'.$alpha5.'{2})$';
	
        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA
        $pcexp[0] = '^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([0-9]{1}'.$alpha5.'{2})$';
        // Expression for postcodes: ANA NAA
        $pcexp[1] =  '^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([0-9]{1}'.$alpha5.'{2})$';

        // Expression for postcodes: AANA NAA
        $pcexp[2] =  '^('.$alpha1.'{1}'.$alpha2.'[0-9]{1}'.$alpha4.')([0-9]{1}'.$alpha5.'{2})$';

        // Exception for the special postcode GIR 0AA
        $pcexp[3] =  '^(gir)(0aa)$';

        // Standard BFPO numbers
        $pcexp[4] = '^(bfpo)([0-9]{1,4})$';

        // c/o BFPO numbers
        $pcexp[5] = '^(bfpo)(c\/o[0-9]{1,3})$';
	

        // Load up the string to check, converting into lowercase and removing spaces
        $postcode = strtolower($toCheck);
        $postcode = str_replace (' ', '', $postcode);

        // Assume we are not going to find a valid postcode
        $valid = false;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $key => $regexp) {
            if (preg_match($regexp.'^',$postcode, $matches)) {

                // Load new postcode back into the form element
                $toCheck = strtoupper ($matches[1] . ' ' . $matches [2]);

                // Take account of the special BFPO c/o format
                $toCheck = preg_replace ('/C\/O/', 'c/o ', $toCheck);

                // Remember that we have found that the code is valid and break from loop
                $valid = true;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was valid
        if(!$returnValidPostcode)
            return $valid;
        else
            return $toCheck;
    }
}

?>