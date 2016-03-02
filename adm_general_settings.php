<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 
?>

<h2>Website Settings</h2>
<?php
if(empty($_POST) == false) {
	
	if(empty($_POST['title']) == true){
		$errors[] = "The title cannot be empty !";
	}
	if(!filter_var($_POST['url'], FILTER_VALIDATE_URL)){
		$errors[] = "URL is not valid";
	}	
	if(strlen(urlencode($_POST['advertise_top'])) > 2550){
		$errors[] = "You have reached the limit of characters on top ad spot ! (limit: 2555)";
	}
	if(strlen(urlencode($_POST['advertise_bottom'])) > 2550){
		$errors[] = "You have reached the limit of characters on bottom ad spot ! (limit: 2555)";
	}
	
	if(!empty($errors)){
		echo output_errors($errors);
	}else{
		$advertise_top        = (empty($_POST['advertise_top']) !== true) ? urlencode($_POST['advertise_top']) : "false";
		$advertise_bottom     = (empty($_POST['advertise_bottom']) !== true) ? urlencode($_POST['advertise_bottom']) : "false";
		$title                = htmlspecialchars($_POST['title'], ENT_QUOTES);
		$description		  = htmlspecialchars($_POST['description'], ENT_QUOTES);
		$pagination           = (INT)$_POST['pagination'];
		$register             = (isset($_POST['register']) == true) ? 1 : 0;
		$show_offline_servers = (isset($_POST['show_offline_servers']) == true) ? 1 : 0;
		$email_confirmation   = (isset($_POST['email_confirmation']) == true) ? 1 : 0;
		$server_confirmation  = (isset($_POST['server_confirmation']) == true) ? 1 : 0;
		$disable_query		  = (isset($_POST['disable_query'])) ? 1 : 0;
		$twitter			  = (empty($_POST['twitter']) !== true) ? $_POST['twitter'] : "false";
		$facebook			  = (empty($_POST['facebook']) !== true) ? $_POST['facebook'] : "false";
		$contact_email 		  = $_POST['contact_email'];
		$server_cache 		  = $_POST['server_cache'];
		$recaptcha_public     = $_POST['recaptcha_public'];
		$recaptcha_private    = $_POST['recaptcha_private'];
		$max_categories 	  = $_POST['max_categories'];
		$url				  = (substr($_POST['url'], -1) !== "/") ? $_POST['url']."/" : $_POST['url'];
		$news_message		  = htmlspecialchars($_POST['news_message'], ENT_QUOTES);

		mysql_query("UPDATE settings SET `title` = '$title', `description` = '$description', `disable_query` = '$disable_query', `max_categories` = '$max_categories', `url` = '$url', `news_message` = '$news_message', `facebook` = '$facebook', `twitter` = '$twitter', `contact_email` = '$contact_email', `pagination` = '$pagination', `register` = '$register',  `server_cache` = '$server_cache', `advertise_top` = '$advertise_top', `advertise_bottom` = '$advertise_bottom', `show_offline_servers` = '$show_offline_servers', `email_confirmation` = '$email_confirmation', `server_confirmation` = '$server_confirmation', `recaptcha_public` = '$recaptcha_public', `recaptcha_private` = '$recaptcha_private' WHERE `id` = 1");
		header('Location: adm_general_settings.php');
	}
}
?>

<ul class="nav nav-pills">
	<li class="active"><a href="#settings" data-toggle="tab">Main Settings</a></li>
	<li><a href="#optimization" data-toggle="tab">Optimization</a></li>
	<li><a href="#recaptcha" data-toggle="tab">Recaptcha Settings</a></li>
	<li><a href="#social" data-toggle="tab">Social Pages</a></li>
	<li><a href="#ads" data-toggle="tab">Ads</a></li>
</ul><br />

