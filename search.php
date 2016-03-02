<?php 
include 'core/init.php';
include 'includes/overall/header.php'; 

if(empty($_GET)){
	header('Location: index.php');
}

$term = mysql_real_escape_string($_GET['term']);
$term = str_replace("%", "", $term);
$term = htmlspecialchars($term, ENT_QUOTES);
$keywords = preg_split('#\s+#', $term);
$c = 0;
foreach($keywords as $keyword){
	if(strlen($keyword) < 3){
		$c++;
	}
}
if($c > 0){
	$errors[] = "One of the keywords you entered is too short.";
}
if(strlen($term) < 4){
	$errors[] = "Search string too short!";
}
if(empty($errors) !== true){
	echo output_errors($errors);
} else {
	echo "<i>Search results for <b>" . $term . "</b> based on the <b>Name</b> of the servers</i><br /><br />";

	$name_where		   = "`name` LIKE '%" . implode("%' OR `name` LIKE '%", $keywords) . "%'";
	$query             = mysql_query("SELECT * FROM `servers` WHERE {$name_where} AND `disabled` = 0");
	
	while($server_data = mysql_fetch_assoc($query)){
		$last_update		= time() + $server_data['cache_time'];
		$last_updateM 		= date("m", $last_update);
		$status				= $server_data['status'];
?>
<table class="table table-bordered table-stripped" style="background:white;">
	<tbody>			
		<tr>
			<td style="width:265px;">
				<?php 
					if($status == 1) echo	'<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>&nbsp;';
					else			 echo '<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>&nbsp;';
				?>
				
				<?php echo "<a href='server.php?id=" . $server_data['id'] . "'>" . $server_data['ip'] . ":" . $server_data['port'] . "</a>"; ?>
				<hr style="margin:10px 0;"/>
				<strong>Name:</strong> <?php echo $server_data['name']; ?><br />
				<strong>Online players:</strong> <?php echo $server_data['Players'] . "/" . $server_data['MaxPlayers']; ?><br />
				<strong>Votes:</strong> <?php echo $server_data['votes']; ?>
			</td>
			<td style="width: 600px;vertical-align:middle;text-align:center;">
			<?php 
			if($server_data['banner'] !== '') echo '<img src="' . "/banners/" . $server_data['banner'] . '" />';
			else echo '<img src="dynamic_image.php?s=' . $server_data['id'] . '&type=background" />';
			?>
			</td>
		</tr>
	</tbody>
</table>
<?php	
	}
}


include 'includes/overall/footer.php'; ?>