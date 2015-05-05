<?php 
/*
* hydi-functions.php
*  Contains functions unique to HYDI. 
*/
require('constants.php');
require('Util.php');
require('install/twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;


/*
* GET all activities
*/
function hydi_getAllActivities(){
	global $wpdb;

	$results = $wpdb->get_results("SELECT * FROM ".TABLE_HYDI_ACTIVITY);

	$results = json_encode($results);

	return $results;
}

/*
* GET activity by object_id
*/
function hydi_getActivity( $postID ){
	global $wpdb;

	//Call ACTIVITY Table
	$activity_row = $wpdb->get_row("SELECT * FROM ".TABLE_HYDI_ACTIVITY." WHERE object_id = '".$postID."'");

	$activityID = $activity_row->object_id;
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
	$obj->id = $activityID;
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
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE object_id = '".$postID."' AND review = '1') AS 'upvotes',
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE object_id = '".$postID."' AND review = '0') AS 'downvotes',
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE object_id = '".$postID."' AND activity_status = '1') AS 'done'"
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
function hydi_getReviews($postid, $userid){
	global $wpdb;

	//GET row by userid && activity id
	$sqlPageReview = $wpdb->get_row("SELECT * FROM ".TABLE_HYDI_USERLIKES." WHERE object_id = '".$postid."' AND fbuid = '".$userid."'");

	//Check if user has liked this page before
	if($sqlPageReview === null){
		return null;
	} else {
		return json_encode($sqlPageReview);
	}
}

/**
* Post vote for an activity by user
* @param $userid - id of the user (may need encryption)
* @param $postid - id of the post
* @param $review - review (either 1 or 0)
*
* Requires hydi_getVote($userid, $postid);
*/
function hydi_postVote($postid, $userid, $review){
	global $wpdb;

	$auth = "";

	//TO-DO: Check authentication

	$sqlPageReview = hydi_getReviews($postid, $userid);

	//Check if user has liked this page before
	if($sqlPageReview === null){ 
		//INSERT to database
		try{
			//consolelog($postid." INSERT");
			$wpdb->insert(
				TABLE_HYDI_USERLIKES,
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
			consolelog($e->getMessage());
		}
	}
	else { 
		//Update database
		try{
			//consolelog($postid." UPDATE");
			$wpdb->update(
				TABLE_HYDI_USERLIKES,
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
			consolelog($e->getMessage());
		}
	}
}

/**
* Get Trends by Country Code
* @param $countryCode (e.g. SG)
*/
function hydi_getTrends($countryCode){
	//GET Google Top Searches
	$topSearchesString = getGoogleTopSearches($countryCode);

	//GET Twitter Trends
	$twitterTrends = getTwitterTrends($countryCode);

	$jsonObj = new stdClass();
	
	$jsonObj->code = $countryCode;
	$jsonObj->topSearches =(explode('|',$topSearchesString));
	$jsonObj->twitterTrends = $twitterTrends;

	return json_encode($jsonObj);
}

/**
* GET Twitter Popular Hashtags
* url: /trends/place (Twitter REST API v1.1)
* @param woeid - WOEID by Yahoo!
* Worldwide: 1
*/
function getTwitterTrends($countryCode){
	define("CONSUMER_KEY", "");
	define("CONSUMER_SECRET", "");
	$access_token = "";
	$access_token_secret = "";

	$HydiUtil = new Util();
	$woeid = $HydiUtil->getWOEIDByCountryCode($countryCode);

	//TO-DO: Input different woeid

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

	$twitterTrends = $connection->get("trends/place", array("id"=> $woeid));

	return $twitterTrends;
}

/**
* GET Google Top Searches
* @param $countryCode - E.g. SG
* @return resultString (e.g. title~pubDate~link |)
* url: GET http://hawttrends.appspot.com/api/terms/ (deprecated)
* url: GET http://www.google.com/trends/hottrends/atom/feed?pn=p23
*/
function getGoogleTopSearches($countryCode){
	$trendsParam = 0;
	$HydiUtil = new Util();
	$trendsParam = $HydiUtil->getParamByCountryCode($countryCode);

	//TO-DO: Set default region based on location

	//Google Search Trends
	$url = 'http://www.google.com/trends/hottrends/atom/feed?pn=p'.$trendsParam;
	$referrer = 'http://www.google.com';
	$agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);

	$result = curl_exec($ch);
	$trends = new SimpleXmlElement($result);
	$resultString = "";
	 
	foreach($trends->channel->item as $value) { 
        $resultString =  $resultString.$value->title."~";
        $resultString = $resultString.$value->pubDate."~";
        $resultString = $resultString.$value->link."|";
	}
	if(substr($resultString, (strlen($resultString)-1), strlen($resultString)) == "|"){
	 	$resultString = substr($resultString, 0, (strlen($resultString)-1));
	}
	//End Google Search Trends

	return $resultString;

}

/**
* GET user profile info
* @param $userid
* @return $jsonObj
*/
function hydi_getUserProfile($userid){
	global $wpdb;

	$userReviews = $wpdb->get_results("
		SELECT 
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE fbuid = '".$userid."' AND review = '1') AS 'upvotes',
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE fbuid = '".$userid."' AND review = '0') AS 'downvotes',
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE fbuid = '".$userid."' AND activity_status = '1') AS 'done'"
	);

	$userActivities = $wpdb->get_results("
		SELECT name, a.object_id, f.review, f.activity_status
		FROM activity a, fb_user_likes f
		WHERE a.object_id = f.object_id
	");

	//populate likes/dislikes/done
	$userLikes = array();
	$userDislikes = array();
	$userDone = array();
	foreach($userActivities as $activity){
		if($activity->review === "1"){
			array_push($userLikes, $activity);
		}
		else if($activity->review === "0"){
			array_push($userDislikes, $activity);
		}
		if($activity->activity_status === "1"){
			array_push($userDone, $activity);
		}
	}

	//create activities json
	$activities = new stdClass();
	$activities->upvotes = $userLikes;
	$activities->downvotes = $userDislikes;
	$activities->done = $userDone;
	$activities->all = $userActivities;

	//create return json response
	$jsonObj = new stdClass();
	$jsonObj->reviews = $userReviews;
	$jsonObj->activities = $activities;
	
	return json_encode($jsonObj);

}

/*
* Random Function to render 1 activity as result
* @params $code - 
*/
//function randomActivity(noOfPax, Location){
	/*
	//create a sql query  (http://www.tizag.com/mysqlTutorial/mysqlfetcharray.php)
	$sql = mysql_query("SELECT obj_id FROM Activity Table 
						WHERE noOfPax = 5
						AND Location = "Orchard"
						ORDER BY RAND() 
						LIMIT 5");
	*/
	
//}
?>