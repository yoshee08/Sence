<?php 
include 'core/init.php';
protect_page();
admin_page();
include 'includes/overall/header.php'; 



if(isset($_GET['delete']) && !empty($_GET['delete'])){
	
	$category_id = (INT)$_GET['delete'];
	if($category_id == "1"){ 
		$errors[] = "You can't delete the SURVIVAL category"; 
		echo output_errors($errors); 
	} else {
		mysql_query("DELETE FROM `categories` WHERE `category_id` = '$category_id'");
		mysql_query("UPDATE `servers` SET `category_id` = '1' WHERE `category_id` = '$category_id'");
		header('Location: adm_categories_management.php?successDelete');
	}
	
} else {

echo "<h2>Categories Management &nbsp; <span class='label label-success' style='cursor:pointer;' onclick=\"$('#add_category').toggle('slow');\"><i class='icon-plus icon-white'></i> Add category </span></h2>";

if(isset($_GET['name'])) {
	$name = htmlspecialchars($_GET['name'], ENT_QUOTES);
	mysql_query("INSERT INTO `categories` (`name`) VALUES ('$name')");
	echo output_success("Category successfully added!");
}
if(isset($_GET['successDelete'])) echo output_success("Category was successfully deleted!");

?>

<div id="add_category" style="display:none;">
<form action="" method="get" role="form" class="form-inline">
	<div class="form-group">
		<input class="form-control" type="text" name="name" placeholder="Name.." />
	</div>
	
	<button type="submit" class="btn btn-default">Submit</button>
</form>
</div><br />

<div class="table-responsive">
<table class="table table-bordered" style="background:white;">
	<thead>
		<tr>
			<th>Name</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$result = mysql_query("SELECT * FROM `categories` ORDER BY `category_id` DESC");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		?>
		<tr>
			<td><?php echo $row['name']; ?></td>
			<td>
				<a href="adm_categories_management.php?delete=<?php echo $row['category_id']; ?>">
					<span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Delete</span>
				</a>
			</td>
		</tr>
		<?php }	?>
	</tbody>
</table>
</div>

<?php } include 'includes/overall/footer.php'; ?>