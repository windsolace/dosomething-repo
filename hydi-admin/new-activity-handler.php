<?php 
/**
* New Activity Form Handler
* - Get form inputs
* - Clean input
* - Write to database
*/

/*==============================================/
Contents
Form Fields
DevID-01. Main
DevID-02. getPostData()
DevID-03. processInputs($value)
DevID-04. writeToDatabase($formData,$tableName)
DevID-05. createPage($pagename)
===============================================*/

/*========================================
Form Fields:
(*) - mandatory
- Post ID(*)	//textfield
- Category 		//checkbox
- Name(*)		//textfield	
- Description 	//textarea
- Address(*) 	//textarea
- longitude 	//textfield
- latitude 		//textfield
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
define("TABLE_ACTIVITY_CATEGORY", "activity_category");
define("TABLE_HYDI_PENDING_ACTIVITY", "pending_activity");

main();


/**
* DevID-01
*/
function main(){
	$formData = getPostData();
	writeToDatabase($formData, TABLE_HYDI_ACTIVITY);
}

/**
* DevID-02
* Get POST data from "Add activity form"
* @return JSON jsonPostData
*/
function getPostData(){
	
	$postid = processInputs($_POST["postid"], "");
	$category = "";
	$timeRange = "";
	//concat categories, timeRange
	if(isset($_POST['category'])){
		if (is_array($_POST['category'])) {
			foreach($_POST['category'] as $value){
				if(strlen($category) > 0)
					$category = $category.",".$value;
				else $category = $category.$value;
			}
		} else {
			$category = $_POST['category'];
		}
	}
	foreach($_POST as $key => $value){
		if($key == "category"){
			$value = processInputs($value, "");
			
			
			
		}
		
		if($key == "fromTime"){
			$fromTime = $value;
		} 
		if($key == "toTime"){
			$toTime = $value;
		}
	}
	
	$timeRange = $fromTime." - ".$toTime;
	$name = processInputs($_POST["name"], "");
	$description = processInputs($_POST["description"], "");
	$address = processInputs($_POST["address"], "");
	$longitude = processInputs($_POST["longitude"], "");
	$latitude = processInputs($_POST["latitude"], "");
	$region = processInputs($_POST["region"], "");
	$country = processInputs($_POST["country"], "");
	$phone = processInputs($_POST["phone"], "phone");
	$website = processInputs($_POST["website"], "");
	$pax1 = processInputs($_POST["pax1"], "");
	$pax2 = processInputs($_POST["pax2"], "");

	//CONDITION: pax1 < pax2
	//Make pax2 = pax1 assuming max1 is the most accurate
	if($pax1 > $pax2){
		$pax2 = $pax1;
	}
	
	$price = processInputs($_POST["price"], "");

	//create a new page and return postid
	$postid = createPage($name);
	echo "Page created with Post ID: ".$postid;
	$posttemplateoption = get_option('newtemplateid');
	if ( $postid && ! is_wp_error( $postid ) ){
        update_post_meta( $postid, '_wp_page_template', 'page-templates/activitydetail.php');
    }

	//new json object
	$jsonPostData = new stdClass();
	$jsonPostData->postid = $postid;
	$jsonPostData->category = $category;
	$jsonPostData->name = $name;
	$jsonPostData->description = $description;
	$jsonPostData->location = array(
			"address" 	=> $address,
			"longitude" => $longitude,
			"latitude" 	=> $latitude,
			"region" 	=> $region
		);
	$jsonPostData->country = $country;
	$jsonPostData->phone = $phone;
	$jsonPostData->website = $website;
	$jsonPostData->pax = array(
		"minPax" => $pax1,
		"maxPax" => $pax2
		);
	$jsonPostData->price = $price;
	$jsonPostData->timeRange = $timeRange;

	$jsonPostData = json_encode($jsonPostData);
	return $jsonPostData;
}

