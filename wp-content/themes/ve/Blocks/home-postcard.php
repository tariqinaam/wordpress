<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
 $postcard_image = get_post_meta(get_the_ID(), 'YOUR_PREFIX_postcard-image', true);
            $postcard_url = wp_get_attachment_image_src($postcard_image, 'full');
            $src = $postcard_url[0];
            // Show image
            echo "<div style='margin-bottom:10px;'>";
            echo "<div>postcard</div>";
            echo "<div><img src='$src' /></div>";
            echo "</div>";

