<?php
include 'core/init.php';
include 'core/query/MinecraftVotifier.php';
include 'includes/overall/header.php';
include 'core/functions/recaptchalib.php';


$server_id  = (INT)$_GET['id'];
$user_id	= id_to_user_id($server_id);
$addedBy 	= username_from_user_id($user_id);

if(empty($_GET['id']) == true || $addedBy == false){echo "<h2>Server not found.</h2>";include 'includes/overall/footer.php';die();}

$result  = mysql_query("SELECT `ip`, `name`, `votifier_key`, `votifier_ip`, `votifier_port`, `banner` FROM `servers` WHERE `id` = '$server_id'");
$server_data = mysql_fetch_array($result, MYSQL_ASSOC);


  if(isset($_POST['$username']) && !empty($_POST['$username'])) {
          echo '<h2>Your not getting anything if you dont tell me your username.</h2>';
          exit;
  }

if(!empty($_POST)) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$currentTime = time();

	$result = mysql_query("SELECT `timestamp` FROM `votes` WHERE `server_id` = '{$server_id}' AND `ip` = '{$ip}' ORDER BY `id` DESC LIMIT 1");
	$dbTime = @mysql_result($result, 0);

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
	if($currentTime - $dbTime <= 86400) {
          if(logged_in() && is_admin($session_user_id)) {
                
         }else{
		$errors[] = "You already voted today!";
	}
}
	if(!empty($errors)) echo output_errors($errors);
	
	if(empty($errors)) {
		if($server_data['votifier_key'] !== "false"){

			$votifier_ip = (empty($server_data['votifier_ip']) == true) ? $server_data['ip'] : $server_data['votifier_ip'];
			$username = htmlspecialchars($_POST['username'], ENT_QUOTES);

			if(Votifier($server_data['votifier_key'], $votifier_ip, $server_data['votifier_port'], $username) !== false) {

				mysql_query("INSERT INTO `votes` (`ip`, `server_id`, `timestamp`, `mcuser`) VALUES ('".$ip."', {$server_id}, {$currentTime}, '$username')");
				mysql_query("UPDATE `servers` SET `votes` = votes+1 WHERE `id` = {$server_id}");

				echo output_success("Your vote was successfully added !");

			} else {
				echo "<p>We had some problems sending the vote to the server..</p>";
			}

		} else {

			mysql_query("INSERT INTO `votes` (`ip`, `server_id`, `timestamp`, `mcuser`) VALUES ('".$ip."', {$server_id}, {$currentTime}, '$username')");
			mysql_query("UPDATE `servers` SET `votes` = votes+1 WHERE `id` = {$server_id}");

			echo output_success("Your vote was successfully added !");

		}
	}

}
?>

<h3>Vote for <?php echo $server_data['name']; ?></h3>
<br> <img src="banners/<?php echo $server_data['banner']; ?>">
<script src='https://www.google.com/recaptcha/api.js'></script>
<form action="#" method="POST" role="form">
	<?php if ($server_data['votifier_key'] !== "false") { ?>
	<label>In Game Username</label>
	<input class="form-control" type="text" name="username" class="span3"/><br />
	<?php } ?>
	<div class="g-recaptcha" data-sitekey="6LescA0TAAAAALyZm3e3tRVV07pRAmifbOmjadix"></div>
	<br />
	<input class="btn btn-primary span3" type="submit" value="Vote" />
</form>


<?php include 'includes/overall/footer.php'; ?>