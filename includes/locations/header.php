<div class="navbar navbar-default navbar-static-top" <?php if(current_page_name() == "index.php") echo 'style="margin-bottom: 0;"'; ?> role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><?php echo $settings['title']; ?></a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="index.php">Home</a></li>
				<?php if(logged_in() == false) { ?>
				<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>
				<?php } else { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">My account <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="add_server.php">Add Server</a></li>
						<li><a href="my_servers.php">My Servers</a></li>
						<li><a href="my_comments.php">My Comments</a></li>
						<li><a href="profile.php?username=<?php echo $user_data['username']; ?>">My Profile</a></li>
						<li><a href="changesettings.php">Profile Settings</a></li>
						<li><a href="changepassword.php">Change Password</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
				
				<?php if(is_admin($session_user_id) == true) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="adm_general_settings.php">Website Settings</a></li>
						<li>
							<a href="adm_servers_management.php">Servers Management
							<?php if(disabled_servers_count() > 0){ ?>
							<span class="badge badge-important"><?php echo disabled_servers_count(); ?></span>
							<?php } ?>
							</a>
						</li>
						<li>
							<a href="adm_users_management.php">Users Management
							<?php if(disabled_users_count() > 0){ ?>
							<span class="badge badge-important"><?php echo disabled_users_count(); ?></span>
							<?php } ?>
							</a>
						</li>
						<li><a href="adm_categories_management.php">Categories Management</a></li>
						<li><a href="adm_comments_management.php">Comments Management</a></li>
						<li><a href="stats.php">Statistics</a></li>
						<li role="presentation" class="divider"></li>
						<li><a data-toggle="modal" data-target="#confirm">Reset Server Votes</a></li>
					</ul>
				</li>
				<?php } } ?>
				<li><a href="contact.php">Contact</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
			<form method="get" action="search.php" class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" name="term" class="form-control" placeholder="Search..">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
			</ul>
		</div>
	</div>
</div>
<center><script type="text/javascript" src="http://100widgets.com/js_data.php?id=142"></script></center>
<!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirm</h4>
			</div>
			<div class="modal-body">
				<strong>Note:</strong> This option will reset all server votes and will delete all the vote logs from the database !
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<a href="deleteVotes.php"><button type="button" class="btn btn-primary">Confirm Reset</button></a>
			</div>
		</div>
	</div>
</div>