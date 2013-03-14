<?php

/**
 * Copyright (c) 2008, David R. Nadeau, NadeauSoftware.com.
 * All rights reserved.
 */

function strip_html_tags( $text )
{
    $text = preg_replace(
            array(
                    // Remove invisible content
                    '@<head[^>]*?>.*?</head>@siu',
                    '@<style[^>]*?>.*?</style>@siu',
                    '@<script[^>]*?.*?</script>@siu',
                    '@<object[^>]*?.*?</object>@siu',
                    '@<embed[^>]*?.*?</embed>@siu',
                    '@<applet[^>]*?.*?</applet>@siu',
                    '@<noframes[^>]*?.*?</noframes>@siu',
                    '@<noscript[^>]*?.*?</noscript>@siu',
                    '@<noembed[^>]*?.*?</noembed>@siu',
                    /*'@<input[^>]*?>@siu',*/
                    '@<form[^>]*?.*?</form>@siu',

                    // Add line breaks before & after blocks
                    '@<((br)|(hr))>@iu',
                    '@</?((address)|(blockquote)|(center)|(del))@iu',
                    '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                    '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                    '@</?((table)|(th)|(td)|(caption))@iu',
                    '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                    '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                    '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                    " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", 
                    " ", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                    "\n\$0", "\n\$0",
            ),
            $text );

    // remove empty lines
    $text = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $text);
    // remove leading spaces
    $text = preg_replace("/\n( )*/", "\n", $text);

    // Remove all remaining tags and comments and return.
    return strip_tags( $text );
}


function place_quotes($a){

    return sprintf('"%s"',$a);
}

function summarise_excerpt($old_excerpt, $limit)
{
       $excerpt_summarised = strtok($old_excerpt, " ");
       while($excerpt_summarised)
       {
           $text .= " $excerpt_summarised";
           $words++;
           if(($words >= $limit) && ((substr($excerpt_summarised, -1) == "!")||(substr($excerpt_summarised, -1) == ".")))
               break;
           $excerpt_summarised = strtok(" ");
       }
       return ltrim($text);
    }

function shorten_text($string,$max_size)
{
    $new_str = $string;
    $len = strlen($string);
    if($len>$max_size)
    {
        $new_str = substr($string,0,$max_size-3)."<small>...</small>";
    }
    return $new_str;
}


function t2live_code($to)
{

   $today = mktime ( date("H"), date("i") , date("s"),date("n") ,date("j") , date("Y")  );

   return ($to) + $today;

}


?>