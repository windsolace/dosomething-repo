<?php
/*
 Template Name: Search Page
 */
get_header(); ?>

<?php 
$login_page= get_page_link(68);
get_search_form();
?>

<?php
global $query_string;

$query_args = explode("&", $query_string);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$search_query = array(
	'post_type' => page,
	'posts_per_page' => -1,
	'paged' => $paged
	);

foreach($query_args as $key => $string) {
	$query_split = explode("=", $string);
	$search_query[$query_split[0]] = urldecode($query_split[1]);
} // foreach

$search = new WP_Query($search_query);
?>
		<div class = "login-banner">
	    		<a href = "<?php echo $login_page?>"><span class = "login"></span></a>
	    </div>

		<div id = "body-wrapper">

			<section id = "main" class = "full">
				<div id = "main-content">
					<h2><?php echo get_the_title() ?></h2>
					<p><?php get_search_form() ?></p>
						<?php
							//Start the Loop
							while(have_posts()) : the_post();
								//Include the content from editor
								the_content();
							endwhile
						?>

				</div><!-- #main-content -->
			</section><!-- #main -->

			<?php get_footer(); ?>

		
		

