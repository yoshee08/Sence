<?php 
include 'core/init.php';
include 'includes/overall/header.php'; 
include 'core/functions/recaptchalib.php';

$server_id  = (INT)$_GET['id'];
$user_id	= id_to_user_id($server_id);
$addedBy 	= username_from_user_id($user_id);

if(empty($_GET['id']) == true || $addedBy == false){echo "<h2>Server not found.</h2>";include 'includes/overall/footer.php';die();}

$result  = mysql_query("SELECT * FROM `servers` WHERE `id` = '$server_id'");

$server_data = mysql_fetch_array($result, MYSQL_ASSOC);

$last_update = time() - $server_data['cache_time'];
$last_updateM = intval($last_update/60);

if($server_data['banner'] != '') {
	$banner = (file_exists("banners/" . $server_data['banner'])) ? $settings['url'] . "banners/" . $server_data['banner'] : $server_data['banner'];
} else $banner = $settings['url'] . "includes/img/1.png";
$server_url = $settings['url'] . 'server.php?id=' . $server_data['id'];



//Stafffffff
$staff_result  = mysql_query("SELECT * FROM `servers_staff` WHERE `serverID` = '$server_id'") or die(mysql_error());

$info = array();

	//Check if server was already queried in the last X seconds
	// if yes -> get the cache data from database
	// if no  -> recheck
	
	if($server_data['cache_time'] > time() - $settings['server_cache']){
		$info['status'] = $server_data['status'];
		$info['Players'] = $server_data['Players'];
		$info['MaxPlayers'] = $server_data['MaxPlayers'];
		$info['serverVersion'] = $server_data['serverVersion'];
		$info['favicon'] = $info['HostName'] = false;
		if($info['status'] == 1){ $status = 1; } else { $status = 0; }
	} else {
		//Server checks
		$Query = new MinecraftQuery($server_data['ip'], $server_data['port']);
		
		if($server_data['protocol'] == 1){
			$info = $Query->QueryNew();
		} else {
			$info = $Query->QueryOld();
		}
		
		//Check status of the server
		if($info !== false){ $status = 1;} else { $status = 0; }
		
		//$info['HostName'] = preg_replace('/\xA7[0-9A-FK-OR]/i', '', $info['HostName']);

		//Update the cache
		if($status == 1) {
			mysql_query("UPDATE `servers` SET `status` = '$status', `serverVersion` = '{$info['serverVersion']}', `Players` = '{$info['Players']}', `MaxPlayers` = '{$info['MaxPlayers']}', `cache_time` = unix_timestamp() WHERE `id` = '$server_id'");
		}else{
			mysql_query("UPDATE `servers` SET `status` = '$status', `Players` = '0', `cache_time` = unix_timestamp()  WHERE `id` = '$server_id'");
		}
	}
$description = html_entity_decode($server_data['description']);

?>

	
<div style="margin: auto;" class="panel panel-default">
<div class="panel-body">
<?php 
if($status == 0){
	echo '<div class="alert alert-important">';
	echo '<p style="font-size: 24px;margin-top: 7px;"><strong>' . $server_data['ip'] . '</strong> is offline</p>';
	echo '</div>';
	//die();
}?>	


<div class="alert alert-info">
	<p style="font-size: 22px;margin-top: 5px;">
	<?php if($info['favicon'] !== false) echo '<img src="' . $info['favicon'] . '" />'; ?>

		Connect to: <strong><?php echo strtolower($server_data['ip']); if($server_data['port'] != "25565" & $server_data['show_port'] != "no") echo ":".$server_data['port']; ?></strong>
               
	</p>
</div>
<div class="clearfix"></div>
<img class="pull-right" src="<?php echo $banner; ?>" />

<ul class="nav nav-pills" id="tabs" >
	<li class="active"><a href="#info"><span class="glyphicon glyphicon-signal"></span> Server Informations</a></li>
	<li><a href="#votestats"><span class="glyphicon glyphicon-thumbs-up"></span> Votes</a></li>
	<li><a href="#banners"><span class="glyphicon glyphicon-picture"></span> Banners</a></li>
	<li><a href="#staff"><span class="glyphicon glyphicon-user"></span> Server Staff</a></li>
	<li><a href="#comments"><span class="glyphicon glyphicon-user"></span> Comments</a></li>
</ul>
<br />
<div class="tab-content" >	
	<div class="tab-pane active" id="info">
		<table class="table table-bordered pull-left" style="width: 100%; background:white;">
