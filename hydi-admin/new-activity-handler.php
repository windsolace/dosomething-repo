<?php 
/**
* New Activity Form Handler
* - Get form inputs
* - Clean input
* - Write to database
*/

/*========================================
Form Fields:
(*) - mandatory
- Post ID(*)	//textfield
- Category 		//checkbox
- Name(*)		//textfield	
- Description 	//textarea
- Address(*) 	//textarea
- Region 		//dropdown
- Country 		//dropdown
- Phone Number 	//textfield
- Website 		//textfield
- Pax 			//2 dropdown
- Average price //textfield
- Opening hours  //2 textfield
========================================*/

require_once("../../../wp-config.php");

define("TABLE_HYDI_ACTIVITY", "activity");

main();

function main(){
	//processInputs();
	getPostData();
	writeToDatabase();
}

function getPostData(){
	$postid = processInputs($_POST["postid"]);
	$category = processInputs($_POST["category"]);
	$name = processInputs($_POST["name"]);
	$description = processInputs($_POST["description"]);
	$address = processInputs($_POST["address"]);
	$region = processInputs($_POST["region"]);
	$country = processInputs($_POST["country"]);
	$phone = processInputs($_POST["phone"]);
	$website = processInputs($_POST["website"]);
	$pax1 = processInputs($_POST["pax1"]);
	$pax2 = processInputs($_POST["pax2"]);
	$price = processInputs($_POST["price"]);

	//new json object
	$jsonPostData = new stdClass();
	$jsonPostData->postid = $postid;
	$jsonPostData->category = $category;
	$jsonPostData->name = $name;
	$jsonPostData->description = $description;
	$jsonPostData->address = $address;
	$jsonPostData->region = $region;
	$jsonPostData->country = $country;
	$jsonPostData->phone = $phone;
	$jsonPostData->website = $website;
	$jsonPostData->pax = array(
		"minPax" => $pax1,
		"maxPax" => $pax2
		);
	$jsonPostData->price = $price;

	$jsonPostData = json_encode($jsonPostData);
	return $jsonPostData;
}

function processInputs($value){
	return $value;
}

function writeToDatabase(){
	global $wpdb;

	//write inputs to activity table
	$wpdb->insert(
		TABLE_HYDI_ACTIVITY,
		array(
			'object_id'			=> $postid,
			'name'				=> $name,
			'description'		=> $description,
			'address' 			=> $address,
			//'longitude' 		=> 0,
			//'latitude' 			=> 0,
			'region' 			=> $region,
			'country' 			=> $country,
			'phone' 			=> $phone,
			'website' 			=> $website,
			'min_pax' 			=> $pax1,
			'max_pax' 			=> $pax2,
			'average_price' 	=> $price,
			//'time_range' 		=> 0,
			//'submitted_date' 	=> 0,
			'approval_id' 	=> null
		)
	);

	
	/*
	$userReviews = $wpdb->get_results("
		SELECT name FROM ".TABLE_HYDI_ACTIVITY." WHERE object_id = '95'
	");
	foreach ( $userReviews as $value ){
		echo $value->name;
	}
	*/
}



?>

<html>
	<body>
		<p>Post ID: <?php echo $_POST["postid"]; ?></p>
		<?php 
			if(isset($_POST['category'])){
				if (is_array($_POST['category'])) {
					$categoryStr = "";
				    foreach($_POST['category'] as $value){
					 	$categoryStr = $categoryStr.$value.",";
				    }
				    echo '<p>Category: '.$categoryStr;
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
		<p>Pax: <?php echo $_POST["pax1"]."-".$_POST["pax2"]; ?></p>
		<p>Price: <?php echo $_POST["price"]; ?></p>
	</body>
</html>