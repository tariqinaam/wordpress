<?php
/* 
 * set up the form fields 
 * You must use $field
 * 
 * Tips
 * provide appropriate namespacingso that metaboxes do not get confused
 */


$field = array();

$field[0] = new Smrtr_WordPressImages('page_image');
$field[0]->setTitle('Page Image')
        ->setDescription('This page image should display a thumbnail');

$field[1] = new Smrtr_Textarea('page_summary');
$field[1]->setTitle('Summary')
        ->setDescription('Provide a little description for the client to easily understand what\'s going on.');

$field[2] = new Smrtr_Text('page_tagline');
$field[2]->setValue('Mwayi Dzanjlimodzi')
        ->setTitle('Tagline');

$field[3] = new Smrtr_Year('page_year');


//Set the current value if it is a post meta
foreach($field as $key => $f){
    $field[$key]->setValue(get_post_meta($_GET['post'],$f->name,true));
}


//if its comin from another from another table, work out how you will do it.

//Display the the metabox in a printed table
echo Print_Model::WPAdmin_Metabox($field,false); 


