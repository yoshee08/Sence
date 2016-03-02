<html>
<head>
<meta charset="UTF-8">
</head>
<body>


<?php
require __DIR__ . '/MinecraftPing.php';
require __DIR__ . '/MinecraftPingException.php';
require __DIR__ . '/MinecraftColors.php';
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
try
        {
        $Query = new MinecraftPing('mc.yoshee08.com', 25565 );
        $info = $Query->Query();
        echo MinecraftColors::convertToHTML($info['description'], true);
        var_dump($info);
        }
        catch( MinecraftPingException $e )
        {
        echo $e->getMessage();
        }
        finally;
        {
        $Query->Close();
        }
?>