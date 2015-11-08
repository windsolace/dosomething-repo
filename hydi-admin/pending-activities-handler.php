<?php
/**
 * Pending Activity Handler
 * - Get form inputs
 * - Clean input
 * - Write to database
 */

main();

function main(){
	$approvedListArr = $_POST["approvedList"];
	$rejectedListArr = $_POST["rejectedList"];
	
	$approvedListArr = getPostData($approvedListArr);
	$rejectedListArr = getPostData($rejectedListArr);
	//writeToDatabase($formData, TABLE_HYDI_ACTIVITY);
}

/**
 * Prepare data from POST for database insert
 * @return array
 */
function getPostData($activityArray){
	foreach($activityArray as $activity){
		//Make max_pax = min_pax assuming min_pax is the most accurate
		if($activity["min_pax"] > $activity["max_pax"]){
			$activity["max_pax"] = $activity["min_pax"];
		}
		/*
		foreach($activity as $key => $value){
			
		}
		*/
	}
	return $activityArray;
}

/**
 * Process flow for approving activity
 * 	- Insert into approved table
 * 	- Insert into activities table
 * 	- Remove from pending table
 * 	- Create page
 * 
 */
function approveActivity(){
	
}

?>