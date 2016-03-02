<?php
include_once 'inc/init.php';

    /* Set Path to Font File */
$font_path = 'style/Squarea.ttf';

   /* Check if the GET variables are not empty */
if(empty($_GET['ip']) || empty($_GET['port']) || empty($_GET['name']) || empty($_GET['bstyle']) || empty($_GET['p'])) {
    header('Location: '.$baseurl.'');
	 exit; 
}

	/* Define _GET variables */
if (isset($_GET['ip'])) {
$serverip = filter_var($_GET['ip'], FILTER_SANITIZE_STRING);
}
if (isset($_GET['port'])) {
$serverport = (int) $_GET['port'];
}
if (isset($_GET['name'])) {
$servername = filter_var($_GET['name'], FILTER_SANITIZE_STRING);
}
if (isset($_GET['bstyle'])) {
$bstyle = filter_var($_GET['bstyle'], FILTER_SANITIZE_STRING);
}
if (isset($_GET['p'])) {
$p = filter_var($_GET['p'], FILTER_SANITIZE_STRING);
} else {
$p = "yes";
}


    /* Bind server ip and port */
	$status = new MinecraftServerStatus();
	$response = $status->getStatus($serverip, '1.7.*', $serverport);
	
    /* Check if server online, if not then show offline */
if(!$response) {
    /* Set the Content Type */
      header('Content-type: image/jpeg');
    /* Create Image From Existing File */
      $jpg_image = imagecreatefromjpeg('bg/'.$bstyle.'.jpg');
	  
    /* Create some colors */
	  
      $white = imagecolorallocate($jpg_image, 255, 255, 255);
      $grey = imagecolorallocate($jpg_image, 128, 128, 128);
      $black = imagecolorallocate($jpg_image, 0, 0, 0);
      $craft = imagecolorallocate($jpg_image, 247, 144, 0);
      $red = imagecolorallocate($jpg_image, 255, 16, 16);
	  $ye = imagecolorallocate($jpg_image, 255,255,0);
    /* Print Text On Image */
	  imagettftext($jpg_image, 20, 0, 65, 31, $black, $font_path, $servername);
      imagettftext($jpg_image, 20, 0, 65, 30, $craft, $font_path, $servername);
	  imagettftext($jpg_image, 14, 0, 65, 50, $black, $font_path, "".$serverip.":".$serverport."");
	  imagettftext($jpg_image, 14, 0, 65, 51, $white, $font_path, "".$serverip.":".$serverport."");
    /* if Show Players = yes */
	  if ($p == "yes") {
	  imagettftext($jpg_image, 14, 0, 357, 52, $black, $font_path, "Offline");
	  imagettftext($jpg_image, 14, 0, 357, 51, $white, $font_path, "Offline");
	  }


    /* Send Image to Browser. */
	  imagejpeg(  $jpg_image);

    /* Clear Memor. */
      imagedestroy($jpg_image);
	  
    /* Server online. */
	} else {
	

    /* Set the Content Type */
      header('Content-type: image/jpeg');
    /* Create Image From Existing File */
      $jpg_image = imagecreatefromjpeg('bg/'.$bstyle.'.jpg');
	  
    /* Create some colors */
      $white = imagecolorallocate($jpg_image, 255, 255, 255);
      $grey = imagecolorallocate($jpg_image, 128, 128, 128);
      $black = imagecolorallocate($jpg_image, 0, 0, 0);
      $craft = imagecolorallocate($jpg_image, 247, 144, 0);
      $red = imagecolorallocate($jpg_image, 255, 16, 16);
	  $ye = imagecolorallocate($jpg_image, 255,255,0);
    /* Print Text On Image */
	  imagettftext($jpg_image, 20, 0, 65, 31, $black, $font_path, $servername);
      imagettftext($jpg_image, 20, 0, 65, 30, $craft, $font_path, $servername);
	  imagettftext($jpg_image, 14, 0, 65, 50, $black, $font_path, "".$serverip.":".$serverport."");
	  imagettftext($jpg_image, 14, 0, 65, 51, $white, $font_path, "".$serverip.":".$serverport."");
    /* if Show Players = yes */
	  if ($p == "yes") {
	  imagettftext($jpg_image, 14, 0, 357, 52, $black, $font_path, "Online: ".$response['players']."");
	  imagettftext($jpg_image, 14, 0, 357, 51, $white, $font_path, "Online: ".$response['players']."");
	  }
    /* Send Image to Browser */
	  imagejpeg(  $jpg_image);


    /* Clear Memory */
      imagedestroy($jpg_image);
  
}

?>