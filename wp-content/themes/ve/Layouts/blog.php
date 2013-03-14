<?php 

/*
 * This is an example of a listing page that is specifically designed to display
 * blog posts
 */

$this->getBlock('header');

echo $this->post_title;
echo 'This is a blog post. We are going to list somethings in here.';

$this->getBlock('listing');

$this->getBlock('footer');
