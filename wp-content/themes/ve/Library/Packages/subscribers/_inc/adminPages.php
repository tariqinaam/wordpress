<?php

class Ibro_Subscribers_Admin extends Add_Admin_Pages{
    
    public function __construct()
    {
        
        ///set layout 
        $this->setLayoutLocation(abs_packages.'/subscribers/_layouts');
        $this->setBlockLocation(abs_packages.'/subscribers/_blocks');
        //add the necessary pages... stupid wordpress
        
        //about page
        $this->addPage('Subscribers','subscribers','administrator');
        $this->setAdminPage($this->getCurrentPage());
        //make sure the class spits out on right hook
	add_action( 'admin_menu', array( &$this, 'createPages' ) );
        return $this;
    }
}

?>