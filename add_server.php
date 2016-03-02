<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php'; 
require_once('core/functions/recaptchalib.php');

if(empty($_POST) == false) {
	$_POST['ip'] = htmlspecialchars($_POST['ip'], ENT_QUOTES);
	$_POST['port'] = (int)$_POST['port'];
	$banner   = htmlspecialchars($_POST['banner'], ENT_QUOTES);
	$possibleProtocols = array("1", "2");
	$allowed_extensions = array("jpg", "jpeg", "gif");
	$image = (empty($_FILES['image']['name']) == false) ? true : false;

	//captcha
	$resp = recaptcha_check_answer ($settings['recaptcha_private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	//-------
	if($resp->is_valid == false) {
		$errors[] = "Captcha is not valid !";
	}
	if(server_exists2($_POST['ip'], $_POST['port'])) {
		$errors[] = "Server already exists into the database!";
	}
	if(strlen($_POST['name']) > 64){
		$errors[] = "Server name is too long !";
	}
	if(strlen($_POST['name']) < 4){
		$errors[] = "Server name is too short !";
	}
	if(!in_array($_POST['protocol'], $possibleProtocols)){
		$errors[] = "Your selected protocol is wrong !";
	} else {
		$Query = new MinecraftQuery($_POST['ip'], $_POST['port']);
		$info = ($_POST['protocol'] == 1 ) ? $Query->QueryNew(): $Query->QueryOld();
		if($info == false) $errors[] = "Your server is offline or you selected the wrong version you may need to change your Query Protocol";
	}
	
		if(!$image) {
		$errors[] = "You need to upload a banner !";
	} else {


		$image_file_name		= $_FILES['image']['name'];
		$image_file_extension	= explode('.', $image_file_name);
		$image_file_extension	= strtolower(end($image_file_extension));
		$image_file_temp		= $_FILES['image']['tmp_name'];
		$image_file_size		= $_FILES['image']['size'];
		list($image_width, $image_height)	= getimagesize($image_file_temp);

		if(in_array($image_file_extension, $allowed_extensions) !== true) {
			$_SESSION['error'][] = "You can only upload JPG, JPEG and GIF images";
		}
		if($image_width != "468" || $image_height != "60") {
			$errors[] = "Banner is too large or too small, only 468 * 60 accepted. Your image has: " .$image_width." * ".$image_height;
		}
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
?>
<center><a href="https://senceservers.net/shop/vip.php" target="_blank" >
<img src="buyvip.png" border="0">
</a></center>
<h2>Submit a new Server!</h2>
<?php

if(isset($_GET['success']) && empty($_GET['success'])) {
	$confirm = "";
	if($settings['server_confirmation'] == '1') $confirm = "&nbsp;&nsbp; Please wait for admin approval!";
	echo output_success('Your server has been added!' . $confirm);
}

if(empty($errors) == false) echo output_errors($errors);

if(empty($errors) == true && !empty($_POST)){
	$ip   	 		= $_POST['ip'];
	$port 	  		= $_POST['port'];
	$show_port 	  	= $_POST['show_port'];
	$name 	 		= mysql_real_escape_string(htmlspecialchars($_POST['name'], ENT_QUOTES));
	$votifier 		= ($_POST['votifierKey'] !== '') ? htmlspecialchars($_POST['votifierKey'], ENT_QUOTES) : "false";
	$votPort  		= ($_POST['votifierPort'] !== '') ? htmlspecialchars($_POST['votifierPort'], ENT_QUOTES) : "false";
	$country  		= get_country($ip);
	$disabled 		= ($settings['server_confirmation'] == '1') ? '1' : '0';
	$category 		= (isset($_POST['category'])) ? json_encode($_POST['category']) : "null";
	$image_new_name = md5(time().rand()) . '.' . $image_file_extension;

	move_uploaded_file($image_file_temp, 'banners/' . $image_new_name);
	move_uploaded_file($image_file_temp, 'mobile/banners/' . $image_new_name);	

	mysql_query("INSERT INTO `servers` (`user_id`, `category_id`, `ip`, `show_port`, `port`, `banner`, `disabled`, `vip`, `name`, `status`, `votifier_key`, `votifier_port`, `country`, `protocol`, dateadded) VALUES ('$session_user_id', '$category', '$ip', '$show_port', '$port', '$image_new_name', '$disabled', 0, '$name', '1', '$votifier', '$votPort', '$country', '{$_POST['protocol']}', NOW())");
	header('Location: add_server.php?success');
}
?>
<form method="post" action="" role="form" enctype="multipart/form-data">
	<div class="form-group">
		<label>Name</label>
		<input class="form-control" type="text" name="name" class="span4" />
	</div>
	
	<label>Server IP</label>
	<div class="input-group">
		<span class="input-group-addon" id="response">Waiting..</span>
		<input class="form-control" type="text" name="ip" class="span4" onblur="testServer();" />
	</div><br />
	
	<div class="form-group">
		<label>Connection Port</label>
		<input class="form-control" type="text" name="port" value="25565" class="span4" maxlength="5" onblur="testServer();"/>
	</div>
	<div class="form-group">
		<label>Should your port be shown?</label>
		<select name="show_port" class="form-control">
			<option value="no">No</option>
			<option value="yes">Yes</option>                               
		</select>
	</div>
	<div class="form-group">
		<label>Query Protocol</label>
		<select name="protocol" class="form-control">
			<option value="1">New Protocol</option>
			<option value="2">Old Protocol</option>                               
		</select>
	</div>
	
	<div class="form-group">
		<label>Upload Banner</label><br />
		<p class="help-block">JPG, JPEG or GIF - 468 x 60</p>
		<input type="file" name="image" value="http://www.senceservers.net/banners/default.jpg" class="form-control" />
	</div>

	<div class="form-group">
		<label>Category</label>
		<p class="muted">You can select up to <?php echo $settings['max_categories']; ?> categories</p>
			<?php
				$result = mysql_query("SELECT * FROM `categories`");
				while($row = mysql_fetch_array($result)){
					echo "<input type='checkbox' name='category[]' value='" . $row['category_id'] . "'>" . $row['name'] . "<br />";
				}
			?>
	</div>

	<br />

	<div class="form-group">
		<label class="checkbox">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="votifierCheck" id="votifierCheck"> Votifier Enabled?
		</label>
	</div>
	
	<div id="votifier" style="display: none;">
		<div class="form-group">
			<label>Votifier Key (Leave this blank if you wish to not enable votifier)</label>
			<input class="form-control" type="text" name="votifierkey" class="span4" />
		</div>
		<div class="form-group">
			<label>Votifier Port (Leave this blank if you wish to not enable votifier)</label>
			<input class="form-control" type="text" name="votifierPort" class="span4" />
		</div>
	</div>
	
	<?php $error = null; echo recaptcha_get_html($settings['recaptcha_public'], $error); ?>

	<br /><button class="btn btn-default" type="submit">Submit</button>
<style>
.fixed {
   padding-right: 2px;
   padding-top: 5px;
   padding-bottom: 5px;        
   background-color: #0099CC;
   color: white;
   border-color: #161f22;
   border-style: solid;
   border-width: 5px;
   border-radius: 10px;
</style>
<a href="https://senceservers.net/my_servers.php" target="_blank" ><center><h5 class="fixed">Note: To add a Server description/staff list you must edit your server after registraion</h5></center></a>
<br>
<center><a href="https://senceservers.net/shop/vip.php" target="_blank">
<img src="buyvip.png" border="0">
</a></center>
</form>

<?php include 'includes/js/add_server.js'; ?>
<?php include 'includes/overall/footer.php'; ?>
