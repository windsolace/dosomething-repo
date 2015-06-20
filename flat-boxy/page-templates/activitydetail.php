<?php
	/*
		* Template Name: Activity Detail
		*
		* @package WordPress
		* @subpackage Flat_Boxy
		* @since Flat_Boxy v1
	*/
	
get_header(); ?>
<?php if ( function_exists('yoast_breadcrumb') ) {
	yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?>
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
		<div id = "main-banner" class="gray">
			<img src = "<?php echo_first_image(get_the_ID()); ?>" alt = ""/>
		</div>
		<span id = "img-credit">
			<?php 
				$imgcredit = get_attachment_metadata(get_the_ID(), "description");
				if($imgcredit){
					echo "Source: ".$imgcredit;
				}
			?>
		</span>
		<div id = "main-content">			
			<?php
				//Start the Loop
				while(have_posts()) : the_post();
				//Include the content from editor
				//the_content();
			?>

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
					
					<?php //review template ?>
					<script id = "activity-reviews" type = "text/html">
						<ul class="no-list rate-list">
							<li><span class="icon up-arrow"><span><%= data.reviews.upvotes %></span></span></li>
							<li><span class="icon down-arrow"><span><%= data.reviews.downvotes %></span></span></li>
							<li><span class="icon tick-mark"><span><%= data.reviews.done %></span></span></li>
						</ul>
					</script>
					<div id = "review-content" class = "content">
						
					</div>

					<?php if($phone){ ?>
						<div class = "content">
								<span class="icon-medium phone float-left"></span>
								<span class = "float-left detail-content-width"><?php echo $phone; ?></span>
						</div>
					<?php } ?>

					<?php if($time_range){ ?>
						<div class = "content">
								<span class="icon-medium clock float-left"></span>
								<span class = "float-left detail-content-width"><?php echo $time_range; ?></span>
						</div>
					<?php } ?>

					<?php if($website){ ?>
						<div class = "content">
								<span class="icon-medium globe"><span><a href = "<?php echo $website; ?>">Website</a></span></span>
						</div>
					<?php } ?>
				</div>
				<div class="grid-6 columns">
					<div class = "grid-header">
						<span>Information</span>
					</div>
					<button id = "transit-up" class = "btn-transit light-gray" style = "display:none" disabled>
						<span class="up"></span>Up To Description
					</button>
					<div id = "upper-content">
						<p></p>
						<p>
							<?php the_content(); ?>
							<?php echo $description;?>
						</p>
						<div class="fb-share-button" data-href="<?php echo get_permalink($post->ID); ?>" data-layout="button_count"></div>
						<p>
							<?php the_modified_date($d,"Last updated: ","",true); ?> 
						</p>
					</div>
					<div id = "lower-content">
						<p>No image available.</p>
						<div class="fb-comments" data-href="<?php echo get_permalink($post->ID); ?>" data-numposts="5" data-width="100%"></div>
					</div>
					<button id = "transit-down" class="btn-transit light-gray">
						<span class="down">Down To Comments and Pictures</span>
					</button>
				</div>
				<div class="grid-3 columns">
					<div class = "grid-header">
						Location
					</div>

					<script id = "activity-address" type = "text/html">
						<span class="icon-medium location-marker float-left"></span>
						<span class = "float-left detail-content-width" title = "<%= data.address %>">
						 <%= data.address %>
						</span>
					</script>
					<div id = "address-content" class = "content">
					</div>

					<div id = "activity-gmap" class = "content" style="display:none">
						
					</div>
				</div>
			</div>
			<?php
				//echo get_the_ID();
				
				endwhile
			?>
		</div><!-- #main-content -->
	</section><!-- #main -->
	<?php get_footer();?>
</div>

										
<script>
$(document).ready(function(){
	//TO-DO: add condition to detect when its mobile
	activityDetailMobileFn();
	activityDetailFn(<?php echo $object_id ?>);
});
</script>																				