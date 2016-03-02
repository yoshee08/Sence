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

require __DIR__ . '/MinecraftPing.php';
require __DIR__ . '/MinecraftPingException.php';
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
$result  = mysql_query("SELECT * FROM `servers` WHERE `disabled` = '0'");
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
		try
        {
        $Query = new MinecraftPing($server_data['ip'], $server_data['port']);

        $data = $Query->Query();
        
        $info['MaxPlayers'] = $data['players']['max'];
        $info['Players'] = $data['players']['online'];
        $info['version'] = $data['version']['name'];
        $info['motd'] = $data['description'];
        $info['favicon'] = $data['favicon'];
        $status = 1;
        //Update the cache
		if($status == 1) {
			mysql_query("UPDATE `servers` SET `status` = '$status', `serverVersion` = '{$info['serverVersion']}', `Players` = '{$info['Players']}', `MaxPlayers` = '{$info['MaxPlayers']}', `favicon` = '{$info['favicon']}', `motd` = '{$info['motd']}', `cache_time` = unix_timestamp() WHERE `id` = '$server_id'");
		}else{
			mysql_query("UPDATE `servers` SET `status` = '$status', `Players` = '0', `cache_time` = unix_timestamp()  WHERE `id` = '$server_id'");
		}
        }
        catch( MinecraftPingException $e )
        {
        $status = 0;
        }
        finally
        {
        $Query->Close();
        }

		
	
	
	}
}      
print_r($result);
?>
