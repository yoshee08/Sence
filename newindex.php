<?php 
include 'core/init.php';
include 'SenceNew/includes/overall/header.php'; 
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
<?php if(!empty($settings['news_message'])) { ?>
<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>News:</strong> <?php echo htmlspecialchars_decode($settings['news_message']); ?>
</div>
<?php } ?>
<br>				
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<div class="panel panel-default">
	<div class="panel-body">
		<div>
			<div class="pull-left" style="display:inline;">
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; View by <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="index.php?sort=players">Players</a></li>
						<li><a href="index.php?sort=status">Status</a></li>
						<li><a href="index.php">Votes</a></li>
					</ul>
				</div>
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; Version <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<?php 
						$result = mysql_query("SELECT DISTINCT `serverVersion` FROM `servers`");
						while($row = mysql_fetch_array($result)){
							echo "<li><a href='index.php?version=" . $row['serverVersion'] . "'>" . $row['serverVersion'] . "</a></li>";
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
							echo "<li><a href='index.php?country=" . $row['country'] . "'>" . $row['country'] . "</a></li>";
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
							echo "<li><a href='index.php?category=" . $row['name'] . "'>" . $row['name'] . "</a></li>";
						}
						?>
					</ul>
				</div>
				<div class="" style="display:inline;">
					<a class="btn btn-default btn-small" href="latest.php">&nbsp; Latest &nbsp;</a>
				</div>
			</div>
				<?php
				if($settings['facebook'] !== 'false') echo '<div style="float:right;"><div class="fb-like" data-href="https://senceservers.net/" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div></div>';
                                if($settings['google'] !== 'false') echo '<div style="float:right;"><div class="g-plusone" data-annotation="inline" data-width="15" data-href="https://senceservers.net/"></div></div>';
				if($settings['twitter'] !== 'false') echo '<div style="float:right;"><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://senceservers.net" data-count="none" data-hashtags="SenceServers">Tweet</a></div>';

				?>
             		</div>
                <br>
<style>
th {color:white;}
</style>
<br>
<hr />

<?php
//Pagination
$page = 1;
if(isset($_GET['page'])) { $page = $_GET['page']; } 
$limitT 		= (($page * $settings['pagination']) - $settings['pagination']); 
$pagination = $settings['pagination'];
//

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

