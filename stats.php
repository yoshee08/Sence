<?php 
include 'core/init.php';
#protect_page();
#admin_page();
include 'includes/overall/header.php';
?>
<h2>Website Stats</h2>
<div class="row">
	<div class="col-md-6">

		<h4>User Stats</h4>

		<table class="table-fixed-full table-statistics">

			<tr>
				<td style="width:50%">Site Admins</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `type` = '1'"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Regular Users</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `type` = ''"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Confirmed users</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = '1'"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Unconfirmed Users</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = '0'"), 0); ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%">Users online</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`username`) FROM `users` WHERE `last_activity` > unix_timestamp() - 30 "), 0); ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%">Guests online</td>
				<td style="width:50%"><kbd><?php echo online_guests(); ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%">Total Users</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users`"), 0); ?></kbd></td>
			</tr>
    
		</table>

	</div>

	<div class="col-md-6">

		<h4>Server statistics</h4>

		<table class="table-fixed-full table-statistics">
			<tr>
				<td style="width:50%">New servers today</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) AS `count` FROM `servers` WHERE YEAR(`date_added`) = YEAR(CURDATE()) AND MONTH(`date_added`) = MONTH(CURDATE()) AND DAY(`date_added`) = DAY(CURDATE())"), 0); ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%">Online servers</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) AS `count` FROM `servers` WHERE `status` = '1'"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Offline servers</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) AS `count` FROM `servers` WHERE `status` = '0'"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Active servers</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `disabled` = '0'"), 0); ?></kbd></td>
			</tr>
		</table>

	</div>
    <div class="col-md-6">

		<h4>Site Stats</h4>

		<table class="table-fixed-full table-statistics">

			<tr>
				<td style="width:50%">VIP servers</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `vip` = '1'"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Servers with Votifier</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `votifier_key` <> 'false'"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Total Comments</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `comments`"), 0); ?></kbd></td>
			</tr>

			<tr>
				<td style="width:50%">Total Votes</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `votes`"), 0); ?></kbd></td>
			</tr>
			<tr>
				<td style="width:50%">Total Servers</td>
				<td style="width:50%"><kbd><?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `servers`"), 0); ?></kbd></td>
			</tr>
		</table>

	</div>
</div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      /*google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
	  ['Data', 'Count'],
	  ['Active Users',      <?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = '1'"), 0); ?>],
	  ['Disabled Users',    <?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = '0'"), 0); ?>],
	  ['Admins',  			<?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `type` = '1'"), 0); ?>]
	]);

        var options = {
	  title: 'User statistics',
	  backgroundColor: '#fff'
        };

        var chart = new google.visualization.PieChart(document.getElementById('users_stats'));

        chart.draw(data, options);
      }*/
    </script>


<div id="users_statsNOTINUSE" style="/*width: 900px; height: 500px;*/"></div>

  <script type="text/javascript">
    /*google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data2 = google.visualization.arrayToDataTable([
	  ['Data', 'Count'],
	  ['Active Servers',     <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `disabled` = '0'"), 0); ?>],
	  ['Disabled Servers',   <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `disabled` = '1'"), 0); ?>],
	  ['VIP Servers',  		 <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `vip` = '1'"), 0); ?>],
	  ['Servers with votifier', <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `votifier_key` <> 'false'"), 0); ?>]
	]);


        var options2 = {
	      title: 'Servers statistics',
	      backgroundColor: '#fff'
          curveType: 'function',
          legend: { position: 'bottom' }
        };

      var chart2 = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart2.draw(data2, options2);
  }*/
  </script>
<div id="columnchart_values_NOTINUSE" style="/*width: 900px; height: 300px;*/"></div>

<div class="clearfix"></div>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data3 = google.visualization.arrayToDataTable([
	  ['Type', 'Data' ],
	  ['Total Users',  	 <?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users`"), 0); ?>],
	  ['Total Servers',  <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers`"), 0); ?>],
	  ['Total Comments', <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `comments`"), 0); ?>],
	  ['Total Votes',    <?php echo mysql_result(mysql_query("SELECT COUNT(`ip`) FROM `votes`"), 0); ?>],
	  ['Users Online',   <?php echo mysql_result(mysql_query("SELECT COUNT(`username`) FROM `users` WHERE `last_activity` > unix_timestamp() - 30 "), 0); ?>],
	  ['Guests Online',  <?php echo online_guests(); ?>]
	]);

	var options3 = {
	  title: 'Company Performance',
	  backgroundColor : '#fff'
	};

	var chart3 = new google.visualization.ColumnChart(document.getElementById('Company_stats'));
	chart3.draw(data3, options3);
  }
</script>
<div id="Company_stats" style="width: 900px; height: 500px;"></div>
<?php include 'includes/overall/footer.php'; ?>