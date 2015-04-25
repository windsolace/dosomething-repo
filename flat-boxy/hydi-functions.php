<?php 
/*
* hydi-functions.php
*  Contains functions unique to HYDI. 
*/
require('constants.php');
/*
* GET activity by object_id
*/
function hydi_getActivity( $postID ){
	global $wpdb;

	//Call ACTIVITY Table
	$activity_row = $wpdb->get_row("SELECT * FROM ".TABLE_HYDI_ACTIVITY." WHERE object_id = '".$postID."'");

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

/*
* Post vote for an activity by user
* @params $userid - id of the user (may need encryption)
* @params $postid - id of the post
* @params $review - review (either 1 or 0)
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

/*
* Get Trends by Code
* @params $code - Code defined by Google Trends
* url: GET http://hawttrends.appspot.com/api/terms/ (deprecated)
* url: GET http://www.google.com/trends/hottrends/atom/feed?pn=p23
* All regions: 0
* AU: 8
* SG: 5
* KR: 23
* MY: 34
*/
function hydi_getTrends($countryCode){
	$trendsParam = 0;
	$trendsParam = getParamByCountryCode($countryCode);

	//TO-DO: Set default region based on location

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
	$jsonObj = new stdClass();
	 
	foreach($trends->channel->item as $value) { 
        $resultString =  $resultString.$value->title."|";
	}
	if(substr($resultString, (strlen($resultString)-1), strlen($resultString)) == "|"){
	 	$resultString = substr($resultString, 0, (strlen($resultString)-1));
	}

	$jsonObj->code = $countryCode;
	$jsonObj->results =(explode('|',$resultString));
	return json_encode($jsonObj);
}

function getParamByCountryCode($countryCode){
	if($countryCode === "ALL"){
		return 0;
	}
	else if($countryCode === "AU"){
		return 8;
	}
	else if($countryCode === "SG"){
		return 5;
	}
	else if($countryCode === "KR"){
		return 23;
	}
	else if($countryCode === "MY"){
		return 34;
	}
	else return 0;
}
?>