<?php 
/*
* ajaxHandler.php
*  Handles all AJAX calls
*/
require("constants.php");
/*
*Start determine if it's AJAX call then handle data accordingly
*/
if(is_ajax()){
	//Define dependencies
	require("hydi-functions.php");

	//Start ajax handling
	$data = $_POST["data"];
	$requestPath = $_POST["requestPath"];
	//routeRequest($requestPath, $data);
}

function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function routeRequest($requestPath, $data){
	$response = "";

	/*
	API id: 01
	* GET/POST user review on activity
	*/
	if($requestPath == USER_ACTIVITY_VOTES){
		$responseArray = json_decode($data);

		//IF HTTP GET -> Retrieve user reviews from user_likes table
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			foreach($responseArray as $key => $value){
				if($key == 'objectid') $objectid = $value;
				if($key == 'userid') $userid = $value;
			}
			$response = hydi_getReviews($objectid,$userid);
			//echo "API 01: GET Success\n";
			echo $response;
		}

		//IF HTTP POST -> Update user_likes table
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			foreach($responseArray as $key => $value){
				if($key == 'objectid') $objectid = $value;
				if($key == 'userid') $userid = $value;
				if($key == 'voteType') $voteType = $value;
			}
			$response = hydi_postVote($objectid,$userid,$voteType);
			echo "API 01: POST Success";
		}
	}

	/*
	API id: 02
	* GET activity details
	*/
	if($requestPath == ACTIVITY_DETAILS){
		$responseArray = json_decode($data);

		//IF HTTP GET -> Retrieve user reviews from usser_likes table
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			foreach($responseArray as $key => $value){
				if($key == 'objectid') $objectid = $value;
			}
			$response = hydi_getActivity($objectid);
			//echo "API 02: GET Success\n";
			echo $response;
		}
	}

	/*
	API id: 03
	* GET all activities
	*/
	if($requestPath == ALL_ACTIVITIES){
		$responseArray = json_decode($data);

		//IF HTTP GET -> Retrieve user reviews from usser_likes table
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			/*foreach($responseArray as $key => $value){
				if($key == 'objectid') $objectid = $value;
			}*/
			$response = hydi_getAllActivities();
			$jsonObj = new stdClass();
			$jsonObj = $response;

			//echo "API 03: GET Success\n";
			echo $jsonObj;
		}
	}

	/*
	API id: 04
	* GET/POST login status
	*/
	if($requestPath == SITE_LOGIN){
		$responseArray = json_decode($data);

		//IF HTTP GET -> get login status
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			foreach($responseArray as $key => $value){
				if($key == 'userid') $userid = $value;
			}
			//$response = getSession($userid);
			$response = isAuthenticated($userid);
			$jsonObj = new stdClass();
			//$jsonObj->userid = $userid;
			//$jsonObj->auth = $response;

			$jsonObj = json_encode($response);

			//echo "API 04: GET Success\n";
			echo $response;
		}
	}

	//echo "routeRequest done";
	die();

}

?>