<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

$spotlight1_title = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight1', true);
            $spotlight1_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_thickbox1', true);
            $spotlight1_desc = get_post_meta(get_the_ID(), 'YOUR_PREFIX_description1', true);
            $spotlight1_url = get_post_meta(get_the_ID(), 'YOUR_PREFIX_spotlight1-url', true);
            $spotlight1_image1 = wp_get_attachment_image_src($spotlight1_image, 'full');
            $src = $spotlight1_image1[0];

            echo "<div style='margin-bottom:10px;'>";
            echo "<div>$spotlight1_title</div>";
            echo "<div><img src=$src /></div>";
            echo "<div>$spotlight1_desc</div>";
            echo "<div><a href=$spotlight1_url>View More</a></div>";
            echo "</div>";