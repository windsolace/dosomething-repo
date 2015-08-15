<?php
	/*
		* Template Name: New Activity Template
		*
		* @package WordPress
		* @subpackage Flat_Boxy
		* @since Flat_Boxy v1
	*/
	
get_header(); ?>
<?php the_breadcrumb(); ?>
<div id = "body-wrapper">
	<section id="main" class="full">
	

		<div id="main-content">
			
			<form action="functions.php" method="POST">
				<table>
					<tr>
						<td>Category</td>
						<td>
							<input type="checkbox" name="category" value="Eat" />Eat
							<input type="checkbox" name="category" value="Play" />Play
							<input type="checkbox" name="category" value="Explore" />Explore
						</td>
					</tr>
					<tr>
						<td>Name</td>
						<td><input type="text" name="name"></td>
					</tr>
					<tr>
						<td>Description</td>
						<td><textarea rows="4" cols="50" maxlength="50" name="description"></textarea></td>
					</tr>
					<tr>
						<td>Address</td>
						<td><textarea rows="4" cols="50" maxlength="50" name="address"></textarea></td>
					</tr>
					<tr>
						<td>Region</td>
						<td>
							<select>
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
							<select>
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
							<select>
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
							<select>
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
						</td>
					</tr>
				</table>
				<br><br>
				<input type="submit" value="Submit">
			</form>
		</div><!-- #main-content -->
	</section>
	
	<?php get_footer();?>
</div>

	


<script>
	$( document ).ready(function() {
		$('#chkBox_opHrs').change(function(){
			if ($('#chkBox_opHrs').is(':checked')) {
				document.getElementById("fromTime").disabled=true;
				document.getElementById("toTime").disabled=true;
			}
			else{
				document.getElementById("fromTime").disabled=false;
				document.getElementById("toTime").disabled=false;
			}
		});
		$('#fromTime').timepicker();
		$('#toTime').timepicker();
		});
		
		</script>
	</body>
</html>