<?php 
if($status == 0){
	echo '<td><span class="glyphicon glyphicon-time"></span> <strong>Status</strong></td>';
	echo '<td><span class="label label-danger"><span class="glyphicon glyphicon-thumbs-down glyphicon glyphicon-white"></span></span></td>';
	echo '</div>';
}
else{
	echo '<td><span class="glyphicon glyphicon-time"></span> <strong>Status</strong></td>';
	echo '<td><span class="label label-success"><span class="glyphicon glyphicon-ok glyphicon glyphicon-white"></span></span></td>';
	echo '</div>';
}?>
<!--			<tr>
				<td><span class="glyphicon glyphicon-time"></span> <strong>Status</strong></td>
				<td><span class="label label-success"><span class="glyphicon glyphicon-ok glyphicon glyphicon-white"></span></span></td>
			</tr>-->
			<tr>
				<td><span class="glyphicon glyphicon-random"></span> <strong>IP Address</strong></td>
				<td><?php echo $server_data['ip']; ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-tasks"></span> <strong>Port</strong></td>
				<td><?php echo $server_data['port']; ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-tasks"></span> <strong>MOTD</strong></td>
				<td><?php echo $info['HostName']; ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-user"></span> <strong>Online Players</strong></td>
				<td><?php echo $info['Players'] . "/" . $info['MaxPlayers']; ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-wrench"></span> <strong>Server Version</strong></td>
				<td><?php echo $info['serverVersion']; ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-globe"></span> <strong>Country</strong></td>
				<td><img src="includes/locations/<?php echo $server_data['country']; ?>.png" title="<?php echo $server_data['country']; ?>" alt="country"/> <?php echo $server_data['country']; ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-user"></span> <strong>Added by</strong></td>
				<td><a href="profile.php?username=<?php echo $addedBy; ?>"><?php echo $addedBy; ?></a></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-cog"></span> <strong>Category</strong></td>
				<td><?php get_categories($server_id); ?></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-eye-open"></span> <strong>Website</strong></td>
				<td><a href="<?php echo $server_data['website']; ?>"><?php echo $server_data['website']; ?></a></td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-refresh"></span> <strong>Last update</strong></td>
				<td><?php echo $last_updateM; ?> minutes ago</td>
			</tr>
			<tr>
				<td><span class="glyphicon glyphicon-check"></span> <strong>Votes</strong></td>
				<td>
					<div id="votes" style="display:inline;"><?php echo $server_data['votes']; ?></div> 
					<a href="vote.php?id=<?php echo $server_data['id']; ?>"><button class="btn btn-success">Vote</button></a>
				</td>
			</tr>
		</table>
		</br>
		<?php if(!empty($description)){ ?>
			<table class="table table-bordered pull-right" style="width: 55%; background:white;">
				<tr><td><strong>Description</strong></td></tr>
				<tr>
					<td style="padding: 0px 20px 0px 20px;"><?php echo $description; ?></td>
				</tr>
			</table>
		<?php } ?>
		
		<?php if(!empty($server_data['youtube_id'])){ ?>
			<table class="table table-bordered pull-right" style="background:white;">
				<tr><td><strong>Video Presentation</strong></td></tr>
				<tr>
					<td style="text-align:center;"><iframe width="800" height="400" src="//www.youtube.com/embed/<?php echo $server_data['youtube_id']; ?>" frameborder="0" allowfullscreen></iframe></td>
				</tr>
			</table>
		<?php } ?>
		
	</div>
	<div class="tab-pane" id="votestats">
		<table class="table table-condensed" style="table-layout:fixed;">
			<thead>
				<tr>
					<th>Username</th>
					<th>Year / Month / Day</th>
					<th>Hour / Minute / Second</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(mysql_result(mysql_query("SELECT COUNT(`ip`) FROM `votes` WHERE `server_id` = '$server_id'"), 0) > 0){
						$voteResult  = mysql_query("SELECT * FROM `votes` WHERE `server_id` = '$server_id' ORDER BY `timestamp` DESC LIMIT 50");
						while($votes_data = mysql_fetch_array($voteResult, MYSQL_ASSOC)){
						$ip = explode(".", $votes_data['ip']);
						$ip[3] = "***";$ip[2] = "***";
						$ip = implode(".", $ip);
						echo "
							<tr>
								<td>" . $votes_data['mcuser'] . "</td>
								<td>" . date('Y.m.d', $votes_data['timestamp']) . "</td>
								<td>" . date('h:i:s', $votes_data['timestamp']) . "</td>
							</tr>";
						}
					} else {
						echo "<tr><td>Currently there are no votes!</td><td></td><td></td></tr>";
					}
				?>
			</tbody>
		</table>	
	</div>
	
	
	
	<div class="tab-pane" id="banners">
		<?php if($server_data['banner'] !== ""){	?>
		<img src="<?php echo $banner; ?>" />
		<h4>BB/HTML Code </h4>
		<textarea class="form-control" id="bb_small_code" rows="3" style="width: 95%;">[url=<?php echo $server_url; ?>][img]<?php echo $banner; ?>[/img][/url]</textarea>
		<br /><textarea class="form-control" id="html_small_code" rows="3" style="width: 95%;"><a href="<?php echo $server_url; ?>"><img src="<?php echo $banner; ?>"></a></textarea>
		<?php } ?>	
		<button id="button" class="btn btn-default" onclick="divPic();hide();">Get dynamic banner</button>
		<div id="live"></div>
		<br />
		<h4>BB/HTML Code </h4>
		<textarea class="form-control" id="bb_small_code" rows="3" style="width: 95%;">[url=<?php echo $server_url; ?>][img]<?php echo $settings['url']; ?>dynamic_image.php?s=<?php echo $id; ?>&type=background[/img][/url]</textarea>
		<br /><textarea class="form-control" id="html_small_code" rows="3" style="width: 95%;"><a href="<?php echo $settings['url']; ?>server.php?id=<?php echo $id; ?>"><img src="<?php echo $settings['url']; ?>dynamic_image.php?s=<?php echo $id; ?>&type=background"></a></textarea>
	</div>



	<div class="tab-pane" id="staff">
		<h3><?php echo $server_data['name'] ?>'s Staff Team</h3>
		<br>
		<div class="row">
		<?php
		while($row = mysql_fetch_array($staff_result, MYSQL_ASSOC)){
			echo'<div class="col-md-3">';
			echo'<center><img style="text-align: center;" class="img-square" src="'. "https://crafatar.com/avatars/" . $row['player'] . '"></center>';
			echo'<strong><p style="text-align: center;">' . $row['player'] . '</p></strong>';
			echo'<p style="text-align: center;">' . $row['rank'] . '</p>';
			echo'</div>';
		}
		?>
		</div></div></div>
