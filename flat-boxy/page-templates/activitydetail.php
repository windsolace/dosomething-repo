<?php
	/*
		* Template Name: Activity Detail
		*
		* @package WordPress
		* @subpackage Flat_Boxy
		* @since Flat_Boxy v1
	*/
	
get_header(); ?>
<?php the_breadcrumb(); ?>
<div id="body-wrapper">
	
	<?php
		if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
			// Include the featured content template.
			get_template_part( 'featured-content' );
		}
	?>
	
	<!-- Start of navigation-->
	<?php include(__DIR__."/../sidenav.php");	?>
	<!-- End of navigation -->
	
	<section id = "main" class = "full">
		<div id = "main-content">
			
			
			<?php
				//Start the Loop
				while(have_posts()) : the_post();
				//Include the content from editor
				//the_content();
			?>
			<section id = "main" class = "full">
				<div id = "main-banner" class="gray"><img src = "../../../wp-content/themes/flat-boxy/img/cells/burger.jpg"/></div>
				<div id = "main-content">
					<div class="row">
						<div class = "grid-12">
							<h2><?php echo get_the_title() ?></h2>
						</div>
					</div>
					<?php
										$con = mysql_connect("localhost","root","");
										if (!$con)
										{
											die('Could not connect: ' . mysql_error());
										}
										mysql_select_db("hydi", $con);
										$object_id = get_the_ID();
										if( ! is_numeric($object_id) )
										die('invalid object id');
										
										$result = mysql_query("SELECT address, description, region, country, phone, website, average_price, time_range FROM activity WHERE object_id =" .$object_id);
										if (!$result) {
											echo 'Could not run query: ' . mysql_error();
											exit;
										}
										$row = mysql_fetch_row($result);
										
										$address = $row[0];
										$description = $row[1];
										$region = $row[2];
										$country = $row[3];
										$phone = $row[4];
										$website = $row[5];
										$average_price = $row[6];
										$time_range = $row[7];
															
										
										mysql_close($con);
										
										//$results = $wpdb->get_row( 'SELECT * FROM $wpdb->activity WHERE object_id = 71');
										//$Description = $wpdb->get_var("SELECT description FROM $wpdb->activity WHERE (object_id = 71)");
										//echo $results->description;
										?>
					<div class="row clear">
						<div class="grid-3 columns">
							<div class = "grid-header">
								<span>Ratings</span>
							</div>
							<div class = "content">
								<ul class="no-list rate-list">
									<li><span class="icon up-arrow"><span>X</span></span></li>
									<li><span class="icon down-arrow"><span>Y</span></span></li>
									<li><span class="icon tick-mark"><span>Z</span></span></li>
								</ul>
							</div>
							<div class = "content">
								<ul class="no-list">
									<li><span class="icon-medium phone"><span>65829540</span></span></li>
									<li><span class="icon-medium clock"><span>11.30-12.30</span></span></li>
									<li><span class="icon-medium globe"><span>http://www.google.com</span></span></li>
								</ul>
								<!--<img src = "wp-content/themes/flat-boxy/img/sprites/phone.png"/>-->
								<span class = "icon phone"></span>Phone
								<?php echo $phone; ?>
							</div>
							<div class = "content">
								<img src = "wp-content/themes/flat-boxy/img/sprites/operatinghours.png"/>
								<?php echo $time_range; ?>
							</div>
							<div class = "content">
								<img src = "wp-content/themes/flat-boxy/img/sprites/website.png"/>
								<a href="<?php echo $website; ?>" target="_blank">Visit Website</a>
							
							</div>
						</div>
						<div class="grid-6 columns">
							<div class = "grid-header">
								<span>Information</span>
							</div>
							<button id = "transit-up" class = "btn-transit light-gray" style = "display:none" disabled>
								<span class="up"></span>Up To Description
							</button>
							<div id = "upper-content">
								<p>
									<?php echo $description;?></p>
										</div>
										<div id = "lower-content">
										<p>Some image here</p>
										</div>
										<button id = "transit-down" class="btn-transit light-gray">
										<span class="down">Down To Comments and Pictures
										</button>
										</div>
										<div class="grid-3 columns">
										<div class = "grid-header">
										
										</div>
										<div class = "content">
											<img src = "../../../wp-content/themes/flat-boxy/img/sprites/location.png"/><br><?php echo $address; ?>
										</div>
										<div class = "content">
										Google maps
										</div>
										</div>
										</div>
										
										</div>
										</section>
										<?php
											echo get_the_ID();
											
											endwhile
										?>
										
										</div><!-- #main-content -->
										</section><!-- #main -->
									<?php get_footer();?>	
<script>
$(document).ready(function(){
	//TO-DO: add condition to detect when its mobile
	activityDetailMobileFn();
	activityDetailFn();
});
</script>																				