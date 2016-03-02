<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php'; 

@$sid  = (INT)$_GET['id'];
$data = mysql_fetch_assoc(mysql_query("SELECT `ip`, `country`, `port`, `show_port` `description`, `youtube_id`, `website`, `name`, `votifier_key`, `votifier_ip`, `votifier_port`, `banner`, `category_id` FROM `servers` WHERE `id` = '$sid' AND `user_id` = '$session_user_id'"));
if($data == false){
	echo "<h2>This server does not belongs to you !</h2>";
	include 'includes/overall/footer.php'; 
	die();
}
$staff_result  = mysql_query("SELECT * FROM `servers_staff` WHERE `serverID` = '$sid'") or die(mysql_error());
$staff_to_remove = "";




if(empty($_POST) == false) {
	$_POST['name']= htmlspecialchars($_POST['name'], ENT_QUOTES);	
	$allowed_extensions = array("jpg", "jpeg", "png", "gif");
	$image = (empty($_FILES['image']['name']) == false) ? true : false;

	if(empty($errors) == true) {

		if(!$image) {
			
		} else {

			$image_file_name		= $_FILES['image']['name'];
			$image_file_extension	= explode('.', $image_file_name);
			$image_file_extension	= strtolower(end($image_file_extension));
			$image_file_temp		= $_FILES['image']['tmp_name'];
			$image_file_size		= $_FILES['image']['size'];
			list($image_width, $image_height)	= getimagesize($image_file_temp);

			if(in_array($image_file_extension, $allowed_extensions) !== true) {
				$_SESSION['error'][] = "You can only upload JPG, JPEG, PNG and GIF images";
			}
			if($image_width != "468" || $image_height != "60") {
				$errors[] = "Banner is too large or too small, only 468 * 60 accepted. Your image is: " .$image_width." * ".$image_height;
			}
		}

		if(strlen($_POST['name']) > 64){
			$errors[] = "Server name is too long !";
		}
		if(strlen($_POST['name']) < 4){
			$errors[] = "Server name is too short !";
		}
		if(!empty($_POST['website'])){
			if(!filter_var($_POST['website'], FILTER_VALIDATE_URL)){
				$errors[] = "Invalid website url !";
			}
		}
		if(strlen($_POST['youtube_id']) > 25){
			$errors[] = "Youtube ID is too long !";
		}
		if(strlen($_POST['description']) > 2500){
			$errors[] = "Description is too long! Please keep it under 2500 letters !";
		}
		
		if(isset($_POST['category'])) {
			if(is_array($_POST['category'])){
				foreach($_POST['category'] as $key => $category){
					if(!is_numeric($category)) unset($_POST['category'][$key]);
				}
				if(count($_POST['category']) > $settings['max_categories']){
					$errors[] = "You selected too many categories !";
				}
				if(count($_POST['category']) < 1){
					$errors[] = "You need to select at least 1 category !";
				}
			}
		}
		
	}
}

