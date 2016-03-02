<?php
include 'core/init.php';
protect_page();


if(empty($_POST) == false) {
	$fields = array('current_password', 'password', 'password_again');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $fields) == true){
			$errors[] = 'All fields are required';
			break 1;
		}
	}

	if(c_hash($user_data['username'], $_POST['current_password']) == $user_data['password']) {
		if(trim($_POST['password']) !== trim($_POST['password_again'])) {
			$errors[] = 'New passwords no not match!';
		} elseif(strlen($_POST['password']) < 6) {
			$errors[] = 'Your new password is too short!';
		}
	} else {
		$errors[] = 'Your current password is incorrect!';
	}
}

include 'includes/overall/header.php'; 
?>
<h2>Change Password</h2>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo output_success('The password was successfully changed !');
} else {
if(empty($_POST) == false && empty($errors) == true) {
	change_password($session_user_id, $user_data['username'], $_POST['password']);
	header('Location: changepassword.php?success');
} else if (empty($errors) == false) {
	echo output_errors($errors);
}
?>
<form action="" method="post" role="form">
	<div class="form-group">
		<label>Current password</label>
		<input class="form-control"type="password" name="current_password">
	</div>
	
	<div class="form-group">
		<label>New Password</label>
		<input class="form-control"type="password" name="password">
	</div>
	
	<div class="form-group">
		<label>New Password Again</label>
		<input class="form-control"type="password" name="password_again"><br />
	</div>
	
	<input class="btn btn-default" type="submit" value="Submit">
</form>
	
<?php
}
include 'includes/overall/footer.php';
?>