/**
* DevID-03: WIP
* Processes form input data
* @param value
* @param type - the type of input
* @return value
*
* - areacode + phone
*/
function processInputs($value, $type){
	if($type == "phone"){

	}
	return $value;
}

/**
* DevID-04
* Do a INSERT into database using processed form data
* @param JSON formData
* @param String tablename
*/
function writeToDatabase(/*json*/ $formData, $tableName){
	global $wpdb;

	$jsonFormData = json_decode($formData, true);

	//write inputs to activity table
	$wpdb->insert(
		$tableName,
		array(
			'object_id'			=> $jsonFormData['postid'],
			'name'				=> $jsonFormData['name'],
			//'category'			=> $jsonFormData->category, category inserted into another table
			'description'		=> $jsonFormData['description'],
			'address' 			=> $jsonFormData['location']['address'],
			'longitude' 		=> $jsonFormData['location']['longitude'],
			'latitude' 			=> $jsonFormData['location']['latitude'],
			'region' 			=> $jsonFormData['location']['region'],
			'country' 			=> $jsonFormData['country'],
			'phone' 			=> $jsonFormData['phone'],
			'website' 			=> $jsonFormData['website'],
			'min_pax' 			=> $jsonFormData['pax']['minPax'],
			'max_pax' 			=> $jsonFormData['pax']['maxPax'],
			'average_price' 	=> $jsonFormData['price'],
			'time_range' 		=> $jsonFormData['timeRange'],
			//'submitted_date' 	=> 0,
			'approval_id' 		=> wp_get_current_user()->display_name
		)
	);
	
	$catArr = explode(",",$jsonFormData['category']);
	foreach($catArr as $category){
		$wpdb->insert(
			TABLE_ACTIVITY_CATEGORY,
			array(
				'object_id'		=> $jsonFormData['postid'],
				'category'		=> $category
			)
		);
	}

	/* For debugging
	$userReviews = $wpdb->get_results("
		SELECT name FROM ".TABLE_HYDI_ACTIVITY." WHERE object_id = '95'
	");
	foreach ( $userReviews as $value ){
		echo $value->name;
	}
	*/
}

/**
* DevID-05
* @param pagename
* @return postid
*/
function createPage($pagename){
	$post = array(
		'post_content'	=> "",
		'post_name' 	=> $pagename,
		'post_title'	=> $pagename,
		'post_type'		=> 'page',
		'post_status'	=> 'publish',
		'page_template'	=> 'activitydetail.php'
		);

	$newpostid = wp_insert_post($post, $wp_error);

	return $newpostid;
}
?>

<html>
	<body>
		<h2>Successfully added activity: </h2>
		<?php 
			if(isset($_POST['category'])){
				if (is_array($_POST['category'])) {
					$category = "";
					foreach($_POST['category'] as $value){
						if(strlen($category) > 0)
							$category = $category.",".$value;
						else $category = $category.$value;
					}
				    echo '<p>Category: '.$category;
				} else {
				    $value = $_POST['category']; 
				    echo '<p>Category:'.$value.'</p>';
				}
			}
		?>
		
		<p>Name: <?php echo $_POST["name"]; ?></p>
		<p>Description: <?php echo $_POST["description"]; ?></p>
		<p>Address: <?php echo $_POST["address"]; ?></p>
		<p>Address: <?php echo $_POST["longitude"]; ?></p>
		<p>Address: <?php echo $_POST["latitude"]; ?></p>
		<p>Region: <?php echo $_POST["region"]; ?></p>
		<p>Country: <?php echo $_POST["country"]; ?></p>
		<p>Phone: <?php echo $_POST["phone"]; ?></p>
		<p>Website: <?php echo $_POST["website"]; ?></p>
		<p>Pax: <?php echo $_POST["pax1"]."-".$_POST["pax2"]; ?></p>
		<p>Price: <?php echo $_POST["price"]; ?></p>
	</body>
</html>