$result  = mysql_query("SELECT * FROM `servers` WHERE `disabled` = '0' {$category_filter} {$country_filter} {$version_filter}{$offline_where} ORDER BY `vip` DESC, {$order} LIMIT $limitT, $pagination");
while ($server_data = mysql_fetch_array($result)) {
	$server_id = $server_data['id'];
	$info = array();

	if($server_data['banner'] != '') {

		$banner = (file_exists("banners/" . $server_data['banner'])) ? "banners/" . $server_data['banner'] : $server_data['banner'];

	} else $banner = "includes/img/1.png";

	//Check if server was already queried in the last X seconds
	// if yes -> get the cache data from database
	// if no  -> recheck
	
	if($server_data['cache_time'] > time() - $settings['server_cache'] || $settings['disable_query'] == "1"){
		$info['status'] = $server_data['status'];
		$info['Players'] = $server_data['Players'];
		$info['MaxPlayers'] = $server_data['MaxPlayers'];
		$info['serverVersion'] = $server_data['serverVersion'];
		if($info['status'] == 1){ $status = 1; } else { $status = 0; }
	} else {
		$Query = new MinecraftQuery($server_data['ip'], $server_data['port']);

		if($server_data['protocol'] == 1){
			$info = $Query->QueryNew();
		} else {
			$info = $Query->QueryOld();
		}

		//Check status of the server
		if($Query->getStatus() !== false){ $status = 1; } else { $status = 0; }

		//Update the cache
		if($status == 1) {
			mysql_query("UPDATE `servers` SET `status` = '$status', `serverVersion` = '{$info['serverVersion']}', `Players` = '{$info['Players']}', `MaxPlayers` = '{$info['MaxPlayers']}', `cache_time` = unix_timestamp() WHERE `id` = '$server_id'");
		}else{
			mysql_query("UPDATE `servers` SET `status` = '$status', `Players` = '0', `cache_time` = unix_timestamp()  WHERE `id` = '$server_id'");
		}
	
	
	}
if($status == 0 && $settings['show_offline_servers'] == '0') { continue; }
?>
<style>
/* Gray Scale */
.hover08 figure img {
	-webkit-filter: grayscale(100%);
	filter: grayscale(100%);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.hover08 figure:hover img {
	-webkit-filter: grayscale(0);
	filter: grayscale(0);
}
h2 span {
	margin-left: 1em;
	color: #aaa;
	font-size: 85%;
}
.column {
	margin: 15px 15px 0;
	padding: 0;
}
.column:last-child {
	padding-bottom: 60px;  
        padding-top: 60px;
}
.column::after {
	content: '';
	clear: both;
	display: block;
}
.column div {
	position: relative;
	float: left;
	width: 468px;
	height: 60px;
	margin: 0 0 0 0px;
	padding: 0;
}
.column div:first-child {
	margin-left: 0;
}
.column div span {
	position: absolute;
	bottom: -20px;
	left: 0;
	z-index: -1;
	display: block;
	width: 468px;
	margin: 0;
	padding: 0;
	color: black;
	font-size: 18px;
	text-decoration: none;
	text-align: center;
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
	opacity: 0;
}
figure {
	width: 468px;
	height: 60px;
	margin: 0 0 50% 50%;
	padding: 0;
	background: #fff;
	/*overflow: hidden;*/
}
figure:hover+span {
	bottom: -36px;
	opacity: 1;
}
server-info {
    background: #0099CC;
    width: 468px;
    padding: 2px 5px;
    display: block;
    color: white;
}
</style>

<div class="hover08">
		<div>
			<figure><img src="<?php echo $banner; ?>" alt="<?php echo $server_data['name']; ?>s banner" width="468" height="60"/>
			<span><?php echo $server_data['name']; ?></span></figure>
			<br>
			<br>
			<span class="server-info"><li class="fa fa-globe"></li>&nbsp; <?php echo strtolower($server_data['ip']); ?></span>
                 </ br>
		</div>
		<div>
</div>

<!--<table class="table list <?php if($server_data['vip'] == "1") echo 'vip-server'; ?>" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td style="width:5%;text-align:center;">
				<?php if($server_data['vip'] == "1"){ ?>
				<span class="glyphicon glyphicon-star"></span>
				<?php } else { ?>
				<span class="badge"><?php echo $rank; ?></span>
				<?php } ?>
			</td>
			<td style="width:55%">
				<a href="server.php?id=<?php echo $server_data['id']; ?>">
					<img src="<?php echo $banner; ?>" alt="<?php echo $server_data['name']; ?>s banner" width="468" height="60"/>
				</a>

				<br />
		</td>
			<td style="width:20%">
-->
<!--				<table class="table-reset">
					<tr>
						<th style="color:#333333">Players</th>
						<td><span class="label label-success"><?php if($status == 1){echo $info['Players'] . "/" . $info['MaxPlayers']; } else { echo "0"; } ?></span></td>
					</tr>
					<tr>
						<th style="color:#333333">Votes</th>
						<td><span class="label label-info"><?php echo $server_data['votes']; ?></span></td>
					</tr>
				</table>
-->
			</td>
		</tr>	
	</tbody>
</table>
<br />
<?php $rank++;} ?>
</div>
</div>
<center><?php
include "pagination.php";
?></center>
<?php
include 'includes/overall/footer.php'; 
?><?php 
include 'core/init.php';
include 'SenceNew/includes/overall/header.php'; 
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
<?php if(!empty($settings['news_message'])) { ?>
<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>News:</strong> <?php echo htmlspecialchars_decode($settings['news_message']); ?>
</div>
<?php } ?>
<br>				
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<div class="panel panel-default">
	<div class="panel-body">
		<div>
			<div class="pull-left" style="display:inline;">
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; View by <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="index.php?sort=players">Players</a></li>
						<li><a href="index.php?sort=status">Status</a></li>
						<li><a href="index.php">Votes</a></li>
					</ul>
				</div>
				<div class="dropdown" style="display:inline;">
					<a class="dropdown-toggle btn btn-default btn-small"  data-toggle="dropdown" href="#">&nbsp; Version <span class="caret"></span> &nbsp;</a>
					<ul class="dropdown-menu" role="menu">
						<?php 
						$result = mysql_query("SELECT DISTINCT `serverVersion` FROM `servers`");
						while($row = mysql_fetch_array($result)){
							echo "<li><a href='index.php?version=" . $row['serverVersion'] . "'>" . $row['serverVersion'] . "</a></li>";
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
							echo "<li><a href='index.php?country=" . $row['country'] . "'>" . $row['country'] . "</a></li>";
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
							echo "<li><a href='index.php?category=" . $row['name'] . "'>" . $row['name'] . "</a></li>";
						}
						?>
					</ul>
				</div>
				<div class="" style="display:inline;">
					<a class="btn btn-default btn-small" href="latest.php">&nbsp; Latest &nbsp;</a>
				</div>
			</div>
				<?php
				if($settings['facebook'] !== 'false') echo '<div style="float:right;"><div class="fb-like" data-href="https://senceservers.net/" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div></div>';
                                if($settings['google'] !== 'false') echo '<div style="float:right;"><div class="g-plusone" data-annotation="inline" data-width="15" data-href="https://senceservers.net/"></div></div>';
				if($settings['twitter'] !== 'false') echo '<div style="float:right;"><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://senceservers.net" data-count="none" data-hashtags="SenceServers">Tweet</a></div>';

				?>
             		</div>
                <br>
<style>
th {color:white;}
</style>
<br>
<hr />

<?php
//Pagination
$page = 1;
if(isset($_GET['page'])) { $page = $_GET['page']; } 
$limitT 		= (($page * $settings['pagination']) - $settings['pagination']); 
$pagination = $settings['pagination'];
//

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

$result  = mysql_query("SELECT * FROM `servers` WHERE `disabled` = '0' {$category_filter} {$country_filter} {$version_filter}{$offline_where} ORDER BY `vip` DESC, {$order} LIMIT $limitT, $pagination");
while ($server_data = mysql_fetch_array($result)) {
	$server_id = $server_data['id'];
	$info = array();

	if($server_data['banner'] != '') {

		$banner = (file_exists("banners/" . $server_data['banner'])) ? "banners/" . $server_data['banner'] : $server_data['banner'];

	} else $banner = "includes/img/1.png";

	//Check if server was already queried in the last X seconds
	// if yes -> get the cache data from database
	// if no  -> recheck
	
	if($server_data['cache_time'] > time() - $settings['server_cache'] || $settings['disable_query'] == "1"){
		$info['status'] = $server_data['status'];
		$info['Players'] = $server_data['Players'];
		$info['MaxPlayers'] = $server_data['MaxPlayers'];
		$info['serverVersion'] = $server_data['serverVersion'];
		if($info['status'] == 1){ $status = 1; } else { $status = 0; }
	} else {
		$Query = new MinecraftQuery($server_data['ip'], $server_data['port']);

		if($server_data['protocol'] == 1){
			$info = $Query->QueryNew();
		} else {
			$info = $Query->QueryOld();
		}

		//Check status of the server
		if($Query->getStatus() !== false){ $status = 1; } else { $status = 0; }

		//Update the cache
		if($status == 1) {
			mysql_query("UPDATE `servers` SET `status` = '$status', `serverVersion` = '{$info['serverVersion']}', `Players` = '{$info['Players']}', `MaxPlayers` = '{$info['MaxPlayers']}', `cache_time` = unix_timestamp() WHERE `id` = '$server_id'");
		}else{
			mysql_query("UPDATE `servers` SET `status` = '$status', `Players` = '0', `cache_time` = unix_timestamp()  WHERE `id` = '$server_id'");
		}
	
	
	}
if($status == 0 && $settings['show_offline_servers'] == '0') { continue; }
?>
<style>
/* Gray Scale */
.hover08 figure img {
	-webkit-filter: grayscale(100%);
	filter: grayscale(100%);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.hover08 figure:hover img {
	-webkit-filter: grayscale(0);
	filter: grayscale(0);
}
h2 span {
	margin-left: 1em;
	color: #aaa;
	font-size: 85%;
}
.column {
	margin: 15px 15px 0;
	padding: 0;
}
.column:last-child {
	padding-bottom: 60px;  
        padding-top: 60px;
}
.column::after {
	content: '';
	clear: both;
	display: block;
}
.column div {
	position: relative;
	float: left;
	width: 468px;
	height: 60px;
	margin: 0 0 0 0px;
	padding: 0;
}
.column div:first-child {
	margin-left: 0;
}
.column div span {
	position: absolute;
	bottom: -20px;
	left: 0;
	z-index: -1;
	display: block;
	width: 468px;
	margin: 0;
	padding: 0;
	color: black;
	font-size: 18px;
	text-decoration: none;
	text-align: center;
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
	opacity: 0;
}
figure {
	width: 468px;
	height: 60px;
	margin: 0 0 50% 50%;
	padding: 0;
	background: #fff;
	/*overflow: hidden;*/
}
figure:hover+span {
	bottom: -36px;
	opacity: 1;
}
server-info {
    background: #0099CC;
    width: 468px;
    padding: 2px 5px;
    display: block;
    color: white;
}
</style>

<div class="hover08 column">
		<div>
			<figure><img src="<?php echo $banner; ?>" alt="<?php echo $server_data['name']; ?>s banner" width="468" height="60"/>
			<span><?php echo $server_data['name']; ?></span></figure>
			<span class="server-info"><h4 class="fa fa-globe"></h4>&nbsp;<?php echo strtolower($server_data['ip']); if($server_data['port'] != "25565" & $server_data['show_port'] != "no") echo ":".$server_data['port']; ?>span>
                 </ br>
		</div>
		<div>
</div>

<!--<table class="table list <?php if($server_data['vip'] == "1") echo 'vip-server'; ?>" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td style="width:5%;text-align:center;">
				<?php if($server_data['vip'] == "1"){ ?>
				<span class="glyphicon glyphicon-star"></span>
				<?php } else { ?>
				<span class="badge"><?php echo $rank; ?></span>
				<?php } ?>
			</td>
			<td style="width:55%">
				<a href="server.php?id=<?php echo $server_data['id']; ?>">
					<img src="<?php echo $banner; ?>" alt="<?php echo $server_data['name']; ?>s banner" width="468" height="60"/>
				</a>

				<br />
		</td>
			<td style="width:20%">
-->
<!--				<table class="table-reset">
					<tr>
						<th style="color:#333333">Players</th>
						<td><span class="label label-success"><?php if($status == 1){echo $info['Players'] . "/" . $info['MaxPlayers']; } else { echo "0"; } ?></span></td>
					</tr>
					<tr>
						<th style="color:#333333">Votes</th>
						<td><span class="label label-info"><?php echo $server_data['votes']; ?></span></td>
					</tr>
				</table>
-->
			</td>
		</tr>	
	</tbody>
</table>
<br />
<?php $rank++;} ?>
</div>
</div>
<center><?php
include "pagination.php";
?></center>
<?php
include 'includes/overall/footer.php'; 
?>