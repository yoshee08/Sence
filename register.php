<?php 
include 'core/init.php';
logged_in_redirect();

if($settings['register'] == false && logged_in() == false){
	protect_page();
}
include 'includes/overall/header.php'; 
require_once('core/functions/recaptchalib.php');

if(empty($_POST) == false) {
	$fields = array('username', 'password', 'password_again', 'email', 'name', 'recaptcha_response_field');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $fields) == true){
			$errors[] = 'All fields are required';
			break 1;
		}
	}
	
//captcha
$resp = recaptcha_check_answer ($settings['recaptcha_private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
//-------

	$_POST['name']     = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$_POST['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$_POST['username'] = htmlspecialchars($_POST['username'], ENT_QUOTES);
	
	if(empty($errors) == true) {
		if($resp->is_valid == false) {
			$errors[] = "Captcha is not valid !";
		}
		if(user_exists($_POST['username']) == true) {
			$errors[] = 'Sorry, the username \'' . $_POST['username'] . '\' is already taken.';
		}
		if(preg_match("/\\s/", $_POST['username']) == true) {
			$errors[] = 'Your username must not contain any spaces';
		}
		if(strlen($_POST['password']) < 6) {
			$errors[] = 'Password too short, it must be at least 6 characters!';
		}
		
		if($_POST['password'] !== $_POST['password_again']) {
			$errors[] = 'Your passwords need to match!';
		}
		
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'A valid email adress is required';
		}
		if(email_exists($_POST['email']) == true) {
			$errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use';
		}
	}
}
?>
<h2>Register</h2>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	$confirm = "";
	if($settings['email_confirmation'] == '1') $confrim = '&nbsp; Please check your email account for activation!';
	echo output_success('You\'ve been registered successfully!' . $confirm);
} else {
	if(empty($_POST) == false && empty($errors) == true){
		if($settings['email_confirmation'] == '1') $active = '0'; else $active = '1';
		$register_data = array(
			'username'     => $_POST['username'],
			'password'     => $_POST['password'],
			'email'        => $_POST['email'],
			'name'         => $_POST['name'],
			'ip'           => $_SERVER['REMOTE_ADDR'],
			'date'         => date('Y.m.d'),
			'email_code'   => md5($_POST['username'] + microtime()),
			'active'	   => $active
		);
		register_user($register_data);
		header('Location: register.php?success');
		exit();
	} elseif(empty($errors) == false) {
		echo output_errors($errors);
	}

?>
<form action="" method="post" role="form">
	<div class="form-group">
		<label>Username</label>
		<input type="text" name="username" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Password</label>
		<input type="password" name="password" class="form-control" />
	
	</div>
	<div class="form-group">
		<label>Password Again</label>
		<input type="password" name="password_again" class="form-control" /> 
	</div>
	
	<div class="form-group">
		<label>Email</label>
		<input type="text" name="email" class="form-control" />
	</div>
	
	<div class="form-group">
		<label>Nickname</label>
		<input type="text" name="name" class="form-control" /><br />
	</div>
	
	<?php $error = null; echo recaptcha_get_html($settings['recaptcha_public'], $error); ?>
	
	<br /><button type="submit" class="btn btn-default">Submit</button>
<center><small><h4>When registering a new account, you also agree to our <a style="color:#333" href="https://senceservers.net/terms.php">Terms of Use</a></h4></small></center>
</form>		
<?php
}
include 'includes/overall/footer.php'; 
?>