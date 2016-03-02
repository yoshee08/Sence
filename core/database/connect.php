<?php
$database = array();
$database['host']     = 'localhost';
$database['user']     = 'root';
$database['password'] = 'NunyaOreoDad2007';
$database['table']    = 'root';

$connect_error = 'Sorry, there are some connection problems.';
mysql_connect($database['host'], $database['user'], $database['password']) or die($connect_error);
mysql_select_db($database['table']) or die($connect_error);

?>