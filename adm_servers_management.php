<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 



if(isset($_GET['delete']) && !empty($_GET['delete'])){
	
	$server_id = (INT)$_GET['delete'];
	mysql_query("DELETE FROM `servers` WHERE `id` = $server_id");
	mysql_query("DELETE FROM `comments` WHERE `server_id` = $server_id");
	header('Location: adm_servers_management.php?successDelete');
	
} elseif(isset($_GET['status']) && !empty($_GET['status'])) {

	$server_id = (INT)$_GET['status'];
	$status    = mysql_result(mysql_query("SELECT `disabled` FROM `servers` WHERE `id` = $server_id") ,0);
	if($status == 0){
		mysql_query("UPDATE `servers` SET `disabled` = 1 WHERE `id` = $server_id");
		header('Location: adm_servers_management.php?successUpdate');
	}	
	if($status == 1){
		mysql_query("UPDATE `servers` SET `disabled` = 0 WHERE `id` = $server_id");
		header('Location: adm_servers_management.php?successUpdate');
	}
	
} elseif(isset($_GET['vip']) && !empty($_GET['vip'])) { 

	$server_id = (INT)$_GET['vip'];
	$vip       = mysql_result(mysql_query("SELECT `vip` FROM `servers` WHERE `id` = $server_id") ,0);
	if($vip == 0){
		mysql_query("UPDATE `servers` SET `vip` = 1 WHERE `id` = $server_id");
		header('Location: adm_servers_management.php?successUpdate');
	}	
	if($vip == 1){
		mysql_query("UPDATE `servers` SET `vip` = 0 WHERE `id` = $server_id");
		header('Location: adm_servers_management.php?successUpdate');
	}
	
} elseif(isset($_GET['vip']) && !empty($_GET['vip'])) { 

	$server_id = (INT)$_GET['vip'];
	$vip       = mysql_result(mysql_query("SELECT `vip` FROM `servers` WHERE `id` = $server_id") ,0);
	if($vip == 0){
		mysql_query("UPDATE `servers` SET `vip` = 1 WHERE `id` = $server_id");
		header('Location: adm_servers_management.php?successUpdate');
	}	
	if($vip == 1){
		mysql_query("UPDATE `servers` SET `vip` = 0 WHERE `id` = $server_id");
		header('Location: adm_servers_management.php?successUpdate');
	}
	
} else {

echo "<h2>Servers Management</h2>";

if(isset($_GET['successUpdate'])) echo output_success("Successfully updated!");
if(isset($_GET['successDelete'])) echo output_success("Server was successfully deleted!");

?>

<div class="table-responsive">
	<table class="table table-bordered" style="background:#f2f2f2;">
		<thead>
			<tr>
				<th>Name</th>
				<th>Ip : Port</th>
				<th>Status (0=offline; 1=Online)</th>
				<th>VIP</th>
                	        <th>Added By</th>
				<th>Settings</th>

			</tr>
		</thead>
		<tbody>
			<?php
			$result = mysql_query("SELECT `id`, `user_id`, `ip`, `port`, `vip`, `status`, `disabled`, `name` FROM `servers` ORDER BY `user_id` DESC");
			if($result === FALSE) { 
             die(mysql_error()); // TODO: better error handling
}
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$addedBy = username_from_user_id($row['user_id']);
			$status  = $row['disabled'];
			$vip 	 = $row['vip'];
			?>
			
			<tr>
				<td><a href="server.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $row['ip'] . " : " . $row['port']; ?></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo $row['vip']; ?></td>
				<td><a href="profile.php?username=<?php echo $addedBy; ?>"><?php echo $addedBy; ?></a></td>
				<td>
					<?php 
					echo '<a href="adm_servers_management.php?status=' . $row['id'] . '">';
					if($status == 0) echo '<span class="label label-danger">Deactivate Server</span>';
					else			 echo '<span class="label label-success">Activate Server</span>';
					echo '</a>';
					?>
					&nbsp;
					<?php 
					echo '<a href="adm_servers_management.php?vip=' . $row['id'] . '">';
					
					if($vip == 0) echo '<span class="label label-warning"><span class="glyphicon glyphicon-star-empty "></span> Make VIP</span>';
					else		  echo '<span class="label label-warning"><span class="glyphicon glyphicon-star"></span> Remove VIP</span>';
					echo '</a>';
					?>
					
					&nbsp;
					<a href="adm_comments_management.php?id=<?php echo $row['id']; ?>">
						<span class="label label-info"><span class="glyphicon glyphicon-globe"></span> Get Comments</span>
					</a>
					
					&nbsp;
					<a href="adm_servers_management.php?delete=<?php echo $row['id']; ?>">
						<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span> Delete</span>
					</a>
				</td>
			</tr>
			
			<?php
			}
			?>
		</tbody>
	</table>
</div>

<?php
}
include 'includes/overall/footer.php'; 
?>