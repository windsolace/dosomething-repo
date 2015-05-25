<?php
/*
Template Name: Landing Page
 *
 * @package WordPress
 * @subpackage Flat_Boxy
 * @since Flat_Boxy v1
 */

get_header(); ?>

<div id="body-wrapper">

	<?php
		if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
			// Include the featured content template.
			get_template_part( 'featured-content' );
		}
	?>

	<!-- Start of navigation-->
	<?php include(__DIR__."/../sidenav.php"); ?>
	<!-- End of navigation -->
	
	<section id = "main" class = "full">
		<?php if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb('<p id="breadcrumbs">','</p>');
		} ?>
		<div id = "main-content">
			<h2><?php echo get_the_title() ?></h2>
			<p><?php get_search_form() ?></p>
			
			<p>
				<?php
					//Start the Loop
					while(have_posts()) : the_post();
						//Include the content from editor
						the_content();
					endwhile
				?>
			</p>
			<div id = "activity-list">
				<div class = "cell-container">
					<?php
						$current_page = $post->ID;
						//$children = get_page('child_of= '.$post->ID);
						$all_pages = list_all_pages();
						$children = get_page_children($current_page, $all_pages);
						asort($children);
						if(!empty($children)){
							for($i = 0; $i < count($children); ++$i){
								$child_page = $children[$i];

								$parent_title = get_parent_title($child_page);
								if($parent_title == get_the_title($current_page)){
									$child_page_id = $child_page->ID;
									$child_page_title =get_the_title($child_page_id);
									$child_page_link = get_permalink($child_page_id);

									?>
									<div class = "cell">
										<div class = "cell-content">
											<a href = "<?php echo $child_page_link ?>">
												<span><img class = "cell-img" src = "<?php echo_first_image($child_page_id); ?>" alt = ""/></span>
												<div class = "cell-title"><?php echo $child_page_title ?></div>
											</a>
										</div>
									</div>
								<?php
								}


							
							}	
						}
					?>
				</div><!-- #cell-container -->					
			</div><!-- #activity-list -->
		</div><!-- #main-content -->		
	</section><!-- #main -->
	<?php get_footer();?>


