<?php

class Lists_Model {
    public $server;

    public function __construct() {

    }
    
    public function __call($method,$attr){
        
        
        //this validation does not exist
    }
    
    
    
    static public function item($id){
        global  $wpdb;
        
        $sql = sprintf('
            select itemValue from smrtr_lists
            where itemID=%d ',$id);
        $res = $wpdb->get_var($sql);
        
        return $res;
    }
    
    
    static public function regions(){
        
        
         global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','regions');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        

        return $item;
    }
    
    
    static public function grantTypes(){
        
        global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','grantTypes');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        return $item;
    }
    
    
    static public function educationStatus(){
        
        global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','educationStatus');
        $res = $wpdb->get_results($sql);
        
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        return $item;
    }
    
    
    
    static public function boolean(){
        
        
        global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','boolean');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
           
            $item[$val->itemID] = preg_replace('/[^(\x20-\x7F)]*/','',$val->itemValue);
            
        }
        
        return $item;
    }
    
    static public function nationalities(){
        
        
        global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','nationalities');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = preg_replace('/[^(\x20-\x7F)]*/','',$val->itemValue);
            
        }
        
        return $item;
    }
    
    static public function languages(){
        
        global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','languages');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        
        return $item;
    }
    
    static public function genders(){
        
         global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','genders');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        
        
        return $item;
    }
    
    static public function countries(){
        
         global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" order by itemValue ASC ','countries');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        
        
        
        return $item;

    }
    
    static public function namePrefixes(){
        
                
         global  $wpdb;
        $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','namePrefixes');
        $res = $wpdb->get_results($sql);
        //$wpdb->show_errors();
        //$wpdb->print_error();
        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        
        
        return $item;

    }
    static public function ibroApproval(){
        
                
        global  $wpdb;
         $sql = sprintf('
            select itemID,itemValue from smrtr_lists l
            left join smrtr_lists_taxonomy t on 
           t.taxID=l.taxID where taxSlug="%s" ','ibroApproval');
        $res = $wpdb->get_results($sql);

        
        $item = array();
        foreach($res as $val){
            $item[$val->itemID] = $val->itemValue;
            
        }
        
        
        return $item;

    }
    
    static public function scoreCriteria(){
        $criteria = array(
        'A'=>'Scientific credentials',
        'B'=>'Level of academic degreee',
        'C'=>'Justification by applicant',
        'D'=>'Quality of abstract or research proposal',
        'E'=>'Quality of conference or host lab',
        'F'=>'Letters of reference',
        'G'=>'Publications (if applicable)',
        'H'=>'Benefit for the applicant'
        );
        
        return $criteria;
    }
    
   
    
    static public function rank(){
        
        $scores =  array(10,20,30);
        $ret    = array();

        foreach($scores as $score){


            switch($score){

                case 10:
                $ret[$score] = "$score (clearly acceptable)";
                break;

          
                case 30:
                $ret[$score] = "$score (clearly not acceptable)";
                break;

                default:
                $ret[$score] = "$score";
                break;

            }
        }

        return $ret;

    }
    
    static public function scores(){
        
        $scores =  range(1,10);
        $ret    = array();

        foreach($scores as $score){


            switch($score){

                case 1:
                $ret[$score] = "$score (Very weak)";
                break;

                case 5:
                $ret[$score] = "$score (Average)";
                break;

                case 10:
                $ret[$score] = "$score (Excellent)";
                break;

                default:
                $ret[$score] = "$score";
                break;

            }
        }

        return $ret;

    }
    
    static function postTypes($remove=array()){
        
        $default = array('attachment','revision','nav_menu_item');
        $remove = count($remove)==0?$default:array_merge($remove,$default);

        return array_diff(get_post_types(),$remove);
    }
    
    

}

?>
