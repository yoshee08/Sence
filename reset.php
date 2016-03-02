<?php
include 'core/init.php';
include 'includes/overall/header.php';
logged_in_redirect();

@$_GET['email'] = htmlspecialchars($_GET['email'], ENT_QUOTES);
@$_GET['code']	= htmlspecialchars($_GET['code'], ENT_QUOTES);

@$username = mysql_result(mysql_query("SELECT `username` FROM `users` WHERE `email` = '{$_GET['email']}' AND `email_code` = '{$_GET['code']}'"), 0);
if($username == false ) {echo "The email and the code don't corespond";include 'includes/overall/footer.php';die(); }

if(empty($_POST) == false) {

		if(strlen($_POST['password']) < 6) {
			$errors[] = 'Password too short, it must be at least 6 characters!';
		}
		if($_POST['password'] !== $_POST['repeat_password']) {
			$errors[] = 'Your passwords need to match!';
		}

	if(empty($errors) == false) {
		echo output_errors($errors);
	} else {
		$reset_code = md5($_GET['email'] + microtime());
		$password = c_hash($username, $_POST['password']);

		mysql_query("UPDATE `users` SET `email_code` = '$reset_code', `password` = '$password' WHERE `email` = '{$_GET['email']}'");

		echo output_success("Your password was changed !");
	}
} 
?>
<h2>Reset Password</h2>
<form action="" method="post" role="form">
	<div class="form-group">
		<label>New Password</label>
		<input name="password" type="password" class="form-control" tabindex="1">
	</div>
	
	<div class="form-group">
		<label>Repeat Password</label>
		<input name="repeat_password" type="password" class="form-control" tabindex="2">
	</div>

	<button type="submit" class="btn btn-default">Submit</button>
</form>
<?php include 'includes/overall/footer.php'; ?>