?>
<h2>Server Settings</h2>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo '<font color=\'green\'>The server settings were successfully updated !</font>';
} else {
if(empty($_POST) == false && empty($errors) == true) {
	if(empty($_POST['votifier_key'])) $votKey = "false"; else $votKey =  htmlspecialchars($_POST['votifier_key'], ENT_QUOTES);
	$votPort = (int)$_POST['votifier_port'];
	$votIp	 =  htmlspecialchars($_POST['votifier_ip'], ENT_QUOTES);
	$ip 	 = $_POST['ip'];
	$port	 = $_POST['port'];
	$name    =  htmlspecialchars($_POST['name'], ENT_QUOTES);
	$website 	 = $_POST['website'];
	$show_port 	 = $_POST['show_port'];
	$youtube_id  = htmlspecialchars($_POST['youtube_id'], ENT_QUOTES);
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES);
	$category = (isset($_POST['category'])) ? json_encode($_POST['category']) : "null";
	$country = (country_check(0, $_POST['country'])) ? $_POST['country'] : 'US';

	if($image != false) { 
		$image_new_name = md5(time().rand()) . '.' . $image_file_extension;
		move_uploaded_file($image_file_temp, 'banners/' . $image_new_name);	
	 	@unlink('banners/'.$data['banner']);
		mysql_query("UPDATE `servers` SET `banner` = '$image_new_name' WHERE `id` = '$sid'");
	}

	mysql_query("UPDATE `servers` SET `ip` = '$ip', `port` = '$port', `country` = '$country', `show_port` = '$show_port', `website` = '$website', `youtube_id` = '$youtube_id', `description` = '$description', `category_id` = '$category', `name` = '$name', `votifier_key` = '$votKey', `votifier_ip` = '$votIp', `votifier_port` = '$votPort' WHERE `id` = '$sid'");
        $listofstaff = explode(",",$_POST['staff_members']);
	if(empty($_POST) == false) foreach($listofstaff as $staff){
		$s = explode(";", $staff);
if($_POST['staff_members'] != ""){


		mysql_query("INSERT INTO `servers_staff` (`ID`, `serverID`, `player`, `rank`) VALUES (NULL, '$sid', '$s[0]', '$s[1]')");
}
}
$listofstafftoremove = explode(",",$_POST['staff_members']);
if(empty($_POST) == false) foreach($listofstafftoremove as $staff){
	mysql_query("DELETE FROM `servers_staff` WHERE `serverID` = '$sid' AND `player` = '$staff'");
}

    



	header('Location: my_servers.php?successUpdate');
} else if (empty($errors) == false) {
	echo output_errors($errors);
}
?>
<form action="" method="post" role="form" enctype="multipart/form-data">
	<div class="form-group">
		<input type="text" name="ip" value="<?php echo $data['ip']; ?>" class="form-control" />
	</div>
        
	<div class="form-group">
		<input type="text" name="port" value="<?php echo $data['port']; ?>" class="form-control" />
	</div>

        <div class="form-group">
		<label>Show port? Default is false.</label>
                <input type="hidden" name="show_port" value="no" />
                <input type="checkbox" name="show_port" value="yes" /> 
	</div>

	<div class="form-group">
		<label>Name*</label>
		<input type="text" name="name" value="<?php echo $data['name']; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Website</label>
		<input type="text" name="website" value="<?php echo $data['website']; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Youtube Video ID</label>
		<input type="text" name="youtube_id" value="<?php echo $data['youtube_id']; ?>" placeholder="Ex: xHRkHFxD-xY" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Upload Banner*</label><br />
		<p class="help-block">JPG, JPEG or GIF - 468 x 60</p>
		<input type="file" name="image" class="form-control" />
	</div>

	<div class="form-group">
		<label>Country</label>
		<select name="country" class="form-control">
			<?php country_check(1, $data['country']); ?>
		</select>
	</div>
	
	<div class="form-group">
		<label>Votifier Key</label>
		<input type="text" name="votifier_key" class="form-control" value="<?php echo $data['votifier_key']; ?>" />
	</div>
	
	<div class="form-group">
		<label>Votifier Ip</label>
		<span class="help-block">Leave empty if you don't want to use a custom votifier IP</span>
		<input type="text" name="votifier_ip" value="<?php echo $data['votifier_ip']; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Votifier Port</label>
		<input type="text" name="votifier_port" value="<?php echo $data['votifier_port']; ?>" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Category*</label>
		<p class="muted">You can select up to <?php echo $settings['max_categories']; ?> categories</p>
			<?php
				$result = mysql_query("SELECT * FROM `categories`");
				$data = ($data['category_id'] !== "null") ? json_decode($data['category_id']) : array();
				while($row = mysql_fetch_array($result)){
					if(in_array($row['category_id'], $data)){
						echo "<input type='checkbox' name='category[]' value='" . $row['category_id'] . "' checked>" . $row['name'] . "<br />";
					} else {
						echo "<input type='checkbox' name='category[]' value='" . $row['category_id'] . "'>" . $row['name'] . "<br />";
					}
				}
			?>
	</div>
	<?php $description = mysql_fetch_assoc(mysql_query("SELECT `description` FROM `servers` WHERE `id` = '$sid' AND `user_id` = '$session_user_id'"));
	?><div class="form-group">
		<label>Description</label>
		<p class="muted">Write an <b>awesome</b> description to attract visitors!</p>
		<textarea name="description" class="form-control"><?php echo $description['description']; ?></textarea>
	</div>
	<div class="form-group">
		<label>Staff Members</label>
		<ul>
		<?php
		//Show current staff
		while($row = mysql_fetch_array($staff_result, MYSQL_ASSOC)){
		echo'<li>' . $row['player'] . '&nbsp;<a href="#" class="deleteStaff" data-player="WOOOOOOOOO"><span class="label label-danger"><span class="glyphicon glyphicon-remove"></span>Coming Soon</span></a><h1 class="playerName" hidden>' . $row['player'] . '</h1></li>';
		}
		?>
		</ul>
		<label>Add staff members</label>
		<label>Usage: Seperate staff with , </label><br>
		<label>To give them a rank do (playername);(rank)</label>
		<input type="text" name="staff_members" placeholder="Note: Contact technical support to remove a staff member" class="form-control" />
	</div>
	<br />
	<input class="btn btn-default" type="submit" value="Submit" />
</form>
	
<?php
}
include 'includes/overall/footer.php';
?>
<script>
$(document).ready(function(){
    $(".deleteStaff").click(function(e){
        $.ajax({url: "https://senceservers.net", success: function(result){
        	var txt = $(e.target).data("player");
            console.log("DELETED A STAFF MEMBER.");
            console.log(txt);
       		alert(txt);
        }});
    });
});
</script>