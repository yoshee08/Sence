<?php 
include 'core/init.php';

$_POST['ip'] = htmlspecialchars($_POST['ip'], ENT_QUOTES);
$_POST['port'] = (int)$_POST['port'];

//Server checks
$Query = new MinecraftQuery($_POST['ip'], $_POST['port'], 3);

//Output
if($Query->getStatus() == true) echo '<span class="label label-success">Online</span>';
else echo '<span class="label label-danger">Offline</span>';
?>