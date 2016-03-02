<?php 
	include 'core/init.php';
	include 'includes/overall/header.php'; 
	protect_page();
$email   = mysql_result(mysql_query("SELECT email, username FROM `users`"), 0);
?>
<?php

$subject = ‘SenceServers update!’;
$message = “Dear server owner, \n\n——–SenceServers would like to inform you that our bidding system, and bump system are just one of the many new features we've worked hard to bring you over the last few weeks. If you're server is currently not listed with us, feel free to add it at any time. Don't forget to encourage your players to vote with vote rewards to get a higher rank! We fully support votifier, and if you have any questions, or need any help, message one of our live chat agents. ——\n\n Best Regards,\n\n——DeathCaptain97—–\n \n\nhttps://www.senceservers.net”;
$message .= “(SenceServers)”;
$headers = ‘From: Senceservers list <senceservers@gmail.com>’ . “\r\n” .
‘Reply-To: senceservers@gmail.com’ . “\r\n” .
‘X-Mailer: PHP/’ . phpversion();

$result = mysql_query(“SELECT email FROM users”);

if(mysql_num_rows($result) > 0)
{
$count = 0;
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC))
{

$to = $row['email'];
mail($to, $subject, $message, $headers);
$count++;
}
echo “myResult=$count Emails Sent. Done.”;
}
else
{
echo “myResult=Email Submissions Failed.”;
}

?>