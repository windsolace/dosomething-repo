<?php
/**
 * Template Name: Log In Template
 *
 * Specifically used for Log In. Has its own layout.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Flat_Boxy
 * @since Flat_Boxy v1
 */
get_header(); ?>

		<div id = "body-wrapper">
			<div class = "s-left">
				<h1>Do Something Now</h1>
				<div class = "description">
					<?php
						//Start the Loop
						while(have_posts()) : the_post();
							//Include the content from editor
							the_content();
						endwhile
					?>
					
				</div>
			</div>
			<div class = "s-right">
				<h1><?php echo get_the_title() ?></h1>
				<ul class = "login-btn-group">
					<li><a href = "#" class = "btn-col">
						<div class = "btn-block"><span class = "fb-icon"></span><span class = "login"></span></div>
					</a></li>
				</ul>
			</div>	

			<?php get_footer(); ?>


