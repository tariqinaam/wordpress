<?php
/*
 *IBRO Applications
 */

define('abs_subscribers_path',abs_packages.'/subscribers/');

require_once(abs_subscribers_path.'/config.php');
autoLoadDirectory(abs_subscribers_path.'/_inc');

//get the admin pages loaded
new Ibro_Subscribers_Admin;
new Ibro_CSV_Handlers_Subscribers;


?>