<?php
function formatMOTD($motd){
	$search  = array('§0', '§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§a', 'Â§b', '§c', '§d', '§e', '§f', '§l', '§m', '§n', '§o', '§k', '§r');
	$replace = array('<font color="#000000">', '<font color="#0000AA">', '<font color="#00AA00">', '<font color="#00AAAA">', '<font color="#aa00aa">', '<font color="#ffaa00">', '<font color="#aaaaaa">', '<font color="#555555">', '<font color="#5555ff">', '<font color="#55ff55">', '<font color="#55ffff">', '<font color="#ff5555">', '<font color="#ff55ff">', '<font color="#ffff55">', '<font color="#ffffff">', '<font color="#000000">', '<b>', '<u>', '<i>', '<font color="#000000">', '<font color="#000000">');
	$motd  = str_replace($search, $replace, $motd);
	
	return $motd;
}

function get_categories($server_id) {
	$categories = json_decode(mysql_result(mysql_query("SELECT `category_id` FROM `servers` WHERE `id` = $server_id"), 0));
	
	if(is_array($categories)){
		foreach($categories as $category_id){
			$category = @mysql_result(mysql_query("SELECT `name` FROM `categories` WHERE `category_id` = $category_id"), 0);
			echo "<a href='index.php?category=" . $category . "'><span class='label label-default'>" . $category . "</span></a>&nbsp;";
		}
	} else {
		$category = @mysql_result(mysql_query("SELECT `name` FROM `categories` WHERE `category_id` = $categories"), 0);
		echo "<a href='index.php?category=" . $category . "'><span class='label label-default'>" . $category . "</span></a>";
	}
}

function server_exists2($ip, $port) {
	$ip = sanitize($ip);
	$query = mysql_query("SELECT COUNT(`ip`) FROM `servers` WHERE `ip` = '$ip' AND `port` = '$port'");
	return (mysql_result($query, 0) == 1) ? true : false;
}
function server_exists($ip) {
	$ip = sanitize($ip);
	$query = mysql_query("SELECT COUNT(`ip`) FROM `servers` WHERE `ip` = '$ip'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function get_country($ip) {
	if(!is_numeric($ip)){
		$ip = gethostbyname($ip);
	}
	$current_dir    = explode("/" ,$_SERVER['REQUEST_URI']);
	$current_dir    = array_slice($current_dir, 0, -1);
	$current_dir    = implode("/", $current_dir);
	$link 			= "http://api.wipmania.com/" . $ip;
	@$country 		= (file_get_contents($link)) ? file_get_contents($link) : "XX";
	//$icon   		= "http://" . $_SERVER['SERVER_NAME'] . $current_dir . "/includes/locations/" . $country . ".png";
	return $country ;
}

/* function country_icon_location($ip) {
	if(!is_numeric($ip)){
		$ip = gethostbyname($ip);
	}
	$ctx=stream_context_create(array('http'=>
		array('timeout' => 2)
	));
	
	$current_dir    = explode("/" ,$_SERVER['REQUEST_URI']);
	$current_dir    = array_slice($current_dir, 0, -1);
	$current_dir    = implode("/", $current_dir);
	$link 			= "http://api.ipinfodb.com/v3/ip-city/?key=abe0dc606dec2fbf07c3a3d08103995a1d01f31afb171118b39b87c10b89cf6f&ip=" . $ip;
	$file	 		= file_get_contents($link,false,$ctx);
	$exploded		= explode(";", $file);
	$country 		= $exploded[3];
	$icon   		= "http://" . $_SERVER['SERVER_NAME'] . $current_dir . "/includes/locations/" . $country . ".png";
	return "<img src=\"" . $icon . "\" />";
	// print_r($current_dir);
} */

function server_vip($server_id) {
	$server_id = (INT)$server_id;
	return (mysql_result(mysql_query("SELECT `vip` FROM `servers` WHERE `id` = $server_id"), 0) == 1) ? 1 : 0;
}
function servers_count() {
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `servers` WHERE `disabled` = 0"), 0);
}
function disabled_servers_count() {
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `servers` WHERE `disabled` = 1"), 0);
}

function HexToRGB($hex) {
		$hex = str_replace("#", "", $hex);
		$color = array();
 
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}
		return $color;
}
	
	
function id_to_user_id($id) {
	$id = sanitize($id);
	$query 	= mysql_query("SELECT `user_id` FROM `servers` WHERE `id` = '$id'");
	$data = mysql_fetch_assoc($query);
	return $data['user_id'];
}


?>