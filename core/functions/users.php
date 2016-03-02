<?php
function c_hash($username, $password){
	$salt = "fc160cd93ccd8b3bdb8e38c1dbe76f2a7c0371c46741b5a98d0b6074b192b9983195148abf077a5b802d803ec16217474476ef335303cb73a0583d3243d84213";
	$hash = hash('sha512', $password . $salt . $username);
	for($i=1;$i<=1000;$i++){
		$hash = hash('sha512', $hash);
	}
	return $hash;
}
function send_avatar($file_temp, $file_extension) {
	global $file_path;
	$file_path = 'avatars/' . substr(md5(time()), 0, 10) . '.' . $file_extension;
	move_uploaded_file($file_temp, $file_path);
}
function online_users() {
	$online_users = mysql_num_rows(mysql_query("SELECT `user_id` FROM `users` WHERE `last_activity` > unix_timestamp() - 30"));//in seconds
	echo $online_users;
}
function online_guests() {
	$online_users = mysql_num_rows(mysql_query("SELECT `ip` FROM `guests` WHERE `last_activity` > unix_timestamp() - 30"));//in seconds
	echo $online_users;
}
function update_user($user_id, $update_data) {
	$update = array();
	array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data) {
		$update[] = '`' . $field . '` = \'' . $data .'\'';
	}
		
	mysql_query("UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` = $user_id ");
}

function is_admin($user_id) {
	$user_id = (INT)$user_id;
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = $user_id AND `type` = 1"), 0) == 1) ? true : false;
	return ($user_data['type'] == 1) ? true : false;
}

function activate($email, $email_code) {
	$email		= mysql_real_escape_string($email);
	$email_code = mysql_real_escape_string($email_code);
	
	if(mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0"), 0) == 1) {
		mysql_query("UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
		return true;
	} else {
		return false;
	}
}

function change_password($user_id, $username, $password) {
	$user_id = (int)$user_id;
	$password = c_hash($username, $password);
	
	mysql_query("UPDATE `users` SET `password` = '$password' WHERE `user_id` = $user_id");
}

function register_user($register_data) {
	global $settings;
	
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = c_hash($register_data['username'], $register_data['password']);
	$active = $register_data['active'];
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
	if($active == '0'){
		sendmail($register_data['email'], 'Activate your account', "
			Hello " . $register_data['name'] . ",\n\n
			To activate your account, access the link below:\n\n
			" . $settings['url'] . "activate.php?email=" . $register_data['email'] . "&email_code=" . $register_data['email_code'] . " \n\n
		");
	}
}
function user_count() {
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1"), 0);
}
function disabled_users_count() {
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 0"), 0);
}

function user_data($user_id) {
	$data = array();
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if($func_num_args > 0) {
		unset($func_get_args[0]);
		$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = '$user_id'"));
		
		return $data;
	}
}

function logged_in() {
	return (isset($_SESSION['user_id'])) ? true : false;
}

function email_exists($email) {
	$email = sanitize($email);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function user_exists($username) {
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function user_active($username) {
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active` ='1'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function user_id_from_username($username) {
	$username = sanitize($username);
	$query = mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username'");
	return mysql_result($query, 0, 'user_id');
}
function username_from_user_id($user_id) {
	$username = sanitize($user_id);
	$query = mysql_query("SELECT `username` FROM `users` WHERE `user_id` = '$user_id'");
	return @mysql_result($query, 0, 'username');
}
function login($username, $password) {
	$user_id = user_id_from_username($username);
	$username = sanitize($username);
	$password = c_hash($username, $password);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `password` = '$password'");
	return (mysql_result($query, 0) == 1) ? $user_id : false;
}
?>