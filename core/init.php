<?php
ob_start();
session_start();
//error_reporting(0);


require 'database/connect.php';
require 'functions/general.php';
require 'functions/users.php';
require 'functions/servers.php';
require 'query/MinecraftQuery.php';

if(logged_in() == true){
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'name', 'username', 'password', 'email', 'type', 'avatar');
	if(user_active($user_data['username']) == false){
		session_destroy();
		header('Location: home.php');
		exit();
	}
	
	mysql_query("UPDATE `users` SET `last_activity` = unix_timestamp() WHERE `user_id` = '$session_user_id'");
}
if(logged_in() == false){
	$guest_ip = @$_SERVER['REMOTE_ADDR'];
	$query = mysql_query("SELECT COUNT(`ip`) FROM `guests` WHERE `ip` = '$guest_ip'");
	if(mysql_result($query, 0) == 1){
		mysql_query("UPDATE `guests` SET `last_activity` = unix_timestamp() WHERE `ip` = '$guest_ip'");
	} else {
		mysql_query("INSERT INTO `guests` (`ip`, `last_activity`) VALUES ('$guest_ip', unix_timestamp())");
	}
		mysql_query("DELETE FROM `guests` WHERE `last_activity` < unix_timestamp() - 30");
}

$settings = settings_data(1, 'title', 'description', 'url', 'news_message', 'facebook', 'twitter', 'google', 'contact_email', 'pagination', 'register', 'show_offline_servers', 'disable_query', 'max_categories', 'server_cache', 'email_confirmation', 'server_confirmation', 'advertise_top', 'advertise_bottom', 'recaptcha_public', 'recaptcha_private');
require 'functions/titles.php';

$errors = array();
?>
