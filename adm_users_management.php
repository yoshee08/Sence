<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 

echo "<h2>Users Management</h2>";

if(isset($_GET['delete']) && !empty($_GET['delete'])){

	$user_id = (INT)$_GET['delete'];
	mysql_query("DELETE FROM `users` WHERE `user_id` = $user_id");
	mysql_query("DELETE FROM `servers` WHERE `user_id` = $user_id");
	
	echo output_success("The user with the id of <b>" . $user_id . "</b> and his servers, were deleted successfully!");
	
} elseif(isset($_GET['status']) && !empty($_GET['status'])) {

	$user_id = (INT)$_GET['status'];
	$status  = mysql_result(mysql_query("SELECT `active` FROM `users` WHERE `user_id` = $user_id") ,0);
	if($status == 1){
		mysql_query("UPDATE `users` SET `active` = 0 WHERE `user_id` = $user_id");
		echo output_success("The user with the id of <b>" . $user_id . "</b>, was deactivated successfully!");
	}	
	if($status == 0){
		mysql_query("UPDATE `users` SET `active` = 1 WHERE `user_id` = $user_id");
		echo output_success("The user with the id of <b>" . $user_id . "</b>, was activated successfully!");
	}
} elseif(isset($_GET['update'])) echo output_success("The user was successfully updated !");

?>

<div class="table-responsive">
<table class="table table-bordered" style="background:white;">
	<thead>
		<tr>
			<th>Username</th>
			<th>Email</th>
			<th>Name</th>
			<th>IP</th>
			<th>Reg.Date</th>
			<th>Settings</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$result = mysql_query("SELECT `user_id`, `username`, `email`, `ip`, `name`, `type`, `active`, `date` FROM `users` ORDER BY `type` DESC");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$username = $row['username'];
		$email    = $row['email'];
		$name     = $row['name'];
		$type     = $row['type'];
		$ip       = $row['ip'];
		$date     = $row['date'];
		$status   = $row['active'];
		?>
		
		<tr>
			<td><a href="profile.php?username<?php echo $username ?>"><?php echo $username ?></a><?php if($type == 1) echo "<sup>admin</sup>"; ?></td>
			<td><?php echo $email; ?></td>
			<td><?php echo $name; ?></td>
			<td><?php echo $ip; ?></td>
			<td><?php echo $date; ?></td>
			<td>
				<?php 
				echo '<a href="adm_users_management.php?status=' . $row['user_id'] . '">';
				if($status == 1) echo '<span class="label label-danger">Deactivate User</span>';
				else			 echo '<span class="label label-success">Activate User</span>';
				echo '</a>';
				?>
				&nbsp;
				
				<a href="adm_user_settings.php?update=<?php echo $row['user_id']; ?>">
					<span class="label label-info"><span class="glyphicon glyphicon-wrench"></span> Edit User</span>
				</a>
				
				&nbsp;
				<a href="adm_users_management.php?delete=<?php echo $row['user_id']; ?>">
					<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span> Delete User</span>
				</a>
			</td>
		</tr>
		
		<?php
		}
		?>
	</tbody>
</table>
</div>

<?php include 'includes/overall/footer.php'; ?>