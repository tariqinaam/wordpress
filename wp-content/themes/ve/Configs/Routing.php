<?php

/* 
 * Configure the routing
 * 
 * Map a request to a controller 
 * 
 */
return $routes = array(
    
        '/blog' => 'Blog_Controller',
        '/blog/page/[0-9]+' => 'Blog_Controller',
        '/blog/[\w][\w\d-]+' => 'Blog_Controller',
        '/apply/account' => 'Apply_Controller',
        '/apply/account/applicant' => 'ApplyAccount_Controller',
        '/apply/account/contact-information' => 'ApplyAccount_Controller',
        '/apply/account/personal-information' => 'Page_Controller',
        '/apply/account/education' => 'Page_Controller',
        '/apply/overview' => 'Page_Controller',
        '/apply/new/[0-9]{7}' => 'Page_Controller',
        '/apply/[0-9]{7}' => 'Page_Controller',
        '/apply/list' => 'Page_Controller',
        '/apply/list/mine' => 'Page_Controller',
        '/apply/list/current' => 'Page_Controller',
        '/apply/to/[0-9]{7}/[0-9]+',
        '/apply/account/[\w]+/[\w]+' => 'Page_Controller',
    

 );
