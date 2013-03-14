<?php 

$this->getPostID();
$this->getBlock('header'); ?>
<div class="content content-page">
    <div id="body-content">
        <p><?php echo $this->post_content?></p>
    </div>
</div>
<?php $this->getBlock('footer'); ?>