<form action="" method="post" role="form">
	<div class="tab-content">
			<div class="tab-pane active" id="settings">
				<div class="form-group">
					<label>Website Title</label>
					<input class="form-control" type="text" name="title" value="<?php echo $settings['title']; ?>"/>
				</div>

				<div class="form-group">
					<label>Description</label>
					<input class="form-control" type="text" name="description" value="<?php echo $settings['description']; ?>"/>
				</div>

				<div class="form-group">
					<label>Website URL</label>
					<input class="form-control" type="text" name="url" value="<?php echo $settings['url']; ?>"/>	
				</div>
				
				<div class="form-group">
					<label>News Message</label>
					<input class="form-control" type="text" name="news_message" value="<?php echo $settings['news_message']; ?>"/>	
				</div>
				
				<div class="form-group">
					<label>Owner Email(for contact form)</label>
					<input class="form-control" type="text" name="contact_email" value="<?php echo $settings['contact_email']; ?>" />
				</div>
				
				<div class="form-group">
					<label>Maximum Categories per Server</label>
					<input class="form-control" type="text" name="max_categories" value="<?php echo $settings['max_categories']; ?>" />
				</div>
				
				<label class="checkbox">
					Register (enabled / disabled)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="register" value=<?php echo "\"" . $settings['register'] . "\""; if($settings['register'] == 1) echo "checked";?>/>
				</label>
				<label class="checkbox">
					Users need to email confirmate their account?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="email_confirmation" value=<?php echo "\"" . $settings['email_confirmation'] . "\""; if($settings['email_confirmation'] == 1) echo "checked";?>/>
				</label>
				
				<label class="checkbox">
				Show Offline Servers?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="show_offline_servers" value=<?php echo "\"" . $settings['show_offline_servers'] . "\""; if($settings['show_offline_servers'] == 1) echo "checked";?>/>
				</label>
				
				<label class="checkbox">
					Admins need to manual activate new servers?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="server_confirmation" value=<?php echo "\"" . $settings['server_confirmation'] . "\""; if($settings['server_confirmation'] == 1) echo "checked";?>/>
				</label>
			</div>
			
			
			<div class="tab-pane" id="optimization">
				<div class="form-group">
					<label>Pagination(Showing X servers / page)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="form-control" type="text" name="pagination" value="<?php echo $settings['pagination']; ?>"/>
				</div>
				
				<div class="form-group">
					<label>Servers Cache(in seconds)</label>
					<input class="form-control" type="text" name="server_cache" value="<?php echo $settings['server_cache']; ?>"/>
				</div>
				
				<label class="checkbox">
					Disable Querying on the home page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="disable_query" value=<?php echo "\"" . $settings['register'] . "\""; if($settings['disable_query'] == 1) echo "checked";?>/>
				</label>
			</div>
			
			
			<div class="tab-pane" id="recaptcha">
				<div class="form-group">
					<label>ReCaptcha Public Key</label>
					<input class="form-control" type="text" name="recaptcha_public" value="<?php echo $settings['recaptcha_public']; ?>"/>
				</div>
				
				<div class="form-group">
					<label>ReCaptcha Private Key</label>
					<input class="form-control" type="text" name="recaptcha_private" value="<?php echo $settings['recaptcha_private']; ?>"/>
				</div>
			</div>
			
			
			<div class="tab-pane" id="social">
				<div class="form-group">
					<label>Facebook</label>
					<input class="form-control" type="text" name="facebook" value="<?php echo $settings['facebook']; ?>" placeholder="Facebook username"/>
				</div>
				
				<div class="form-group">
					<label>Twitter</label>
					<input class="form-control" type="text" name="twitter" value="<?php echo $settings['twitter']; ?>" placeholder="Twitter username"/>
				</div>
			</div>

			
			<div class="tab-pane" id="ads">
				<h3>Top Ads</h3>
				<textarea class="form-control" style="width:95%;height:200px;" name="advertise_top" ><?php echo urldecode($settings['advertise_top']);?></textarea>
			
				<h3>Bottom Ads</h3>
				<textarea class="form-control" style="width:95%;height:200px;" name="advertise_bottom" ><?php echo urldecode($settings['advertise_bottom']);?></textarea>
			</div>
	</div>
	
	<br /><input class="btn btn-default" type="submit" value="Change Settings">
</form>

<?php
include 'includes/overall/footer.php'; 
?>