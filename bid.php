<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php'; 
require_once('core/functions/recaptchalib.php');
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<h2>Bid for Sponsor slots!</h2>
<?php

if(isset($_GET['success']) && empty($_GET['success'])) {
	$confirm = "";
	echo output_success('Your bid has been added.' . $confirm);
}
 

if($_POST){
	$serverid  	= $_POST['serverid'];
	$price 		= $_POST['price'];

	mysql_query("UPDATE `servers` SET `price` = '$price' WHERE id ='$serverid'");
	header('Location: bid.php?success');

}
?>

<style>

</style>
<form method="post" action="" role="form" enctype="multipart/form-data">
	<div class="form-group">
		<label>Price</label>
		<input class="form-control" type="text" name="price" class="span4" placeholder="Price in USD" />
	</div>
	
	
	<div class="form-group">
		<label>Category</label>
		<p class="muted">Select your server</p>
                     <select name="serverid" id="servers">
			<?php
				$result1 = mysql_query("SELECT id, name FROM `servers` WHERE user_id='$session_user_id' AND disabled='0'");
				while ($row = mysql_fetch_array($result1)){
					echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option> ";
				}
			?>
                     </select>
	</div>

	<br />

	<br /><button class="btn btn-default" type="submit">Submit</button>

</form>
<?php include 'includes/overall/footer.php'; ?>