<?php

/*
 * Example usage of the form class
 */



include 'smrtrForm.php';

if ($_FILES) {
    foreach ($_FILES as $file => $array){
        $imageID = insert_attachment($file,$ID,false);
    }
    add_post_meta($ID,'uwsu_page_image',$imageID, true);
}



$options = array();
$options['1'] = 'School 1';
$options['2'] = 'School 2';
$options['3'] = 'School 3';
$options['4'] = 'School 4';

$options2['1'] = 'School 4';

//------------------------------------------------------------------------------
//start new form and add fields and their properties
$form = new smrtrForm();
$form->setMethod('post');
$form->setID('formid');

$form->setSubmit('formsubmit','This is the button');
$form->setSubmitClass('this is the submit class');
$form->setSubmitStyle(array('background-color'=>'red','color'=>'#fff'));
$form->setSubmitID('submitID');




//------------------------------------------------------------------------------
//set all the fields that are to be used in this form
$field = array();
$field[0] = new smrtrText('firstname');
$field[0]->setValue('Mwayi Dzanjlimodzi')
         ->setValidation(array('required'=>true,'maxlength'=>32))
         ->setData('validation-required','true')
         ->setStyle(array('font-size'=>'12px'));

//field 2
$field[1] = new smrtrSelect('firstname2');
$field[1]->setValue(1)
         ->setDefault('Please select this mother effing option')
         ->setOptions($options)
         ->setStyle(array('font-size'=>'12px'))
         ->setWierd_AttributeType(array('font-size'=>'12px'));


//field 2
$field[3] = new smrtrCheckbox('checkboxfield');
$field[3]->setValue(array(1,4))
         ->setStyle(array('font-size'=>'12px'))->setOptions($options)
         ->setWierd_AttributeType(array('font-size'=>'12px'));


//field 2
$field[4] = new smrtrRadio('radioField');
$field[4]->setValue(1)
         ->setStyle(array('font-size'=>'12px'))->setOptions($options)
         ->setWierd_AttributeType(array('font-size'=>'12px'));


//field 2
$field[2] = new smrtrPassword('password');
$field[2]
         ->setStyle(array('font-size'=>'12px'))
         ->setValidation(array('required'=>true))
         ->setWierd_AttributeType(array('font-size'=>'12px'));


//field 2
$field[5] = new smrtrFile('myCV');
$field[5]->setValue('password')
         ->setStyle(array('font-size'=>'12px'))
         ->setWierd_AttributeType(array('font-size'=>'12px'));

//field 2
$field[6] = new smrtrTextarea('a_longbitogtext');
$field[6]->setValue('this is the text which has html "" \'entities')
         ->setStyle(array('font-size'=>'12px'))->setClass('class1 classt claaas')
         ->setWierd_AttributeType(array('font-size'=>'12px'));

$form->addFields($field);

if($form->isSubmit()){
    echo "<h1>The form has been submitted</h1>";
    
    $form->onSubmit();
    
    if($form->hasErrors()){
        echo "<h1>this form has errors</h1>"; 
    }else{
        
        echo "<h1>this form can be fucked with</h1>"; 
    }
}else echo "<h1>The form has no been submitted</h1>";

//reconfigure
$form->setValue('firstname','Mway E. Dzanjalimodzi uohiuhiugi g');
$form->setAttributes('firstname',array('id'=>'firstname','ggg'=>'ffff'));
$form->begin();

//pre_dump($form);


//set new values
$field[0]->setValue('Mwayi E. Dzanjlimodzi');


//print this
foreach($field as $item){
    
    echo $item->getHTML();
    echo $item->getErrors();
}
$form->getSubmit();
$form->end();
?>