<h3><p class="pull-right">Comments (<?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `comments` WHERE `server_id` = '$server_id'"), 0);?>) <?php if(logged_in() == true){ ?><span class="label label-success" style="cursor:pointer;" onClick="$('#comments').toggle('slow');">Add Comment</span><?php } ?></h3>
<br>
</p>
<br>
<?php
if(empty($_POST) == false){
	//captcha
        if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
          echo '<h2>Please check the the captcha form.</h2>';
          exit;
        }
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LescA0TAAAAAIT9Rr6y7shwzZrVmHsZKGw7GPH9&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        if($response.success==false)
        {
          echo '<h2>You have incorrectly solved the captcha.</h2>';
        }else
        {
          echo '';
        }
	
	if(strlen($_POST['comment']) > 254){
		$errors[] = 'Comment too long, maximum 255 characters!';
	}
	
	if(strlen(trim($_POST['comment'])) < 10){
		$errors[] = 'Comment too short, minimum 10 characters!';
	}
	
	if(empty($errors) == true){
		$comment	= htmlspecialchars($_POST['comment'], ENT_QUOTES);
		mysql_query("INSERT INTO `comments` (`server_id`, `user_id`, `comment`) VALUES ('$server_id', '$session_user_id', '$comment')");
	}else{
		echo output_errors($errors);
	}
}
?>

<?php
$query = mysql_query("SELECT * FROM `comments` WHERE `server_id` = '$server_id'");
while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
$comment_user_id  = $row['user_id'];
@$comment_added_by = mysql_result(mysql_query("SELECT `username` FROM `users` WHERE `user_id` = '$comment_user_id'"), 0) ? mysql_result(mysql_query("SELECT `username` FROM `users` WHERE `user_id` = '$comment_user_id'"), 0) : "Unknown User";
$comment = html_entity_decode($row['comment']);
?>
<table class="table table-bordered pull-right" style="width:55%; background:white;">
	<tr>
		<td>
			<h4><strong>Comment by:</strong> <?php echo $comment_added_by; ?></h4>
<br>
<br>
			<?php if(logged_in() == true && is_admin($session_user_id)){ ?>
			<div class="pull-right">
				<a href="server.php?id=<?php echo $server_id; ?>&delete=<?php echo $row['id']; ?>">
					<span class="label label-important"><span class="glyphicon glyphicon-remove glyphicon glyphicon-white"></span> Delete </span>
				</a>
			</div>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td><?php echo $comment; ?></td>
	</tr>
</table>
<?php } ?>

<br /><br />

<?php if(logged_in() == true){ ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<form action="" class="pull-right" method="post" id="comments" style="display:none;" role="form">
	<pre><textarea style="width:100%;height:100px;" name="comment" class="form-control"></textarea><br /></pre>
	
	<div class="g-recaptcha" data-sitekey="6LescA0TAAAAALyZm3e3tRVV07pRAmifbOmjadix"></div><br />

	<input type="submit" class="btn btn-primary" value="Add comment" />
</form>
<br>
<br>
<br>
<br>
<?php } ?>

<?php
if(empty($_GET['delete']) == false && logged_in() && is_admin($session_user_id)){
	$comment_id = (INT)$_GET['delete'];
	mysql_query("DELETE FROM `comments` WHERE `id` = '$comment_id'");
	header('Location: server.php?id=' . $server_id);
}
?>
</div>
</div>		
	</div>
<br>


<script>
$('#tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
 $(function () {
	$("[rel='tooltip']").tooltip();
});
 function divPic() {
	document.getElementById('live').innerHTML = "<img src=\"dynamic_image.php?s=<?php echo $server_id; ?>&type=background\" />";
	$("#button").hide('slow');
}
</script>

<?php include 'includes/overall/footer.php'; ?>