<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 



if(isset($_GET['delete']) && !empty($_GET['delete'])){
	
	$comment_id = (INT)$_GET['delete'];
	mysql_query("DELETE FROM `comments` WHERE `id` = '$comment_id'");
	header('Location: adm_comments_management.php?successDelete');
	
} else {

echo "<h2>Comments Management </h2>";
if(isset($_GET['successDelete'])) echo output_success("Comment was successfully deleted!");
?>
<p>Click on a server to get the full list of comments of that server.</p>
<div class="table-responsive">
<table class="table table-bordered" style="background:white;">
	<thead>
		<tr>
			<th>Comment</th>
			<th>From</th>
			<th>Server</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if(!isset($_GET['id'])){
			$result = mysql_query("SELECT * FROM `comments` ORDER BY `id` DESC LIMIT 50");
		} else {
			$result = mysql_query("SELECT * FROM `comments` WHERE `id` = {$_GET['id']} ORDER BY `id` DESC");
		}
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from = username_from_user_id($row['user_id']);
			$server = mysql_result(mysql_query("SELECT `ip` FROM `servers` WHERE `id` = '{$row['server_id']}'"), 0)
		?>
		<tr>
			<td><?php echo string_resize($row['comment'], 25); ?></td>
			<td><a href="profile.php?id=<?php echo $from; ?>"><?php echo $from; ?></a></td>
			<td><a href="adm_comments_management.php?id=<?php echo $row['id']; ?>"><?php echo $server; ?></a></td>
			<td>
				<a href="adm_comments_management.php?delete=<?php echo $row['id']; ?>">
					<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span> Delete</span>
				</a>
			</td>
		</tr>
		<?php }	?>
	</tbody>
</table>
</div>
<?php } include 'includes/overall/footer.php'; ?>