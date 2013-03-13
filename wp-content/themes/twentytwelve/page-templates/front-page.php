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
get_header();
?>
<?php ?>
<div id="primary" class="site-content">
    <div id="content" role="main">
<?php get_template_part('content', 'page'); ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (has_post_thumbnail()) : ?>
                <div class="entry-page-image">
                    <?php the_post_thumbnail(); ?>
                </div><!-- .entry-page-image -->
            <?php endif; ?>
            
                <?php
            //youtube 
             $youtube_id = get_post_meta(get_the_ID(), 'YOUR_PREFIX_youtube-url', true);
             echo "<iframe width='560' height='315' src='http://www.youtube.com/embed/$youtube_id' frameborder='0' allowfullscreen></iframe>";
            //youtube ends here
            
            //reason to visit agent
            $reason1_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text1', true);
            $reason2_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text2', true);
            $reason3_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text3', true);
            $reason4_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text4', true);
            $reason5_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text5', true);


           // vdump($reason1_text);
            
            echo "<div style='margin-bottom:10px;'>";
            echo "<div><b>Reason to visit Travel Agents</b></div>";
            foreach ($reason1_text as $key => $value) {
                 echo "<div>$value</div>";
            }
          
            echo "<div>$reason2_text</div>";
            echo "<div>$reason3_text</div>";
            echo "<div>$reason4_text</div>";
            echo "<div>$reason5_text</div>";
            echo "</div>";
            //reason to visit agent ends here.
            //competition spotlight

            $competition_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_competition-image', true);
            $competition_url = get_post_meta(get_the_ID(), 'YOUR_PREFIX_competition-url', true);
            $competition_image1 = wp_get_attachment_image_src($competition_image, 'full');
            $src = $competition_image1[0];

            echo "<div style='margin-bottom:10px;'>";
            echo "<div>competition</div>";
            echo "<div><img src=$src /></div>";
            echo "<div><a href=$competition_url>View More</a></div>";
            echo "</div>";
            //competition spotlight ends here
            //feature spotlight1 
            $spotlight1_title = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight1', true);
            $spotlight1_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_thickbox1', true);
            $spotlight1_url = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight1-url', true);
            $spotlight1_image1 = wp_get_attachment_image_src($spotlight1_image, 'full');
            $src = $spotlight1_image1[0];

            echo "<div style='margin-bottom:10px;'>";
            echo "<div>$spotlight1_title</div>";
            echo "<div><img src=$src /></div>";
            echo "<div><a href=$spotlight1_url>View More</a></div>";
            echo "</div>";
            //feature spotlight1 ends here
            //feature spotlight 2 
            $spotlight2_title = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight2', true);
            $spotlight2_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_thickbox2', true);
            $spotlight2_url = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight2-url', true);
            $spotlight2_image1 = wp_get_attachment_image_src($spotlight2_image, 'full');
            $src = $spotlight2_image1[0];

            echo "<div style='margin-bottom:10px;'>";
            echo "<div>$spotlight2_title</div>";
            echo "<div><img src=$src /></div>";
            echo "<div><a href=$spotlight2_url>View More</a></div>";
            echo "</div>";
            //feature spotlight 2 ends here.
            // code for postcard block start here
            $postcard_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_postcard-image', true);
            $postcard_url = wp_get_attachment_image_src($postcard_image, 'full');
            $src = $postcard_url[0];
            // Show image
            echo "<div style='margin-bottom:10px;'>";
            echo "<div>postcard</div>";
            echo "<div><img src='$src' /></div>";
            echo "</div>";


            //postcard block ends here
            // }
            ?>
            

        <?php endwhile; // end of the loop.  ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar('front'); ?>
<?php get_footer(); ?>