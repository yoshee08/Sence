<?php 
	include 'core/init.php';
	include 'includes/overall/header.php'; 
	require_once('core/functions/recaptchalib.php');

if(empty($_POST) == false) {
	$fields = array('subject', 'name', 'email', 'message', 'captchavar');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $fields) == true){
			$errors[] = 'All fields are required';
			break 1;
		}
	}
	
//captcha
$resp = recaptcha_check_answer ($settings['recaptcha_private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
//-------

	$_POST['subject']  = htmlspecialchars($_POST['subject'], ENT_QUOTES);
	$_POST['name']     = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$_POST['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$_POST['message']  = htmlspecialchars($_POST['message'], ENT_QUOTES);
	
	if(empty($errors) == true) {
		if($resp->is_valid == false) {
			$errors[] = "Captcha is not valid !";
		}
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'A valid email adress is required';
		}
	}
}
?>
<h2>Contact</h2>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo output_success('Your message has been sent successfully! But please allow 24-48 hours for a response!');
} else {
	if(empty($_POST) == false && empty($errors) == true){
		mail($settings['contact_email'],  $_POST['subject'], $_POST['message'], 'From: ' . $_POST['email']);
		header('Location:contact.php?success');
	} elseif(empty($errors) == false) {
		echo output_errors($errors);
	}
}
?>

<form action="" method="post" role="form">
	<div class="form-group">
		<input class="form-control" type="text" name="subject" placeholder="Title"/>
	</div>
	
	<div class="form-group">
		<input class="form-control" type="text" name="email" placeholder="Your Email"/>
	</div>
	
	<textarea class="form-control" rows="6" name="message">Message</textarea><br />

	<?php $error = null; echo recaptcha_get_html($settings['recaptcha_public'], $error); ?><br />

	<input class="btn btn-default" type="submit" value="Send mail"/>
</form>
<?php include 'includes/overall/footer.php'; ?>