<?php
class Ibro_Table_Subscribers extends smtrWp_Table_Core{
    
    function column_default( $item, $col )
    {
        
	switch ( $col ) {
           
	    default:
                return $item->$col;
	}
    }
   
}


?>