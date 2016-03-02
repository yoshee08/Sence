<?php 
include 'core/init.php'; 
?>
<?php 

if(servers_count() < 1) {
	echo "<br />";
	include 'includes/overall/footer.php'; 
	die();
}
?>

<?php

$result  = mysql_query("SELECT `id`, `category_id`, `ip`, `port`, `vip`, `viplist`, `favicon`, `name`, `country`, `votes`, `disabled`, `status`, `Players`, `MaxPlayers`, `serverVersion`, `cache_time`, `protocol`, `show_port` FROM `servers` WHERE `disabled` = '0'");
while ($server_data = mysql_fetch_array($result)) {
	$server_id = $server_data['id'];
	$info = array();

	//Check if server was already queried in the last X seconds
	// if yes -> get the cache data from database
	// if no  -> recheck
	
	if($settings['disable_query'] == "1"){
		$info['status'] = $server_data['status'];
		$info['Players'] = $server_data['Players'];
		$info['MaxPlayers'] = $server_data['MaxPlayers'];
		$info['serverVersion'] = $server_data['serverVersion'];
        $info['favicon'];
		if($info['status'] == 1){ $status = 1; } else { $status = 0; }
	} else {
		$Query = new MinecraftQuery($server_data['ip'], $server_data['port']);
			$info = $Query->Query();
    	//Check status of the server
		if($Query->getStatus() !== false){ $status = 1; } else { $status = 0; }

		//Update the cache
		if($status == 1) {
			mysql_query("UPDATE `servers` SET `status` = '$status', `serverVersion` = '{$info['serverVersion']}', `Players` = '{$info['Players']}', `MaxPlayers` = '{$info['MaxPlayers']}', `cache_time` = unix_timestamp() WHERE `id` = '$server_id'");
			mysql_query("INSERT INTO players (server_id, playercount, date) VALUES ('$server_id', '{$info['Players']}',now())");
		}else{
			mysql_query("UPDATE `servers` SET `status` = '$status', `Players` = '0', `cache_time` = unix_timestamp()  WHERE `id` = '$server_id'");
			mysql_query("INSERT INTO players (server_id, playercount, date) VALUES ('$server_id', '{$info['Players']}',now())");
		}
	
	
	}
}      
print_r($result);
?>
