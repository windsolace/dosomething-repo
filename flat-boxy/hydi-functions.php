<?php 
/*
* hydi-functions.php
*  Contains functions unique to HYDI. 
*/

/*
* GET activity by object_id
*/
function hydi_getActivity( $postID ){
	global $wpdb;

	//Call ACTIVITY Table
	$activity_row = $wpdb->get_row("SELECT * FROM ".activity." WHERE object_id = '".$postID."'");

	$activityName = $activity_row->name;
	$activityDescription = $activity_row->description;
	$activityAddress = $activity_row->address;
	$activityLongitude = $activity_row->longitude;
	$activityLatitude = $activity_row->latitude;
	$activityRegion = $activity_row->region;
	$activityCountry = $activity_row->country;
	$activityPhone = $activity_row->phone;
	$activityWebsite = $activity_row->website;
	$activityMinPax = $activity_row->min_pax;
	$activityMaxPax = $activity_row->max_pax;
	$activityAveragePrice = $activity_row->average_price;
	$activityTimeRange = $activity_row->time_range;

	$obj = new stdClass();
	$obj->name = $activityName;
	$obj->description = $activityDescription;
	$obj->country = $activityCountry;
	$obj->address = $activityAddress;
	$obj->phone = $activityPhone;
	$obj->longitude = $activityLongitude;
	$obj->latitude = $activityLatitude;
	$obj->region = $activityRegion;
	$obj->website = $activityWebsite;
	$obj->minPax = $activityMinPax;
	$obj->maxPax = $activityMaxPax;
	$obj->averagePrice = $activityAveragePrice;
	$obj->timeRange = $activityTimeRange;

	//call FB_USER_LIKES Table
	$sqlreviews = $wpdb->get_results("
		SELECT 
		(SELECT COUNT(*) FROM ".fb_user_likes." WHERE object_id = '".$postID."' AND review = '1') AS 'upvotes',
		(SELECT COUNT(*) FROM ".fb_user_likes." WHERE object_id = '".$postID."' AND review = '0') AS 'downvotes',
		(SELECT COUNT(*) FROM ".fb_user_likes." WHERE object_id = '".$postID."' AND activity_status = '1') AS 'done'"
	);
	$obj->reviews = $sqlreviews;

	return json_encode($obj);
}

function hydi_postVote($data){
	//echo ../wordpress/wp-load.php();
	//include(".../wordpress/wp-load.php");
	global $wpdb;

	$postid = "96";
	$userid = "12317263743";
	$auth = "";

	//read JSON

	//TO-DO: Check if user has liked this page before
	$sqlPageReview = $wpdb->get_row("SELECT * FROM ".fb_user_likes." WHERE object_id = '".$postid."' AND fbuid = '".$userid."'");

	//TO-DO: Check authentication
	if($sqlPageReview !=null){
		//TO-DO: Update database
		$wpdb->update(
			'fb_user_likes',
			array(
				'review'=>0
			),
			array(
				'fbuid'=>'12317263743',
				'object_id'=>'96'
			)
		);
		//echo "updated";
	}
	else {
		//TO-DO: INSERT to database
		//INSERT INTO `fb_user_likes`(`fbuid`, `object_id`, `review`, `activity_status`, `is_to_do`) VALUES ('12317263743','96','1','0','0')
	}
	//return json_encode($sqlPageReview);


	//echo "IN hydi postVote \n";
	//die("End of hydi_postVote");
}
?>