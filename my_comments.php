<?php 
	include 'core/init.php';
	include 'includes/overall/header.php'; 
	protect_page();
?>
<h2>My Comments</h2>
<?php
if(isset($_GET['delete']) && !empty($_GET['delete'])){
	$comment_id = (INT)$_GET['delete'];
	
	if(mysql_result(mysql_query("SELECT `user_id` FROM `comments` WHERE `id` = '$comment_id'"), 0) !== $_SESSION['user_id']){
		$errors[] = 'This is not your comment !';
	}

	if(empty($errors)){
		mysql_query("DELETE FROM `comments` WHERE `id` = $comment_id");
		echo output_success("<p>You just deleted a comment !");
	} elseif(!empty($errors)) {
		echo output_errors($errors);
	}	
}
$total_comments = mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `comments` WHERE `user_id` = '$session_user_id' ORDER BY `id` DESC"), 0);?>	
<p>I have a total of <?php echo $total_comments; ?> comments on SenceServers!</p>

<?php 
if($total_comments > 0){
	$result  = mysql_query("SELECT `id`, `comment`, `server_id` FROM `comments` WHERE `user_id` = '$session_user_id'");
	while ($comments_data = mysql_fetch_array($result)) {
	$server = mysql_result(mysql_query("SELECT `ip` FROM `servers` WHERE `id` = '{$comments_data['server_id']}'"), 0);
$comment = html_entity_decode($comments_data['comment']);
?>
	<table class="table table-bordered" style="background:white;">
		<tbody>
			<tr>
				<td>
					<strong>Server:</strong> <?php echo $server; ?>
					<a href="my_comments.php?delete=<?php echo $comments_data['id']; ?>">					
						<span class="label label-danger pull-right"><span class="glyphicon glyphicon-remove"></span> Delete</span>
					</a>
				</td>
			</tr>
			<tr>
				<td><?php echo $comment; ?></td>
			</tr>
		</tbody>
	</table>
<?php 
	}
}
?>

<?php include 'includes/overall/footer.php'; ?>