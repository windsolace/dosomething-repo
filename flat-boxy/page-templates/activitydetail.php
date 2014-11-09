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
	<?php include(__DIR__."/../sidenav.php"); ?>
	<!-- End of navigation -->
	
	<section id = "main" class = "full">
		<div id = "main-content">
			<h2><?php echo get_the_title() ?></h2>
			
				<?php
					//Start the Loop
					while(have_posts()) : the_post();
						//Include the content from editor
						the_content();
					endwhile
				?>
			

		</div><!-- #main-content -->
	</section><!-- #main -->
	<?php get_footer();?>