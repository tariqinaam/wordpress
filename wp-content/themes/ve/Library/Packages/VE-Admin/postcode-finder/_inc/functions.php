<?php

class VE_PostcodeFinder extends Add_Admin_Pages{
    
    public function __construct()
    {
        
        ///set layout 
        $this->setLayoutLocation(abs_packages.'/VE-Admin/postcode-finder/_layouts');
        $this->setBlockLocation(abs_packages.'/VE-Admin/postcode-finder/_blocks');
        //add the necessary pages... stupid wordpress
        
        //about page
        $this->addPage('Postcode Finder','postcode-finder','administrator');
        $this->setAdminPage($this->getCurrentPage());
        //make sure the class spits out on right hook
	add_action( 'admin_menu', array( &$this, 'createPages' ) );
        return $this;
    }
}






class Ibro_Table_Entries extends smtrWp_Table_Core{
    
    function column_default( $item, $col )
    {
	switch ( $col ) {
           
            
	    case 'id':
                 $url = smrtrURL::args(
                        array('agentId'=>$item->$col,
                              'smrtrTabs'=>'edit-agent')
                    );
                
                return sprintf('<a href="%s">Edit</a>',$url);
                break;
            
           
            case 'created':
            case 'modified':
                return date('d M Y',dt2ts($item->$col));
                break;
            case 'urn':
                return sprintf('<a href="/wp-admin/admin.php?page=application-entries&smrtrTabs=view&entryURN=%s&step=appbase">%s</a>',$item->$col,$item->$col);
            case 'model':
                return sprintf('<a href="/wp-admin/admin.php?page=application-applications&applicationID=%s&smrtrTabs=edit">%s</a>',$item->appURN,$item->$col);
                break;
	    default:
		return ucwords($item->$col);
	}
    }
}