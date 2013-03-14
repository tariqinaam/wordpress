<?php

/* 
 * Configure commonly used paths
 * 
 * 
 */
define('url_jquery','http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');


//host
define('url_host',   get_bloginfo('url'));
define('url_temp',   get_bloginfo('template_directory'));
define('rel_temp',   str_replace(url_host,'',url_temp));

//lib root
define('abs_inc',    abs_temp . '/Library');
define('url_inc',    url_temp . '/Library');
define('rel_inc',    rel_temp . '/Library');


//packages root
define('abs_packages', abs_temp . '/Library/Packages');
define('url_packages', url_temp . '/Library/Packages');
define('rel_packages', rel_temp . '/Library/Packages');


//asset root
define('abs_assets', abs_temp . '/Assets');
define('url_assets', url_temp . '/Assets');
define('rel_assets', rel_temp . '/Assets');

//block root
define('abs_blocks', abs_temp . '/Blocks');
define('url_blocks', url_temp . '/Blocks');
define('rel_blocks', rel_temp . '/Blocks');


//other
define('abs_img',    abs_assets.'/img');
define('abs_design', abs_assets.'/img/design');
define('abs_content',abs_assets.'/img/content');
define('abs_js',     abs_assets.'/js');
define('abs_css',    abs_assets.'/css');


define('url_img',    url_assets.'/img');
define('url_design', url_assets.'/img/design');
define('url_content',url_assets.'/img/content');
define('url_js',     url_assets.'/js');
define('url_css',    url_assets.'/css');


define('rel_img',    rel_assets.'/img');
define('rel_design', rel_assets.'/img/design');
define('rel_content',rel_assets.'/img/content');
define('rel_js',     rel_assets.'/js');
define('rel_css',    rel_assets.'/css');


define('ENCRPTION_KEY', 'oaserfedfyrtg4rggosafrtg54gfd7803rt5rgt348r');

?>