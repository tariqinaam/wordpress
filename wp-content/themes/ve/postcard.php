<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php 
global $wpdb; 
            $images = get_post_meta(get_the_ID(), 'YOUR_PREFIX_postcard-image', false);
            $images = implode(',', $images);
// Re-arrange images with 'menu_order'
            $images = $wpdb->get_col("
    SELECT ID FROM {$wpdb->posts}
    WHERE post_type = 'attachment'
    AND ID in ({$images})
    ORDER BY menu_order ASC
");
            foreach ($images as $att) {
                // Get image's source based on size, can be 'thumbnail', 'medium', 'large', 'full' or registed post thumbnails sizes
                $src = wp_get_attachment_image_src($att, 'full');
                $src = $src[0];
                // Show image
                echo "<img src='{$src}' />";
            }
            