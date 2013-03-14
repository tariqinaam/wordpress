<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
 //youtube 
             $youtube_id = get_post_meta(get_the_ID(), 'YOUR_PREFIX_youtube-url', true);
             echo "<iframe width='560' height='315' src='http://www.youtube.com/embed/$youtube_id' frameborder='0' allowfullscreen></iframe>";
            //youtube ends here