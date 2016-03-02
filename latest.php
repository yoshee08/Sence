<?php 
include 'core/init.php';
include 'includes/overall/header.php'; 
?>

<?php 

if(servers_count() < 1) {
	echo "<br />";
	include 'includes/overall/footer.php'; 
	die();
}
?>
<script>
function copyToClipboard(text) {
    window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
}
 
// Use JQuery
//
$('#ipport').click(function() {
    copyToClipboard('<?php echo strtolower($server_data['ip']); if($server_data['port'] != "25565" & $server_data['show_port'] != "no") echo ":".$server_data['port']; ?>')
});
</script>
<div align="right">

</div>				
		<div>
			<div class="pull-left" style="display:inline;">
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; View by <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="latest.php?sort=players">Players</a></li>
						<li><a href="latest.php?sort=status">Status</a></li>
						<li><a href="latest.php">Votes</a></li>
					</ul>
				</div>
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; Version <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<?php 
						$result = mysql_query("SELECT DISTINCT `serverVersion` FROM `servers`");
						while($row = mysql_fetch_array($result)){
							echo "<li><a href='latest.php?version=" . $row['serverVersion'] . "'>" . $row['serverVersion'] . "</a></li>";
						}
						?>
					</ul>
				</div>
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; Country <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<?php 
						$result = mysql_query("SELECT DISTINCT `country` FROM `servers`");
						while($row = mysql_fetch_array($result)){
							echo "<li><a href='latest.php?country=" . $row['country'] . "'>" . $row['country'] . "</a></li>";
						}
						?>
					</ul>
				</div>
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; Category <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<?php 
						$result = mysql_query("SELECT DISTINCT `name` FROM `categories`");
						while($row = mysql_fetch_array($result)){
							echo "<li><a href='latest.php?category=" . $row['name'] . "'>" . $row['name'] . "</a></li>";
						}
						?>
					</ul>
				</div>
		</div>
		<br>
		<br>
<style>
th {color:white;}
</style>
<hr />
<?php

$rank = 1;
if($page > 1) $rank = $page * $pagination - $pagination + 1;

$order = "status DESC, votes DESC";
if(isset($_GET['sort'])){
	switch($_GET['sort']){
		case "players" : $order = "`Players` DESC"; break;
		case "status"  : $order = "`status` DESC";  break;
	}
}

$offline_where = $category_filter = $version_filter = $country_filter = NULL;
if($settings['show_offline_servers'] == '0') $offline_where = "AND `status` = 1"; 
	
if(isset($_GET['version'])){
	$_GET['version'] = sanitize($_GET['version']);
	$version_filter = "AND `serverVersion` = '" . $_GET['version'] . "'";
}
if(isset($_GET['category'])){
	$_GET['category'] = sanitize($_GET['category']);
	$category_id = mysql_result(mysql_query("SELECT `category_id` FROM `categories` WHERE `name` = '{$_GET['category']}'"),0);
	$category_filter = "AND `category_id` LIKE '%{$category_id}%'";
}
if(isset($_GET['country'])){
	$_GET['country'] = sanitize($_GET['country']);
	$country_filter = "AND `country` = '" . $_GET['country'] . "'";
}

$result  = mysql_query("SELECT `id`, `category_id`, `ip`, `port`, `vip`, `viplist`, `banner`, `name`, `country`, `votes`, `disabled`, `status`, `Players`, `MaxPlayers`, `serverVersion`, `cache_time`, `protocol`, `show_port` FROM `servers` WHERE `disabled` = '0' {$category_filter} {$country_filter} {$version_filter}{$offline_where} ORDER BY `id` DESC, {$order} LIMIT 0,20");
while ($server_data = mysql_fetch_array($result)) {
	$server_id = $server_data['id'];
	$info = array();

	if($server_data['banner'] != '') {

		$banner = (file_exists("banners/" . $server_data['banner'])) ? "banners/" . $server_data['banner'] : $server_data['banner'];

	} else $banner = "includes/img/1.png";
	

		$info['status'] = $server_data['status'];
		$info['Players'] = $server_data['Players'];
		$info['MaxPlayers'] = $server_data['MaxPlayers'];
		$info['serverVersion'] = $server_data['serverVersion'];
		if($info['status'] == 1){ $status = 1; } else { $status = 0; }
?>
<table class="table list" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td style="width:5%;text-align:center;">
				<span class="badge"><?php echo $rank; ?></span>
			</td>
			<td style="width:55%">
                                                        

				<a href="server.php?id=<?php echo $server_data['id']; ?>">
					<img src="<?php echo $banner; ?>" alt="<?php echo $server_data['name']; ?>s banner"/>
				</a>

							<!--This Is Where Server-Info Was-->

				<?php if(logged_in() && is_admin($session_user_id)) { ?>
				<span class="opacity">
				<a href="adm_servers_management.php?vip=<?php echo $server_id; ?>">
					<span class="label label-info">
						<span class="glyphicon glyphicon-star"></span> VIP
					</span>
				</a>&nbsp;
				
				<a href="adm_servers_management.php?status=<?php echo $server_id; ?>">
					<span class="label label-danger">
						<span class="glyphicon glyphicon-off "></span> Disable
					</span>
				</a>&nbsp;
				
				<a href="adm_servers_management.php?delete=<?php echo $server_id; ?>">
					<span class="label label-danger">
						<span class="glyphicon glyphicon-remove"></span> Delete
					</span>
				</a>
				</span>	
				<?php } ?>

			</td>
			<td style="width:20%;height:20%;">
				<table class="table-reset">
					<tr>
						<th style="color:#333333">Players&nbsp;</th>
						<td><span class="label label-success"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php if($status == 1){echo $info['Players'] . "/" . $info['MaxPlayers']; } else { echo "0"; } ?></span></span><br></td>
					</tr>
					<tr>

						<th style="color:#333333"><br>Votes</th>
						<td><br><span class="label label-info"><span class="glyphicon glyphicon-check">&nbsp;<?php echo $server_data['votes']; ?></span></span></td>
					</tr>
				</table>
			</td>
                      </tr>
			<tr>
			<td><div style="margin-left: 55px;padding-bottom: 5px;">
				<?php get_categories($server_id); ?>
			</div>
			</td> 
		</tr>	
	</tbody>
<span class="server-info">&nbsp;<?php echo '<a style="color:#fff;" href="server.php?id=' . $server_data['id'] . '">' . $server_data['name'] . '</a>';	?></span>
</table>
<span class="server-info">&nbsp;<li class="glyphicon glyphicon-globe"></li>&nbsp;&nbsp;<input type="text" class="copyip" onclick="this.select(); copyToClipboard(this.value);" value="<?php echo strtolower($server_data['ip']); if($server_data['port'] != "25565" & $server_data['show_port'] != "no") echo ":".$server_data['port']; ?>" readonly="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($status == 1) echo "<span class='online label-success'>Online</span>"; else echo "<span class='offline label-warning'>Offline</span>"; ?></span>
<br />
<?php $rank++;} ?>
</div>
</div>

<?php
include 'includes/overall/footer.php'; 
?>
