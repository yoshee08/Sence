<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 

echo output_success("All server votes were set to 0 and all vote logs were deleted !");

mysql_query("DELETE FROM `votes`");
mysql_query("UPDATE `servers` SET `votes` = 0");


include 'includes/overall/footer.php'; 
?>