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
$search_query = array();

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

			<section id = "index-main">
				<div id="index-menu">
					<h2 id = "index-greeting">Search</h2>
					
					<div class = "cell-container">
						
						<a href = "<?php echo $site_home_url."/eat" ?>">
							<div class = "homecell eat">
								<div class = "cell-content">Eat</div>
							</div>
						</a>
						<a href = "<?php echo $site_home_url."/play"?>">
							<div class = "homecell play">
								<div class = "cell-content">Play</div>
							</div>
						</a>
						<a href = "<?php echo $site_home_url."/trend"?>">
							<div class = "homecell trend">
								<div class = "cell-content">Trend</div>
							</div>
						</a>
						<a href = "<?php echo $site_home_url."/explore"?>">
							<div class = "homecell explore">
								<div class = "cell-content">Explore</div>
							</div>
						</a>
					</div>
				</div>	<!--#index-menu -->
			</section>

			<?php get_footer(); ?>

		
		

