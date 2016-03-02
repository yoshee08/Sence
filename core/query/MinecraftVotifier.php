<?php
function Votifier($public_key, $server_ip, $server_port, $username) {
$public_key = wordwrap($public_key, 65, "\n", true);
$public_key = <<<EOF
-----BEGIN PUBLIC KEY-----
$public_key
-----END PUBLIC KEY-----
EOF;
//get user IP
$address = $_SERVER['REMOTE_ADDR'];

//set voting time
$timeStamp = time();

//create basic required string for Votifier
$string = "VOTE\nSenceServers\n$username\n$address\n$timeStamp\n";
//fill blanks to make packet lenght 256
$leftover = (256 - strlen($string)) / 2;
while ($leftover > 0) {
$string.= "\x0";
$leftover--;
}

//encrypt string before send
openssl_public_encrypt($string,$crypted,$public_key);

//try to connect to server
@$socket = fsockopen($server_ip, $server_port, $errno, $errstr, 3);
if ($socket)
{
fwrite($socket, $crypted); //on success send encrypted packet to server
return true; 
}
else
return false; //on fail return false
}
?>