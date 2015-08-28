<?php
/**
* New activity form
* Adds a new activity into activity table
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
<h4>Inserts a new activity into activity table</h4>
<form action="<?php echo plugins_url('hydi-admin/new-activity-handler.php') ?>" method="POST" onsubmit = "return form_validation();">
	<table>
		<tr>
			<td>Post ID *</td>
			<td><input type="text" name="postid" required/></td>
		</tr>
		<tr>
			<td>Category</td>
			<td>
				<input type="checkbox" name="category[]" value="Eat"/>Eat
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
			<td>Country</td>
			<td>
				<select name = "country">
					<option value="Singapore">Singapore</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Postal Code</td>
			<td><input type="text" name="postalcode" maxlength="6"></td>
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
<script type="text/javascript" src="http://gothere.sg/jsapi?sensor=false"></script>
<script>
	gothere.load("maps");
	jQuery( document ).ready(function() {
		init();
		events();
	});

	var init = function(){
		setPostalMaxLength(jQuery('select[name="country"]').val());
	}
	
	/**
	* events
	* 	event-1. Disable operating hours if 24-hrs is checked
	*	event-2. Populate address by postal code (Countries: SG)
	*	event-3. Change postalcode maxlength based on country select
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

		//event-2
		jQuery('input[name="postalcode"]').blur(function(){
			//use gothere.sg geo API if country selected is singapore
			if(jQuery('select[name="country"]').val().toLowerCase() === "singapore"){
				var postalCode = jQuery('input[name="postalcode"]').val();
				var geocoder = new GClientGeocoder(); 
				geocoder.getLatLng(postalCode, function(response){
					geocoder.getLocations(response, function(place){
						var apiAddress = place.Placemark[0].address;
						console.log(place.Placemark[0].address);
						jQuery('textarea[name="address"]').text(apiAddress);
					});
				});
			}
		});

		//event-3
		jQuery('select[name="country"]').change(function(){
			setPostalMaxLength(jQuery(this).val());
		});
	};

	/**
	* Sets postal code input field max length based on country selected
	*/
	var setPostalMaxLength = function(){
		if("Singapore" === "singapore"){
			jQuery('input[name="postal"]').show().prop('disabled', false);
			jQuery('input[name="postal"]').attr('maxlength', '6');
		} else {
			jQuery('input[name="postal"]').hide().prop('disabled', true);
		}
	}

	//validation
	var form_validation = function(){
		validationPassed = true;
		if(jquery('input[name="category"]').length <= 0){
			validationPassed = false;
			console.log("Category checkboxes not checked!");
		}
		return validationPassed;
		/*
		if(callback)
			callback();
		*/
	};
</script>
