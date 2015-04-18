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

/*
* Get a review object
* @params $userid - id of the user (may need encryption)
* @params $postid - id of the post
*
*/
function hydi_getVote($postid, $userid){
	global $wpdb;

	//TO-DO: Check if user has liked this page before
	$sqlPageReview = $wpdb->get_row("SELECT * FROM ".fb_user_likes." WHERE object_id = '".$postid."' AND fbuid = '".$userid."'");

	return json_encode($sqlPageReview);
}

/*
* Post vote for an activity by user
* @params $userid - id of the user (may need encryption)
* @params $postid - id of the post
* @params $review - review (either 1 or 0)
*
* Requires hydi_getVote($userid, $postid);
*/
function hydi_postVote($postid, $userid, $review){
	//echo ../wordpress/wp-load.php();
	//include(".../wordpress/wp-load.php");
	global $wpdb;

	$review = 0;
	$postid = "96";
	$userid = "12317263743";
	$auth = "";

	//TO-DO: Check authentication

	$sqlPageReview = hydi_getVote($postid, $userid);

	//Check if user has liked this page before
	if($sqlPageReview !=null){
		//Update database
		try{
			$wpdb->update(
				'fb_user_likes',
				array(
					'review'=> $review
				),
				array(
					'fbuid'		=>$userid,
					'object_id'	=>$postid
				)
			);
			//log("UPDATE success");
		} catch(Exception $e){
			log($e->getMessage());
		}
	}
	else {
		//INSERT to database
		try{
			$wpdb->insert(
				'fb_user_likes',
				array(
					'fbuid'				=> $userid,
					'object_id'			=> $postid,
					'review'			=> $review,
					'activity_status' 	=> 0,
					'is_to_do' 			=> 0
				)
			);
			//log("INSERT success");
		} catch(Exception $e){
			log($e->getMessage());
		}
	}
}
?>