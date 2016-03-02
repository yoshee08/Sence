<?php
include 'inc/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<title>SenceServers » Banners</title>
<?php 
include '../core/init.php';
include '../includes/overall/header.php'; 
?>
<body>
<div class="container">

<div class="contt">



<div class="panel panel-default">

<div class="panel-body">
<center><a href="https://senceservers.net/shop/gfx.php" target="_blank" >
<img src="https://senceservers.net/CustomBanner.png"></a></center>
<br>
<?php


$ip = $port = $name = $pstatus = $bstyle = null;

if(!empty($_POST)) {
	/* Define some variables */
    $ip = filter_var($_POST['serverip'], FILTER_SANITIZE_STRING);
    $port = (int) $_POST['serverport'];
    $name = filter_var($_POST['servername'], FILTER_SANITIZE_STRING);
    $pstatus = filter_var($_POST['pstatus'], FILTER_SANITIZE_STRING);
    $bstyle = filter_var($_POST['bstyle'], FILTER_SANITIZE_STRING);
	/* Create base url to banner */
    $org = ''.$baseurl.'/bannermaker/banner.php?ip='.$ip.'&port='.$port.'&name='.$name.'&bstyle='.$bstyle.'&p='.$pstatus.'';

    /* Check server name */
    if(strlen($name) > 32 || strlen($name) < 3) {
    echo'<div class="alert alert-danger">The server name must be between 3 -32 symbol</div>';}
    /* Checks to make sure everything is entered */
    else if (empty($_POST["serverip"])) {
    echo'<div class="alert alert-danger">Please enter a server IP!</div>';}
    else if (empty($_POST['serverport'])) {
    echo'<div class="alert alert-danger">Please enter a server Port!</div>';}
    else if (empty($_POST['pstatus'])) {
    echo'<div class="alert alert-danger">Please select the Show Players, yes or no!</div>';}
    else if (empty($_POST['bstyle'])) {
    echo'<div class="alert alert-danger">Please select the Banner Style!</div>';}
    else if((!$response = $status->getStatus($_POST["serverip"], '1.7.*', $_POST["serverport"]))) {
    echo'<div class="alert alert-danger">Server is offline!</div>';
} else {	
    /* Add the server to the database */
    $stmt = $mysqli->prepare("INSERT INTO `banners` (`ip`, `port`, `name`) VALUES (?, ?, ?)");
    $stmt->bind_param('sss',  $ip, $port, $name);
    $test = $stmt->execute();
    $stmt->close();

?>

<div class="#">
<div class="panel-heading">Generated banner for <b><?php echo $name; ?></b></div>
<div class="panel-body">
<center><img src="<?php echo $org; ?>"></img></center>
<!--<br>-->
<p>Direct Image URL:
 <input type="text" class="form-control " size="75" value="<?php echo $org; ?>" disabled />
</p>
<p>BB Code:
 <input type="text" class="form-control " size="75" value="[IMG]<?php echo $org ?>[/IMG]" disabled />
</p>
<p>HTML Code:
<input type="text" class="form-control " size="75" value='<img src="<?php echo $org ?>"></img>' disabled />				
</p>
</div>
</div>
<hr>
<?php
}
}
?>
<form class="form-horizontal" role="form" method="post">

<div class="form-group">
<label for="inputUsername" class="col-sm-3 control-label" >Server Name</label>
<div class="col-sm-offset-1 col-sm-5">
<input value="<?php echo $name; ?>" type="text" class="form-control" id="servername" placeholder="Ex. Server Name" name="servername">
</div>
</div>

<div class="form-group">
<label for="inputUsername" class="col-sm-3 control-label">Server IP</label>
<div class="col-sm-offset-1 col-sm-5">
<input value="<?php echo $ip; ?>" type="text" class="form-control" id="serverip" placeholder="Server IP (Required)" name="serverip">
</div>
</div>

<div class="form-group">
<label for="inputUsername" class="col-sm-3 control-label">Server Port</label>
<div class="col-sm-offset-1 col-sm-5">
<input value="<?php echo $port; ?>" type="text" class="form-control" placeholder="25565" name="serverport">
</div>
</div>
<div class="form-group">
<label  class="col-sm-3 control-label">Show Players</label>
<div class="col-sm-offset-1 col-sm-5">
<div class="btn-group" data-toggle="buttons">
<label class="btn btn-default btn-success btn-xs <?php if($pstatus == 'yes') { echo 'active';}?>">
<input type="radio" name="pstatus" value="yes" <?php if($pstatus == 'yes') {  echo ' checked="checked"';}?>/> Yes
</label> 
<label class="btn btn-default btn-xs <?php if($pstatus == 'no') { echo 'active';}?>">
<input type="radio" name="pstatus" value="no"  <?php if($pstatus == 'no') {  echo ' checked="checked"';}?> /> No
</label> 
</div>

</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label">Banner Style</label>
<div class="col-sm-offset-1 col-sm-5">
<img style="width: 300px;" src="bg/bg1.jpg">
<input type="radio" name="bstyle" value="bg1" <?php if($bstyle == 'bg1'){  echo ' checked="checked"';}?>>
<!--</br><br>-->
<img style="width: 300px;" src="bg/bg2.jpg">
<input type="radio" name="bstyle" value="bg2" <?php if($bstyle == 'bg2'){  echo ' checked="checked"';}?>>
<!--<br></br>-->
<img style="width: 300px;" src="bg/bg3.jpg">
<input type="radio" name="bstyle" value="bg3" <?php if($bstyle == 'bg3'){  echo ' checked="checked"';}?>>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-3">
    <button type="submit" name="submit"class="btn btn-default">Generate banner</button>
</div>
</div>
<!--<br>-->
<span style="font-size:11px;color:#161f22;" class="text-muted"></span>
<b>
<?php
//total banners
mysqli_query($mysqli,"SELECT * FROM banners");
print mysqli_affected_rows($mysqli);
$mysqli->close();
?>
</b>

</form>
</div>
</div>
</div>
</div>
<?php
include '../includes/overall/footer.php'; 
?>
</body>
</html>
