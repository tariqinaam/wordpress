<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
 $competition_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_competition-image', true);
            $competition_url = get_post_meta(get_the_ID(), 'YOUR_PREFIX_competition-url', true);
            $competition_image1 = wp_get_attachment_image_src($competition_image, 'full');
            $src = $competition_image1[0];

            echo "<div style='margin-bottom:10px;'>";
            echo "<div>competition</div>";
            echo "<div><img src=$src /></div>";
            echo "<div><a href=$competition_url>View More</a></div>";
            echo "</div>";