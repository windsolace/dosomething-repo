<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Flat_Boxy
 * @since Flat_Boxy v1
 */

get_header(); ?>
	<section id = "main" class = "full">
		<div id = "main-content">
			<h2><?php _e( 'Error 404: Page Not Found', 'hydi' ); ?></h2>

			<h3><?php _e( 'This is somewhat embarrassing, isn’t it?', 'hydi' ); ?></h3>
			<p><?php _e( 'How about returning to our Home page ', 'hydi' );?><a href = "<?php echo esc_url( home_url( '/' ) ); ?>">HERE</a> ?</p>

		</div>
	</section>
<?php get_footer(); ?>