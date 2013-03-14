<?php
/**
 * Load up wordpress interfaces
 * These are helper classes that make programming in wordpress more bearable.
 * 
 * 
 * @section Postmeta
 * Postmeta allows us to render post forms within the wordpress back end
 * 
 */

define('abs_wpinterfaces',abs_packages.'/Wordpress_Interfaces');

require_once abs_packages.'/Wordpress_Interfaces/Meta/Interface.php';

new Metaboxes_Interface();
