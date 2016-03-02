<?php
include 'core/init.php';
include 'includes/overall/header.php';
logged_in_redirect();

if(empty($_POST) == false) {
	$email = $_POST['email'];

	if(empty($email)) {
		$errors[] = 'You need to fill up the email section!';
	} else if(email_exists($email) == false) {
		$errors[] = 'This user doesn\'t exists';
	} 

	if(empty($errors) == false) {
		echo output_errors($errors);
	} else {
		$reset_code = md5($_POST['email'] + microtime());
		mysql_query("UPDATE `users` SET `email_code` = '$reset_code' WHERE `email` = '$email'");

		sendmail($email, 'SenceServers Forgot password', 
			"To reset your password, access the link below:\n\n
			" . $settings['url'] . "reset.php?email=" . $email . "&code=" . $reset_code . " \n\n
		");

		//echo $settings['url'] . "reset.php?email=" . $email . "&code=" . $reset_code;
	}
} 
?>
<h2>Forgot password</h2>
<h5>If you don't see the message appear in your email, don't forget to check your spam folder!</h5>
<form action="" method="post" role="form">
	<div class="form-group">
		<label>Email</label>
		<input name="email" type="email" class="form-control" tabindex="1">
	</div>
	
	<button type="submit" class="btn btn-default">Submit</button>
</form>
<?php include 'includes/overall/footer.php'; ?>