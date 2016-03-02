<?php 
	include 'core/init.php';
	include 'includes/overall/header.php';

if(isset($_GET['username']) == true && empty($_GET['username']) == false){
	$username = $_GET['username'];
	if(user_exists($username)){
		$user_id      = user_id_from_username($username);
		$profile_data = user_data($user_id, 'name', 'username', 'active', 'date', 'avatar');
		$servers_added = mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `servers` WHERE `user_id` = '$user_id'"), 0);
		$comments_added= mysql_result(mysql_query("SELECT COUNT(`id`) FROM `comments` WHERE `user_id` = '$user_id'"), 0);
?>
	<h2 style="text-transform: capitalize;"><?php echo $profile_data['name']; ?>'s profile</h2>

	<div class="media">
		<a class="pull-left" href="#">
			<?php if(empty($profile_data['avatar']) == false) {
				echo "<img class='media-object' src='" . $profile_data['avatar'] . "' alt='" . $profile_data['name'] . "' />";
			}
			?>
		</a>
		<div class="media-body">
			<ul style="list-style-type: none">
				<li><b>Username:</b> <?php echo $profile_data['username']; ?></li>
				<li><b>Registration Date:</b> <?php echo $profile_data['date']; ?></li>
				<li><b>Servers Added:</b> <?php echo $servers_added; ?></li>
				<li><b>Comments:</b> <?php echo $comments_added; ?></li>
				<?php if(logged_in() && is_admin($session_user_id)){ ?>
				<li>
					<a href="adm_user_settings.php?update=<?php echo $user_id; ?>">
						<span class="label label-info">
							<span class="glyphicon glyphicon-wrench icon-white"></span> Edit User
						</span>
					</a>&nbsp;
					
					<a href="adm_user_settings.php?delete=<?php echo $user_id; ?>">
						<span class="label label-danger">
							<span class="glyphicon glyphicon-remove"></span> Delete User
						</span>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	
	<br />
	
	<?php if($servers_added > 0){ ?>
	<div class="table-responsive">
		<table class="table table-bordered" style="background:white;">
			<thead>
				<tr>
					<th>Ip</th>
					<th>Status</th>
					<th>details</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result = mysql_query("SELECT `id`, `user_id`, `ip`, `vip`, `disabled` FROM `servers` WHERE `user_id` = '$user_id' ORDER BY `user_id` DESC");
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$addedBy = username_from_user_id($row['user_id']);
				$status  = $row['disabled'];
				$vip 	 = $row['vip'];
				?>
				<tr>
					<td><?php if($vip == 1) echo "<font color='#ffac1e'>" . $row['ip'] . "</font>"; else echo $row['ip'] ?></td>
					<td><?php if($status == 1) echo "Unactive server"; else echo "Active server"; ?></td>
					<td><a href="server.php?id=<?php echo $row['id']; ?>"><span class="label label-info"><i class="icon-th icon-white"></i> View</span></a></td>
				</tr>
				<?php }	?>
			</tbody>
		</table>
	</div>
	<?php } ?>
	
	<br />
	<?php if($comments_added > 0){ ?>
	<div class="table-responsive">
		<table class="table table-bordered" style="background:white;">
			<thead>
				<tr>
					<th>Latest Comments</th>
					<th>Server</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result = mysql_query("SELECT `comment`, `server_id` FROM `comments` WHERE `user_id` = '$user_id' ORDER BY `id` DESC LIMIT 10");
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$server = mysql_result(mysql_query("SELECT `ip` FROM `servers` WHERE `id` = '{$row['server_id']}'"), 0);
				?>
				<tr>
					<td><?php echo string_resize($row['comment'], 25); ?></td>
					<td><a href="server.php?id=<?php echo $row['server_id']; ?>"><?php echo $server; ?></a></td>
				</tr>
				<?php }	?>
			</tbody>
		</table>
	</div>
	<?php } ?>
		

<?php
	} else {
		echo "This user doesn\'t exist!";
	}
} else {
	header('Location: index.php');
}

	include 'includes/overall/footer.php'; 
?>