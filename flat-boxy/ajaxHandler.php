<?php 
/*
* ajaxHandler.php
*  Handles all AJAX calls
*/

/*
*Start determine if it's AJAX call then handle data accordingly
*/
if(is_ajax()){
	//Define dependencies
	require("hydi-functions.php");

	//Start ajax handling
	$data = $_POST["data"];
	$requestPath = $_POST["requestPath"];
	routeRequest($requestPath, $data);
}

function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function routeRequest($requestPath, $data){
	//Review Call
	if($requestPath == "hydi/activity/reviews"){
		hydi_postVote($data);
	}

}

?>