<?php
/**
 * The main custom template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Flat_Boxy
 * @since Flat_Boxy v1
 */
get_header(); ?>
<?php 
$login_page= get_page_link(68);
?>
		<div class = "login-banner">
    		<a href = "<?php echo $login_page?>"><span class = "login">Log In</span></a>
    		<a style="display:none">&nbsp|&nbsp</a>
    		<a id="login-name" href = ""><span></span></a>
	    </div>

		<div id = "body-wrapper">

			<section id = "index-main">
				<div id="index-menu">
					<h2 id = "index-greeting">What do you want to do today?</h2>
					
					<div class = "cell-container">
						<p><?php get_search_form() ?></p>
						
						<a href = "<?php echo $site_home_url."/eat" ?>">
							<div class = "homecell eat">
								<div class = "cell-content">Eat</div>
							</div>
						</a>
						<a href = "<?php echo $site_home_url."/play" ?>">
							<div class = "homecell play">
								<div class = "cell-content">Play</div>
							</div>
						</a>
						<a href = "<?php echo $site_home_url."/trend" ?>">
							<div class = "homecell trend">
								<div class = "cell-content">Trend</div>
							</div>
						</a>
						<a href = "<?php echo $site_home_url."/explore" ?>">
							<div class = "homecell explore">
								<div class = "cell-content">Explore</div>
							</div>
						</a>
					</div>
				</div>	<!--#index-menu -->
			</section>

			<?php get_footer(); ?>

		
		

