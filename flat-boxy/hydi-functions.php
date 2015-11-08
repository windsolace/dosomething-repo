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
function hydi_postVote($postid, $userid, $review, $doneFlag){
	global $wpdb;

	$sqlPageReview = hydi_getReviews($postid, $userid);

	//Check if user has liked this page before
	if($sqlPageReview === null){ 
		//INSERT to database
		if($doneFlag === "true"){
			//Update activity_status instead
			try{
				//consolelog($postid." INSERT");
				$wpdb->insert(
					TABLE_HYDI_USERLIKES,
					array(
						'fbuid'				=> $userid,
						'object_id'			=> $postid,
						'activity_status' 	=> $review,
						'is_to_do' 			=> 0
					)
				);
				//log("INSERT success");
			} catch(Exception $e){
				consolelog($e->getMessage());
			}
		} else if($doneFlag === "false"){
			//Update review instead
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
	}
	else { 
		//Update database
		if($doneFlag === "true"){
			//Update activity_status instead
			try{
				//consolelog($postid." UPDATE");
				$wpdb->update(
					TABLE_HYDI_USERLIKES,
					array(
						'activity_status'=> $review
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
		} else if($doneFlag === "false") {
			//Update review instead
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
	define("CONSUMER_KEY", HYDI_TWIT_KEY);
	define("CONSUMER_SECRET", HYDI_TWIT_SECRET);

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
	$HydiUtil = new Util();

	$userReviews = $wpdb->get_results("
		SELECT 
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE fbuid = '".$userid."' AND review = '1') AS 'upvotes',
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE fbuid = '".$userid."' AND review = '0') AS 'downvotes',
		(SELECT COUNT(*) FROM ".TABLE_HYDI_USERLIKES." WHERE fbuid = '".$userid."' AND activity_status = '1') AS 'done'"
	);

	$userActivities = $wpdb->get_results("
		SELECT name, a.object_id, f.review, f.activity_status, DATE_FORMAT(f.done_date,'%d %M %Y') as done_date
		FROM ".TABLE_HYDI_ACTIVITY." a, ".TABLE_HYDI_USERLIKES." f
		WHERE a.object_id = f.object_id
		AND f.fbuid = '".$userid."'
	");

	//Get date difference
	$dateJoined = $wpdb->get_var("SELECT DATE_FORMAT(registered,'%d %M %Y') as registered FROM fb_user WHERE fbuid='".$userid."'");
	$displayName = $wpdb->get_var("SELECT displayName FROM fb_user WHERE fbuid='".$userid."'");
	$dateNow = time();
	$dateThen = strtotime($dateJoined);
	$datediff = abs($dateNow - $dateThen);
	$ageObj = $HydiUtil->getAgeFromSeconds($datediff);

	//Generate age
	$yearStr = "years";
	$monthStr = "months";
	$daysStr = "days";
	$accountAge = new stdClass();
	if($ageObj['y'] > 0){
		if($ageObj['y'] == 1) $yearStr = "year";
		$accountAge->years = $ageObj['y']." ".$yearStr;
	}
	if($ageObj['m'] > 0){
		if($ageObj['m'] == 1) $monthStr = "month";
		$accountAge->months = $ageObj['m']." ".$monthStr;
	}
	if($ageObj['d'] > 0){
		if($ageObj['d'] == 1) $dayStr = "day";
		$accountAge->days = $ageObj['d']." ".$daysStr;
	}

	//populate likes/dislikes/done
	$userLikes = array();
	$userDislikes = array();
	$userDone = array();
	foreach($userActivities as $activity){
		$activity->url = get_permalink($activity->object_id);
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
	$jsonObj->dateJoined = $dateJoined;
	$jsonObj->accountAge = $accountAge;
	$jsonObj->userid = $userid;
	$jsonObj->displayName = $displayName;
	$jsonObj->reviews = $userReviews;
	$jsonObj->activities = $activities;
	
	return json_encode($jsonObj);

}

/**
* GET Instagram images by Hashtag
* @param $countryCode - E.g. SG
* @return resultString (e.g. title~pubDate~link |)
*/
function getImagesByHashtag($hashtag){
	$HydiUtil = new Util();

	$url = "https://api.instagram.com/v1/tags/".$hashtag."/media/recent?client_id=".HYDI_INSTA_ID;
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);

	$result = curl_exec($ch);

	$respDataArr = [];
	$jsonObj = json_decode($result, true);

	foreach($jsonObj['data'] as $data){
		array_push($respDataArr, $data);
	}

	curl_close($ch); 
	return json_encode($respDataArr);
}

/**
 * POST new activity
 * @param formData
 * category%5B%5D=Play&name=New+activyity&description=desc&country=Singapore&postalcode=510119&address=119+Pasir+Ris+Street+11%2C+Singapore+510119&longitude=123&latitude=345&phone=81023504&website=&price=
 */
function postNewActivity($formData){
	global $wpdb;
	$success = false;
	$pendingTable = TABLE_HYDI_PENDING_ACTIVITY;
	$formInputArray = explode("&", $formData);
	//$page_id = $formInputArray[0];
	
	$jsonFormData = new stdClass();
	$jsonFormData->name = $value;
	$catStr = "";
	foreach($formInputArray as $formInput){
		$keyValueArray = explode("=", $formInput);
		$key = rawurldecode($keyValueArray[0]);
		$value = $keyValueArray[1];
		//echo $key." --- ".$value;
		switch($key){
			case "name": 
				$jsonFormData->name = rawurldecode($value); 
				break;
			case "category[]":
				if(strlen($catStr) > 0)
					$catStr = $catStr.",".$value;
				else $catStr = $catStr.$value;
				break;
			case "description":
				$jsonFormData->description = rawurldecode($value);
				break;
			case "country":
				$jsonFormData->country = rawurldecode($value);
				break;
			case "address":
				$jsonFormData->location["address"] = rawurldecode($value);
				break;
			case "longitude":
				$jsonFormData->location["longitude"] = rawurldecode($value);
				break;	
			case "latitude":
				$jsonFormData->location["latitude"] = rawurldecode($value);
				break;
			case "region":
				$jsonFormData->location["region"] = rawurldecode($value);
				break;
			case "phone":
				$jsonFormData->phone = rawurldecode($value);
				break;
			case "website":
				$jsonFormData->website = $value;
				break;
			case "min_pax":
				$jsonFormData->pax["minPax"] = rawurldecode($value);
				break;
			case "max_pax":
				$jsonFormData->pax["maxPax"] = rawurldecode($value);
				break;
			case "average_price":
				$jsonFormData->averagePrice = rawurldecode($value);
				break;
			case "fromTime":
				$fromTime = rawurldecode($value);
				break;
			case "toTime":
				$toTime = rawurldecode($value);
				break;
		}
		
	} 
	$jsonFormData->category = $catStr;
	$jsonFormData->timeRange = $fromTime." - ".$toTime;
	$jsonFormData = json_encode($jsonFormData);	
	$jsonArrData = json_decode($jsonFormData, true);
	//write inputs to activity table
	$wpdb->insert(
		$pendingTable,
		array(
			'name'				=> $jsonArrData['name'],
			'category'			=> $jsonArrData['category'],
			'description'		=> $jsonArrData['description'],
			'address' 			=> $jsonArrData['location']['address'],
			'longitude' 		=> $jsonArrData['location']['longitude'],
			'latitude' 			=> $jsonArrData['location']['latitude'],
			'region' 			=> $jsonArrData['location']['region'],
			'country' 			=> $jsonArrData['country'],
			'phone' 			=> $jsonArrData['phone'],
			'website' 			=> $jsonArrData['website'],
			'min_pax' 			=> $jsonArrData['pax']['minPax'],
			'max_pax' 			=> $jsonArrData['pax']['maxPax'],
			'average_price' 	=> $jsonArrData['averagePrice'],
			'time_range' 		=> $jsonArrData['timeRange']
			//'submitted_date' 	=> 0,
			//'approval_id' 		=> wp_get_current_user()->display_name*/
		)
	);
	echo $jsonFormData;
	return $success;
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