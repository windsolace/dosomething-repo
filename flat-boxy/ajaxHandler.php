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
				if($key == 'auth') $sessionID = $value;
				if($key == 'doneFlag') $updateDoneStatus = $value;
			}
			if($sessionID){
				$authResponse = isAuthenticated($userid, $sessionID);
				$authResponse = json_decode($authResponse);
				$authenticated = false;
				foreach($authResponse as $key => $value){
					if($key == 'isLoggedIn') $authenticated = $value;
				}

				if($authenticated){
					//Check to update Done status or update Upvote/Downvote
					$response = hydi_postVote($objectid,$userid,$voteType, $updateDoneStatus);
					echo "API 01: POST Success";
				} else {
					echo "Unable to POST vote, user failed authentication.";
				}
			} else{
				 echo "Unable to POST vote, not logged in.";
			}
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
				if($key == 'auth') $sessionID = $value;
			}
			//$response = getSession($userid);
			$response = isAuthenticated($userid, $sessionID);
			$jsonObj = new stdClass();
			//$jsonObj->userid = $userid;
			//$jsonObj->auth = $response;

			$jsonObj = json_encode($response);

			//echo "API 04: GET Success\n";
			echo $response;
		}

		//IF HTTP POST -> update/POST login status
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			foreach($responseArray as $key => $value){
				if($key == 'userid') $userid = $value;
				if($key == 'auth') $sessionID = $value;
			}

			//If have session and userid (already logged in before)
			if($sessionID){
				$authResponse = isAuthenticated($userid, $sessionID);
				$authResponse = json_decode($authResponse);
				foreach($authResponse as $key => $value){
					if($key == 'isLoggedIn') $authenticated = $value;
				}

				//session tokens match
				if($authenticated){
					//Generate session key
					$sessionID = genSessionID(SESSION_ID_LENGTH);
					$response = storeSessionID($userid, $sessionID);
				}
				//1/6/2015: Added check for new user
				//no match of session tokens (might be new user)
				else{
					if($userid){
						registerNewUser($userid);
						$sessionID = genSessionID(SESSION_ID_LENGTH);
						$response = storeSessionID($userid, $sessionID);
					}
					else{
						consolelog("No userid detected.");
					}
				}
			}
			//If no session, generate new session
			else{
				$sessionID = genSessionID(SESSION_ID_LENGTH);
				$response = storeSessionID($userid, $sessionID);
			}

			$jsonObj = new stdClass();
			$jsonObj->sessionID = $sessionID;
			//$jsonObj->auth = $response;

			$jsonObj = json_encode($jsonObj);

			//echo "API 04: GET Success\n";
			echo $jsonObj;
		}
	}

	/*
	API id: 05
	* GET user profile info
	*/
	if($requestPath == USER_PROFILE_INFO){
		$responseArray = json_decode($data);

		//IF HTTP GET -> get login status -> get user info
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			foreach($responseArray as $key => $value){
				if($key == 'userid') $userid = $value;
				if($key == 'auth') $auth = $value;
			}
			//$response = getSession($userid);
			$authResponse = isAuthenticated($userid, $auth);

			$jsonObj = new stdClass();			
			$jsonObj = json_decode($authResponse, true);

			//Check auth response -> If true, get profile info
			if($jsonObj['isLoggedIn'] == true){
				$userProfileObj = hydi_getUserProfile($userid);
				echo $userProfileObj;
			}

			//echo "API 05: GET Success\n";
			//echo $jsonObj['isLoggedIn'];
		}
	}

	//echo "routeRequest done";
	die();

}

?>