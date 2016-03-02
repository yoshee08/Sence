<?php
include 'core/init.php';
include 'includes/overall/header.php';
logged_in_redirect();
//require_once('core/functions/recaptchalib.php');

if(empty($_POST) == false) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if(empty($username) || empty($password)) {
		$errors[] = 'You need to fill up the username and password!';
	} else if(user_exists($username) == false) {
		$errors[] = 'This user doesn\'t exists';
	} else if(user_active($username) == false) {
		$errors[] = 'You have\'t activated your account!';
	} else {
		$login = login($username, $password);
		if($login == false) {
			$errors[] = 'Username and password combination is incorrect';
		}
	}
	if(empty($errors) == false) {
		echo '<h2>Login failed...</h2>';
		echo output_errors($errors);
	} else {
		$_SESSION['user_id'] = $login;
		header('Location: index.php');
		exit();
	}
} else {
?>
<h2>Login</h2>
<form action="" method="post" role="form">
	<div class="form-group">
		<label>Username</label>
		<input name="username" type="text" class="form-control" tabindex="1">
	</div>
	<div class="form-group">
		<label>Password</label>
		<input name="password" type="password" class="form-control" tabindex="2">
	</div>
	
	<button type="submit" class="btn btn-default">Login</button>
	<a href="forgot.php"><button type="button" class="btn btn-info">I forgot my password</button></a>
</form>
<?php
}
include 'includes/overall/footer.php';
?>