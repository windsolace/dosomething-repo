<?php
/**
 * The Header for custom theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Flat_Boxy
 * @since Flat_Boxy v1
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<?php
		header("Cache-Control: max-age=172800");
	?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!--<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">-->

	<!--End different viewports -->

	<?php
		//Load themes
		function hydi_theme(){
			wp_register_style('googleFont-Oxygen','http://fonts.googleapis.com/css?family=Oxygen');
			wp_register_style('googleFont-Oregano','http://fonts.googleapis.com/css?family=Oregano');

			wp_enqueue_style('hydi-style', get_stylesheet_directory_uri() . '/style.css');
			wp_enqueue_style('hydi-cells', get_stylesheet_directory_uri() . '/cells.css');
			wp_enqueue_style('googleFont-Oxygen');
			wp_enqueue_style('googleFont-Oregano');
		} 
		//Load scripts
		function hydi_scripts(){
		    wp_deregister_script('jquery'); //Remove WP's default jQuery
		    wp_register_script('jquery','//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false,null,false);
		    wp_register_script('menu-js', get_template_directory_uri() . '/js/menu-js.js', array('jquery'));
		    wp_register_script('cells', get_template_directory_uri() . '/js/cells.js', array('jquery'));

		    wp_enqueue_script('menu-js');
		    wp_enqueue_script('cells');
		}
		add_action('wp_enqueue_scripts', 'hydi_theme');
		add_action('wp_enqueue_scripts', 'hydi_scripts'); 
	?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<?php include_once("analyticstracking.php") ?>
</head>

<body <?php body_class(); ?>>
	<?php 

		$site_home_url = get_blogaddress_by_id(get_current_blog_id()); 
	?>

	<script>
		var home_url = "<?php echo $site_home_url ?>";

		window.fbAsyncInit = function() {
			FB.init({
			  appId      : '337876719693977',
			  xfbml      : true,
			  version    : 'v2.0',
			});

			//on page load, check if fb logged in
			FB.getLoginStatus(function(response) {
				//if logged in
				if (response.status === 'connected') {
					$('.login').addClass('logout');
					$('.logout').removeClass('login').text('Log Out');
					$('.logout').parent('a').attr("href", "#"); 

					var fbuid = sessionStorage.getItem('fbuid');
					if(!fbuid){
						fbuid = FB.getUserID();
						sessionStorage.setItem('fbuid',fbuid);
					}

					FB.api('/me', function(response) {
						first_name = response.first_name;
		                user_name = response.name; //get user email
		                $('.login-banner .logout').removeClass('login').text(user_name + ' | Log Out');
		      			$('#index-greeting').text("Hello " + first_name + "! What do you want to do today?");
						
		            });
				}
				//if logged out
				else {
					$('.logout').addClass('login');
					$('.login').removeClass('logout').text('Log In');
				}
			});


		};

		(function(d, s, id){
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "https://connect.facebook.net/en_US/sdk.js";
		 fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

		
    </script>
	<div id="page" class="hfeed site">
		<?php if ( get_header_image() ) : ?>
		<section id="site-header">
			<a href="<?php echo $site_home_url ?>" rel="home">
				<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="">
			</a>
			<p>Adding social to life</p>
		</section>
		<?php endif; ?>

		<!-- Mobile Nav -->
		<section id = "mobile-nav">
			<div class = "mobile-nav-list-wrapper closed">
				<ul class = "mobile-nav-list">
					<li>
						<a href = "<?php echo esc_url( home_url( '/' ) ); ?>"><div class= "mobile-menu-button">Home</div></a>
					</li>
					<li>
						<a href = "./play"><div class= "mobile-menu-button play">Play</div></a>
					</li>
					<li>
						<a href = "./trend"><div class= "mobile-menu-button trend">Trend</div></a>
					</li>
					<li>
						<a href = "./explore"><div class= "mobile-menu-button explore">Explore</div></a>
					</li>
					<li>
						<a href = "./about"><div class= "mobile-menu-button">About</div></a>
					</li>
					<li>
						<a href = "./contact"><div class= "mobile-menu-button">Contact</div></a>
					</li>
					<li>
						<a class = "login" href = "#"><span class= "mobile-menu-button">Log In</span></a>
					</li>
				</ul>
			</div>
			<div class = "nav-stick-right">
				<a href = "javascript:void(0)">
					<span id = "mobile-menu-icon" class = "mobile-nav-icon"><img class = "menu"/></span>
				</a>
				
			</div>
		</section>
		<!-- Mobile Nav -->
		<section id="home-header" class="site-header" role="banner">
			<h1 class="site-title"><a href="<?php echo $site_home_url ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<p>Adding social to life</p>
			</h1>
		</section><!-- #masthead -->
