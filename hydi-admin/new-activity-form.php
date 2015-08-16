<?php
/**
* New activity form
* Adds a new activity into database
*/

/*========================================
Form Fields:
(*) - mandatory
- Post ID(*)
- Category
- Name(*)
- Description
- Address(*)
- Region
- Country
- Phone Number
- Website
- Suitable for how many persons
- Average price
- Opening hours 
========================================*/
?>
<form action="<?php echo plugins_url('hydi-admin/new-activity-handler.php') ?>" method="POST">
	<table>
		<tr>
			<td>Post ID *</td>
			<td><input type="text" name="postid" required/></td>
		</tr>
		<tr>
			<td>Category</td>
			<td>
				<input type="checkbox" name="category[]" value="Eat" required/>Eat
				<input type="checkbox" name="category[]" value="Play" />Play
				<input type="checkbox" name="category[]" value="Explore" />Explore
			</td>
		</tr>
		<tr>
			<td>Name *</td>
			<td><input type="text" name="name" required></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><textarea rows="4" cols="50" maxlength="50" name="description"></textarea></td>
		</tr>
		<tr>
			<td>Address *</td>
			<td><textarea rows="4" cols="50" maxlength="50" name="address" required></textarea></td>
		</tr>
		<tr>
			<td>Region</td>
			<td>
				<select name = "region">
					<option value="North">North</option>
					<option value="South">South</option>
					<option value="East">East</option>
					<option value="West">West</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Country</td>
			<td>
				<select name = "country">
					<option value="Singapore">Singapore</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Phone number</td>
			<td><input type="text" name="phone"></td>
		</tr>
		<tr>
			<td>Website</td>
			<td><input type="text" name="website"></td>
		</tr>
		<tr>
		<td>Suitable for how many number of person</td>
		<td>
			<select name = "pax1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
			&nbsp;-&nbsp;
			<select name = "pax2">
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
		</td>
		</tr>
			<tr>
				<td>Average price</td>
				<td><input type="text" name="price"></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td>Opening hours</td>
				<td>
					<input type="checkbox" id="chkBox_opHrs" value="24 Hours">24 Hours<br>
					<input id="fromTime" type="text" class="time ui-timepicker-input" data-scroll-default="6:00am" autocomplete="off">
					&nbsp;-&nbsp;
					<input id="toTime" type="text" class="time ui-timepicker-input" data-scroll-default="6:00am" autocomplete="off">
				</select>
			</td>
		</tr>
	</table>
	<br><br>
	<input type="submit" value="Submit">
</form>
<script>
	jQuery( document ).ready(function() {
		events();
	});
	
	/**
	* events
	* 	event-1. Disable operating hours if 24-hrs is checked
	*/
	var events = function(){
		//event-1
		jQuery('#chkBox_opHrs').change(function(){
			if (jQuery('#chkBox_opHrs').is(':checked')) {
				document.getElementById("fromTime").disabled=true;
				document.getElementById("toTime").disabled=true;
			}
			else{
				document.getElementById("fromTime").disabled=false;
				document.getElementById("toTime").disabled=false;
			}
		});
		jQuery('#fromTime').timepicker();
		jQuery('#toTime').timepicker();
	};

	//validation
	var form_validation = function(callback){
		if(callback)
			callback();
	};
</script>
