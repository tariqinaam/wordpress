<div class="tbswp wrap">
    
    <?php
    
    $this->getBlock('header','/admin');
    
    $tabs = array(
        'list'=>'List Subscribers',
        'download'=>'Download Subscribers',);
   
    $a = new smrtrTabs($tabs,
            abs_packages.'/subscribers/_layouts/subscribers'
            );
    $a->keepQueryStrings(array('smrtrTabs','page'));
   
    
    ?>
 
  <?php $a->menu(array('class'=>'nav nav-pills')); ?>
  <div class="tab-content">
    <?php $a->canvas(); ?>
  </div>
</div>