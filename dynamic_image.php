<?php
//------------------------------------------------------------------------------------------------------------+
// Basic Dynamic Server Image Status addon for LGSL by MadMakz, remade by Grohs Fabian.
//------------------------------------------------------------------------------------------------------------+
// CORE

	//error_reporting(0);
	require 'core/init.php';
  
//------------------------------------------------------------------------------------------------------------+
// GET THE SERVER DETAILS AND PREPARE IT FOR DISPLAY

	$s      = (INT)$_GET['s'];
	$query  = mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `id` = '$s'");
	$result = mysql_result($query, 0);
	if ($result == 0) {
		exit("This server doesn't exist!");
	}
	$mysql_query  = "SELECT `ip`, `port`, `status`, `name`, `disabled`, `Players`, `MaxPlayers` FROM `servers` WHERE `id` = '$s'";
    $mysql_result = mysql_query($mysql_query) or die(mysql_error());
    $mysql_row    = mysql_fetch_array($mysql_result, MYSQL_ASSOC);

	if($mysql_row['disabled'] == 1){
		exit("This server is deactivated!");
	}


// CHECK IF BACKGROUND IMAGE EXISTS, IF NOT USE DEFAULT
$width 	= 468;
$height	= 60;


if(isset($_GET['top'])){
	$top = $_GET['top'];
} else { $top = "692108"; };
if(isset($_GET['bottom'])){
	$bot = $_GET['bottom'];
} else { $bot = "381007"; }

$bgimg = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/core/image/gradient_bg.php?width='.$width.'&height='.$height.'&top='.$top.'&bot='.$bot;

//------------------------------------------------------------------------------------------------------------+

$status     = ($mysql_row['status'] == '1') ? true : false;
$name		= $mysql_row['name'];
$statusText = ($status == '1') ? "online" : "offline";
$ip_port    = gethostbyname($mysql_row['ip']) . ":" . $mysql_row['port'];
$type= $_GET['type'];

//------------------------------------------------------------------------------------------------------------+
// DEFINE CREATE IMAGE FROM IMAGE SOURCE

	if($type == 'background'){
		$bgimg = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/includes/img/1.jpg';
	}
	$im = imagecreatefrompng($bgimg);

	// GAMEICON
	$im_icon_info = getimagesize("includes/img/iconmc.png");
	$im_icon = imagecreatefrompng("includes/img/iconmc.png");

	

//------------------------------------------------------------------------------------------------------------+
// TEXT COLOR & FORMATING. PLAY WITH IT!
// WE USE 2 FONTS HERE, FIRST IS FOR THE HEADING/SERVERNAME (UTF-8), SECOND IS FOR THE CONTENT SUCH AS CURRENT MAP
    
    $text_font0 = "core/image/_font/verdanab.ttf";
    $text_font1 = "core/image/_font/verdana.ttf";

    $sizeMedium = 12;
	$sizeBig = 16;
	
// TEXT COLOR    	
if(isset($_GET['font'])){
	$rgb = HexToRGB($_GET['font']);
	$text_color0 = ImageColorAllocate($im, $rgb['r'], $rgb['g'], $rgb['b']);
} else {
	$text_color0 = ImageColorAllocate($im, 255, 255, 255);
}
 
//------------------------------------------------------------------------------------------------------------+   

//------------------------------------------------------------------------------------------------------------+    

// Servername
	pretty_text_ttf($im,$sizeBig,0,40,20,$text_color0,$text_font0,strtolower($name));
// IP:Port
	pretty_text_ttf($im,$sizeMedium,0,40,45,$text_color0,$text_font1,$ip_port);
// Players
	pretty_text_ttf($im,$sizeMedium,0,360,20,$text_color0,$text_font1,$mysql_row['Players']." / ".$mysql_row['MaxPlayers']);
// status
	pretty_text_ttf($im,$sizeMedium,0,360,45,$text_color0,$text_font1,$statusText);

//Gameicon
$im_icon_width  = 16;
$im_icon_height = 16;
$im_icon_posx   = 6;
$im_icon_posy   = 6;
if($type !== 'background'){
	imagecopyresampled($im, $im_icon, $im_icon_posx, $im_icon_posy, 0, 0, $im_icon_width, $im_icon_height, $im_icon_info[0], $im_icon_info[1]);
}



// -=-=-=-=-=-=-=-=-=-=-=-BORDER!!!=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=//
	
	if(isset($_GET['border'])) { 
		$bcolor = HexToRGB($_GET['border']); 
		$bordercolors = imagecolorallocate($im, $bcolor['r'], $bcolor['g'], $bcolor['b']); //Define border color

	} else { 
		$bordercolors = imagecolorallocate($im, 0, 0, 0); //Define border color
	}
	$x2 = 0;
	$y2 = 0;
	$w2 = imagesx($im) - 1; //get width image and decrease 1px or points ?
	$h2 = imagesy($im) - 1; //get height image and decrease 1px or points ?
	imageline($im, $x2,$y2,$x2,$y2+$h2,$bordercolors); //left
	imageline($im, $x2,$y2,$x2+$w2,$y2,$bordercolors); //top
	imageline($im, $x2+$w2,$y2,$x2+$w2,$y2+$h2,$bordercolors); //right
	imageline($im, $x2,$y2+$h2,$x2+$w2,$y2+$h2,$bordercolors); //bottom
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=//



//------------------------------------------------------------------------------------------------------------+
// MAKE THE IMAGE

make_img($im);

//------------------------------------------------------------------------------------------------------------+
// CUSTOM FUNCTIONS


function pretty_text_ttf($im, $fontsize, $angle, $x, $y, $color, $font, $string, $outline = false) {
	//global $bgimg;
	//$black  = imagecolorallocate($bgimg, 0, 0, 0);

	// Black outline
	if($outline){
		imagettftext($im, $fontsize, $angle, $x - 1, $y - 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x - 1, $y, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x - 1, $y + 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x, $y - 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x, $y + 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x + 1, $y - 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x + 1, $y, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x + 1, $y + 1, $black, $font, $string);
	}

	// Your text
	imagettftext($im, $fontsize, $angle, $x, $y, $color, $font, $string);
	return $im;
}


function make_img($im){
	Header("Content-type: image/png");
	imagepng($im, null, 9);
	imagedestroy($im);
	exit;
}


?>