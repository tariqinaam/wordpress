<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
$spotlight2_title = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight2', true);
            $spotlight2_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_thickbox2', true);
            $spotlight2_desc = get_post_meta(get_the_ID(), 'YOUR_PREFIX_description2', true);
            $spotlight2_url = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight2-url', true);
            $spotlight2_image1 = wp_get_attachment_image_src($spotlight2_image, 'full');
            $src = $spotlight2_image1[0];

            echo "<div style='margin-bottom:10px;'>";
            echo "<div>$spotlight2_title</div>";
            echo "<div><img src=$src /></div>";
             echo "<div>$spotlight2_desc</div>";
            echo "<div><a href=$spotlight2_url>View More</a></div>";
            echo "</div>";