<?php
$database = array();
$database['host']     = 'I'm not telling;
$database['user']     = 'This might be a little wrong..';
$database['password'] = 'This is totally the password';
$database['table']    = 'Gotchya';

$connect_error = 'Sorry, there are some connection problems.';
mysql_connect($database['host'], $database['user'], $database['password']) or die($connect_error);
mysql_select_db($database['table']) or die($connect_error);

?>
