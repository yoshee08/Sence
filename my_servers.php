<?php 
	include 'core/init.php';
	include 'includes/overall/header.php'; 
	protect_page();
?>
<h2 style="text-transform: capitalize;"><?php echo $user_data['name']; ?>'s Servers</h2>
<?php
if(isset($_GET['successUpdate'])){
	echo output_success("Successfully updated!");
}
if(isset($_GET['delete']) && !empty($_GET['delete'])){
	
	$server_id = (INT)$_GET['delete'];
	
	if(id_to_user_id($server_id) !== $_SESSION['user_id']){
		$errors[] = 'You don\'t own this server, sorry!';
	}

	if(empty($errors)){
		mysql_query("DELETE FROM `servers` WHERE `id` = $server_id");
		mysql_query("DELETE FROM `comments` WHERE `server_id` = $server_id");
		echo output_success("<p>The server with the id of <b>" . $server_id . "</b>, was deleted successfully!");
	} elseif(!empty($errors)) {
		echo output_errors($errors);
	}
	
} else {
$disabled = mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `servers` WHERE `user_id` = ". $_SESSION['user_id'] . " AND `disabled` = 1"), 0);
$active   = mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `servers` WHERE `user_id` = ". $_SESSION['user_id'] . " AND `disabled` = 0"), 0);
?>	

<p>You have a total of <?php echo $active; ?> active servers and <?php echo $disabled; ?> disabled servers!</p>
<?php
	if($active > 0){
?>
<div class="table-responsive">
	<table class="table table-bordered" style="background:white;">
		<thead>
			<tr>
				<th>Status</th>
				<th>Name</th>
				<th>IP</th>
				<th>Votifier</th>
				<th>Extras</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$result  = mysql_query("SELECT * FROM `servers` WHERE `disabled` = '0' AND `user_id` = '$session_user_id'");
			while ($server_data = mysql_fetch_array($result)) {
			$server_id = $server_data['id'];
			$info = array();
			
			$info['status'] = $server_data['status'];
			$info['Players'] = $server_data['Players'];
			$info['MaxPlayers'] = $server_data['MaxPlayers'];
			$info['serverVersion'] = $server_data['serverVersion'];
			if($info['status'] == 1){ $status = 1; } else { $status = 0; }
			?>
			<tr>
				<td>
					<?php 
					if($status == 1) echo '<span class="label label-success"><span class="glyphicon glyphicon-ok"></span></span>';
					else echo '<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span></span>';
					
					if(server_vip($server_data['id']) == 1)	echo '&nbsp;<span class="label label-info"><span class="glyphicon glyphicon-star"></span></span>'; 
					?>
				</td>
				<td>
					<?php echo '<a href="server.php?id=' . $server_data['id'] . '">' . $server_data['name'] . '</a>';	?>
				</td>
				<td><?php echo $server_data['ip'] . ":" . $server_data['port']; ?></td>
				<td><?php if($server_data['votifier_key'] == 'false') $Votifier = "No"; else $Votifier = "Yes"; echo $Votifier;?></td>
				<td>
					<!--<a href="customize_server.php?id=<?php echo $server_id; ?>"><span class="label label-default"><span class="glyphicon glyphicon-film"></span> Get banners</span></a>&nbsp;
					<a href="changeserver.php?id=<?php echo $server_data['id']; ?>"><span class="label label-info"><span class="glyphicon glyphicon-wrench">Edit Server</span> Edit</span></a>&nbsp;
					<a href="my_servers.php?delete=<?php echo $server_id; ?>"><span class="label label-danger"><span class="glyphicon glyphicon-remove">Delete Server</span> Delete</span></a>
				    -->
					<div id="extras" align="center"> 
					<!--<a href="bannermaker?id=<?php echo $server_id; ?>"><span class="glyphicon glyphicon-film">&nbsp;Banners</a>&nbsp;-->
					<a href="changeserver.php?id=<?php echo $server_data['id']; ?>"><span class="glyphicon glyphicon-wrench">Edit</a>&nbsp;
					<a href="my_servers.php?delete=<?php echo $server_id; ?>"><span class="glyphicon glyphicon-remove">Delete</a>
				    </div>
				</td>
			</tr>
				<?php }	?>
		</tbody>
	</table>
</div>
<?php
	}
}
include 'includes/overall/footer.php'; 
?>