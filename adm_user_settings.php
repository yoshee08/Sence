<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 

if(isset($_GET['update']) && !empty($_GET['update'])){

	$user_id = (INT)$_GET['update'];
	$data = user_data($user_id, 'user_id', 'name', 'username', 'password', 'email', 'type');

} elseif(!isset($_GET['update']) && empty($_GET['update'])) {
	header('Location: adm_users_management.php');
}

if(empty($_POST) == false){
	$_POST['username'] = ($data['username'] !== htmlspecialchars($_POST['username'], ENT_QUOTES)) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : false;
	$_POST['email']    = ($data['email'] !== htmlspecialchars($_POST['email'], ENT_QUOTES)) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : false;
	$_POST['name']     = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$_POST['type']     = (INT)$_POST['type'];
	$_POST['password'] = (!empty($_POST['password'])) ? $_POST['password'] : false;
	
	if(empty($errors) == true) {
		if($_POST['username'] !== false){
			if(user_exists($_POST['username']) === true) {
				$errors[] = 'Sorry, the username \'' . $_POST['username'] . '\' is already taken.';
			}
		}
		if(preg_match("/\\s/", $_POST['username']) == true) {
			$errors[] = 'Username field must not contain any spaces';
		}
		if($_POST['password'] !== false){
			if(strlen($_POST['password']) < 6) {
				$errors[] = 'Password too short, it must be at least 6 characters!';
			}
		}
		if($_POST['email'] !== false){
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'A valid email adress is required';
			}
			if(email_exists($_POST['email']) === true) {
				$errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use';
			}
		}
	}
}

?>		
<h2 style="text-transform: capitalize;"><?php echo $data['username']; ?>'s settings</h2>
<?php
if(empty($_POST) == false && empty($errors) == true){
	
	$update_data = array(
		'name'         => $_POST['name'],
		'type'		   => $_POST['type']
	);
	if($_POST['password'] !== false){
		$update_data['password'] = c_hash($data['username'], $_POST['password']);
	}
	elseif($_POST['username'] !== false){
		$update_data['username'] = $_POST['username'];
	}elseif($_POST['email'] !== false){
		$update_data['email'] = $_POST['email'];
	}
	
	
	update_user($_GET['update'], $update_data);
	header('Location: adm_users_management.php?update');
	exit();
} elseif(empty($errors) == false) {
	echo output_errors($errors);
}

	
?>
<form action="" method="post" role="form">
	<div class="form-group">
		<label>Username</label>
		<input class="form-control" type="text" name="username" value="<?php echo $data['username']; ?>"/>
	</div>
	
	<div class="form-group">
		<label>Email</label>
		<input class="form-control" type="text" name="email" value="<?php echo $data['email']; ?>"/>
	</div>
	
	<div class="form-group">
		<label>Name</label>
		<input class="form-control" type="text" name="name" value="<?php echo $data['name']; ?>"/>
	</div>
	
	<div class="form-group">
		<label>Type(0 = Normal user || 1 = Admin)</label>
		<input class="form-control" type="text" name="type" value="<?php echo $data['type']; ?>"/>
	</div>
	
	<div class="form-group">
		<label>New Password(leave blank if you dont want to change it)</label>
		<input class="form-control" type="password" name="password" /><br />
	</div>
	
	<input class="btn btn-default" type="submit" value="Submit"/>
</form>
<?php
include 'includes/overall/footer.php'; 
?>