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

	$activity_row = $wpdb->get_row("SELECT * FROM ".activity." WHERE object_id = '".$postID."'");

	$activityName = $activity_row->name;
	$activityDescription = $activity_row->description;

	return $activity_row->description;
}
?>