<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

define('abs_VEadmin',abs_packages.'/VE-Admin/');
autoLoadDirectory(abs_packages.'/VE-Admin/postcode-finder/_inc/');


new VE_PostcodeFinder;

