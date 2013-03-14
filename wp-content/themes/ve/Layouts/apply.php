<?php 

pre_dump($this);
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $this->post_title; ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo rel_css; ?>/bootstrap.css" />
    <?php wp_head(); //allow wordpress to hook into header block ?>
</head>

<body>

<div>

    <div>
    