    <?php
	
    $Ad[0] = '<a href="https://senceservers.net/server.php?id=72" id="link1"><img src="https://senceservers.net/banners/5c30aefde924ae16b90aafd5538618b8.gif" width="468" height="60" alt="mc.yoshee08.com" border="0" id="link_1" /></a>';
    $Ad[1] = '<a href="https://senceservers.net/shop/advertisements.php" id="link2"><img src="https://senceservers.net/includes/overall/youradhere.png" width="468" height="60" alt="youradhere" border="0" id="link_2" /></a>';
    $Ad[2] = '<a href="https://senceservers.net/server.php?id=122" id="link3"><img src="https://senceservers.net/banners/44d0c6a1ab91ee395a530e50eb84911a.gif" width="468" height="60" alt="link 3" border="0" id="link_3" /></a>';
    $Ad[3] = '<a href="https://senceservers.net/shop/advertisements.php" id="link4"><img src="https://senceservers.net/includes/overall/youradhere.png" width="468" height="60" alt="youradhere" border="0" id="link_4" /></a>';
    $Ad[4] = '<a href="https://senceservers.net/server.php?id=105" id="link5"><img src="https://senceservers.net/banners/ad220cb6dd7d7c8fea7502365162219a.gif" width="468" height="60" alt="play.ejmc.us" border="0" id="advertisment_5" /></a>';
    $Ad[5] = '<a href="https://senceservers.net/server.php?id=149" id="link6"><img src="https://senceservers.net/banners/7f6a3fc20f4202684799798fd964c0d8.gif" width="468" height="60" alt="youradhere" border="12" id="ReversePlasma" /></a>';
        

    $Weight[0]=1;
    $Weight[1]=1;
    $Weight[2]=1;
    $Weight[3]=1;
    $Weight[4]=1;
    $Weight[5]=4;
    $sum =0;
    for($i=0;$i<count($Weight);$i++)
    	$sum+=$Weight[$i];
    $ShowAd = rand(0, $sum - 1);
    for($i=0;$i<count($Weight);$i++)
    {
    	if($ShowAd<=$Weight[$i])
    	{
    		$ShowAd=$i;
    		break;
    	}
    	else
    		$ShowAd-=$Weight[$i];
    }
    echo $Ad[$ShowAd];
    ?>