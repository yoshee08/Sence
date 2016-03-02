<?php 
include 'core/init.php';
include 'https://www.senceservers.net/sencesite/includes/overall/header.php';  
?>
<?php 

if(servers_count() < 1) {
	echo "<br />";
	include 'https://www.senceservers.net/sencesite/includes/overall/footer.php'; 
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
<style>

</style>

<?php
//Pagination
$page = 1;
if(isset($_GET['page'])) { $page = $_GET['page']; } 
$limitT 		= (($page * $settings['pagination']) - $settings['pagination']); 
$pagination = $settings['pagination'];
//

$rank = 1;
if($page > 1) $rank = $page * $pagination - $pagination + 1;

$order = "viplist ASC, votes DESC, status DESC";
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

$result  = mysql_query("SELECT `id`, `category_id`, `ip`, `port`, `vip`, `viplist`, `banner`, `favicon`, `name`, `country`, `votes`, `disabled`, `status`, `Players`, `MaxPlayers`, `serverVersion`, `cache_time`, `protocol`, `show_port` FROM `servers` WHERE `disabled` = '0' {$category_filter} {$country_filter} {$version_filter}{$offline_where} ORDER BY `vip` DESC, {$order} LIMIT $limitT, $pagination");
while ($server_data = mysql_fetch_array($result)) {
	$server_id = $server_data['id'];
	$info = array();

	if($server_data['banner'] != '') {

		$banner = (file_exists("banners/" . $server_data['banner'])) ? "banners/" . $server_data['banner'] : $server_data['banner'];

	} else $banner = "/includes/img/1.png";
	
		$info['status'] = $server_data['status'];
		$info['Players'] = $server_data['Players'];
		$info['MaxPlayers'] = $server_data['MaxPlayers'];
		$info['serverVersion'] = $server_data['serverVersion'];
        $info['favicon'] = $server_data['favicon']; 
		if($info['status'] == 1){ $status = 1; } else { $status = 0; }
    
if($status == 0 && $settings['show_offline_servers'] == '0') { continue; }
?>
<table class="table list <?php if($server_data['vip'] == "1") echo 'vip-server'; ?>" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td style="width:5%;text-align:center;">
				<?php if($server_data['vip'] == "1"){ ?>
				<span class="glyphicon glyphicon-star-empty"></span>
				<?php } else { ?>
				<span class="badge"><?php echo $rank; ?></span>
				<?php } ?>
			</td>
			<td style="width:55%">                          

				<a href="server.php?id=<?php echo $server_data['id']; ?>">
					<img  src="<?php echo $banner; ?>" alt="<?php echo $server_data['name']; ?>s banner"/>
				</a>

							<!--This Is Where Server-Info Was-->
                <?php echo $info['favicon'] ?>
				<?php if(logged_in() && is_admin($session_user_id)) { ?>
				<br><span class="opacity">
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
<center><?php
include "pagination.php";
?></center>			


</div>
<?php
include 'https://www.senceservers.net/sencesite/includes/overall/footer.php'; 
?>
