<?php 
?>

<html>
<body>
	<p>Post ID: <?php echo $_POST["postid"]; ?></p>
	<?php 
		if(isset($_POST['category'])){
			if (is_array($_POST['category'])) {
			    foreach($_POST['category'] as $value){
				 	echo '<p>Category:'.$value.'</p>';
			    }
			} else {
			    $value = $_POST['category'];
			    echo '<p>Category:'.$value.'</p>';
			}
		}
	?>
	
	<p>Name: <?php echo $_POST["name"]; ?></p>
	<p>Description: <?php echo $_POST["description"]; ?></p>
	<p>Address: <?php echo $_POST["address"]; ?></p>
	<p>Region: <?php echo $_POST["region"]; ?></p>
	<p>Country: <?php echo $_POST["country"]; ?></p>
	<p>Phone: <?php echo $_POST["phone"]; ?></p>
	<p>Website: <?php echo $_POST["website"]; ?></p>
	<p>Pax: <?php echo $_POST["pax"]; ?></p>
	<p>Price: <?php echo $_POST["price"]; ?></p>
</body>
</html>