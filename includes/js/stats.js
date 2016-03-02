<!--First Line Of JS-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
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

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
  }
</script>
<div id="chart_div" class="pull-left" style="width: 470px; height: 270px;"></div>


<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Data', 'Count'],
	  ['Active Servers',     <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `disabled` = '0'"), 0); ?>],
	  ['Disabled Servers',   <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `disabled` = '1'"), 0); ?>],
	  ['VIP Servers',  		 <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `vip` = '1'"), 0); ?>],
	  ['Servers with votifier', <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers` WHERE `votifier_key` <> 'false'"), 0); ?>]
	]);

	var options = {
	  title: 'Servers statistics',
	  backgroundColor: '#fff'
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
	chart.draw(data, options);
  }
</script>
<!--First line of JS-->
<!--Second Line of JS-->
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Type', 'Data' ],
	  ['Total Users',  	 <?php echo mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users`"), 0); ?>],
	  ['Total Servers',  <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `servers`"), 0); ?>],
	  ['Total Comments', <?php echo mysql_result(mysql_query("SELECT COUNT(`id`) FROM `comments`"), 0); ?>],
	  ['Total Votes',    <?php echo mysql_result(mysql_query("SELECT COUNT(`ip`) FROM `votes`"), 0); ?>],
	  ['Users Online',   <?php echo mysql_result(mysql_query("SELECT COUNT(`username`) FROM `users` WHERE `last_activity` > unix_timestamp() - 30 "), 0); ?>],
	  ['Guests Online',  <?php echo online_guests(); ?>]
	]);

	var options = {
	  title: 'Company Performance',
	  backgroundColor : '#fff'
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
	chart.draw(data, options);
  }
</script>
<!--Second  Line Of Js Here-->