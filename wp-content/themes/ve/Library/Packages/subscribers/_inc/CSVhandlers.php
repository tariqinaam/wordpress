<?php 

/**
 * @author Mwayi.smrtr
 */
class Ibro_CSV_Handlers_Subscribers extends smrtrCSV{
    

    public function csvHandle_subscribers(){

        global $wpdb;
        

        $sql  = sprintf('select email from subscribers %s %s',$this->sql->ordering,$this->sql->limit);
        $rows = $wpdb->get_results($sql, ARRAY_A );

       
        if($rows){
            $head = array_keys($rows[0]);
            $rows = array_merge(array($head),$rows);
            
            smrtrCSV::serve($this->filename,$rows);
        }
    }
    
}

?>