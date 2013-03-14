<?php
function ptag( $atts, $content = null ) 
{
   return '<p>'. do_shortcode($content) . '</p>';
}

/*
function shortcode_bold($atts, $content) 
{
   return "<b>$content</b>";
}
add_shortcode( 'b', 'shortcode_bold' );
*/


function shortcode_strong($atts, $content) 
{
   return "<strong>$content</strong>";
}
        
function shortcode_em($atts, $content) 
{
   return "<em>$content</em>";
}

?>