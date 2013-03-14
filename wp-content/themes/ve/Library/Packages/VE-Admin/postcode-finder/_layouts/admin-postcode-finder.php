<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */




?>

<div class="tbswp wrap">
    
    <?php
    
    //$this->getBlock('header','/admin');
    
    $tabs = array(
        'list'=>'List Agents',
        'edit-agent'=>'Edit Agent',
        'geo-analytics'=> 'Analytics');
   
    $a = new smrtrTabs($tabs,
            abs_VEadmin.'/postcode-finder/_layouts/postcode-finder'
            );
    $a->keepQueryStrings(array('smrtrTabs','page'));
   
    
    ?>
 
  <?php $a->menu(array('class'=>'nav nav-pills')); ?>
  <div class="tab-content">
    <?php $a->canvas(); ?>
  </div>
</div>