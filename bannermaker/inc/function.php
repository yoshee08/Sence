<?php
//hex color to rgb
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)): // check if input string is a valid hex colour code
        if(strlen($hex) == 3) { // three letters code
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else { // six letters coode
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
        return implode(",", array($r, $g, $b));         // returns the rgb values separated by commas, ready for usage in a rgba( rr,gg,bb,aa ) CSS rule
        // return array($r, $g, $b); // alternatively, return the code as an array
    else: return "";  // input string is not a valid hex color code - return a blank value; this can be changed to return a default colour code for example
    endif;
} 
	
    
