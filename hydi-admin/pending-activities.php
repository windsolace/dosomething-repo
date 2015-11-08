<?php
/**
 * TAB: Pending activities
 * View a list of activities from pending table
 * Perform action on a pending activity
 */

function getPendingActivities(){
	global $wpdb;

	$results = $wpdb->get_results("SELECT * FROM ".TABLE_HYDI_PENDING_ACTIVITY);

	$results = json_encode($results);

	return $results;
}

$pendingActivitiesJson = getPendingActivities();
$pendingActivitiesArr =  json_decode($pendingActivitiesJson);
?>

<h4><?php echo count($pendingActivitiesArr) ?> Pending activities: </h4>

<table id = "pending-activities">
<?php $count = 0; ?>
	<tr>
		<td> <b>Item</b> </td>
		<td> <b>Activity</b> </td>
		<td> <b>Approve</b> </td>
		<td> <b>Reject</b> </td>
	</tr>
	<?php foreach($pendingActivitiesArr as $pendingActivity){ ?>
	<tr>
		<td><?php  $count++; echo $count; ?>
		<td class = "details text-left">
		<?php foreach($pendingActivity as $key => $value){ ?>
				<div>
					<span class = "detail-key"><?php echo $key ?></span>:
					<span class = "detail-value" name = "<?php echo $key ?>"><?php echo $value ?></span>
				</div>
		<?php } ?>
		</td>
		<td><input type = "checkbox" class = "approve"></input></td>
		<td><input type = "checkbox" class = "reject"></input></td>
	</tr>
	<?php } ?>
</table>

<input type = "submit"/>
<script>
jQuery(document).ready(function(){
	init();

	events();
	

});
var init = function(){
}

var events = function(){
	jQuery('input[type="submit"]').on('click', function(){
		
		var approveCheckboxes = jQuery('#pending-activities').find('input.approve:checked');
		var rejectCheckboxes = jQuery('#pending-activities').find('input.reject:checked');
		var approvedActivitiesArr = [];
		var rejectedActivitiesArr = [];

		//get approved activities
		for(var i = 0; i < approveCheckboxes.length; i++){
			var pendingActivityObj = {};
			var checkbox = approveCheckboxes[i];
			var activityValues = jQuery(checkbox).parents('td').siblings('td.details').find('span.detail-value');
			jQuery.each(activityValues, function(index, key){
				var value = activityValues[index];
				var propStr = jQuery(value).attr('name');
				var valueStr = jQuery(value).text();
				
				pendingActivityObj[propStr] = valueStr;
			});
			//console.log(pendingActivityObj);
			approvedActivitiesArr.push(pendingActivityObj);
		}

		//get rejected activities
		for(var i = 0; i < rejectCheckboxes.length; i++){
			var pendingActivityObj = {};
			var checkbox = rejectCheckboxes[i];
			var activityValues = jQuery(checkbox).parents('td').siblings('td.details').find('span.detail-value');
			jQuery.each(activityValues, function(index, key){
				var value = activityValues[index];
				var propStr = jQuery(value).attr('name');
				var valueStr = jQuery(value).text();
				
				pendingActivityObj[propStr] = valueStr;
			});
			//console.log(pendingActivityObj);
			rejectedActivitiesArr.push(pendingActivityObj);
		}

		jQuery.ajax({
			method:"POST",
			url: "<?php plugins_url('hydi-admin/pending-activities-handler.php') ?>",
			data: {
				"approvedList":approvedActivitiesArr,
				"rejectedList":rejectedActivitiesArr
			},
			done: function(){
			console.log("POST done");
			}
		})
		.done(function(data){
			console.log("post success");
		});
	});
}
</script>