<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

//pre_dump($this);exit;
//get_header();
$this->getBlock('header');

?>
<?php ?>
<div id="primary" class="site-content">
    <div id="content" role="main">
<?php //get_template_part('content', 'page'); ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (has_post_thumbnail()) : ?>
                <div class="entry-page-image">
                    <?php //the_post_thumbnail(); ?>
                </div><!-- .entry-page-image -->
            <?php endif; ?>
            
                <?php
           $this->getBlock('youtube');
           
           $this->getBlock('postcode-widget');
            
          $this->getBlock('reason-to-visit');
            //competition spotlight

           $this->getBlock('competition-spotlight');
            //competition spotlight ends here
           
            //experience finder
           $this->getBlock('experience-finder');
           
            //feature spotlight1 
            $this->getBlock('home-spotlight1');
            //feature spotlight1 ends here
            //feature spotlight 2 
            $this->getBlock('home-spotlight2');
            //feature spotlight 2 ends here.
            // code for postcard block start here
           $this->getBlock('home-postcard');
            //postcard block ends here
            // }
            endwhile; // end of the loop.  ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php// get_sidebar('front'); ?>
<?php $this->getBlock('footer');?>