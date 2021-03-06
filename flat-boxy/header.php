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
<?php
	//header("Cache-Control: max-age=172800");

	//Remove header with WordPress version
	add_filter('the_generator', 'remove_version_from_head'); 

?>
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	
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

			wp_enqueue_style('timepicker', get_stylesheet_directory_uri() . '/css/libs/timepicker.css');
			wp_enqueue_style('colorbox', get_stylesheet_directory_uri() . '/css/libs/colorbox.css');
			wp_enqueue_style('hydi-style', get_stylesheet_directory_uri() . '/css/styles.css');
			wp_enqueue_style('hydi-cells', get_stylesheet_directory_uri() . '/css/cells.css');
			wp_enqueue_style('googleFont-Oxygen');
			wp_enqueue_style('googleFont-Oregano');
		} 
		//Load scripts
		function hydi_scripts(){
		    wp_deregister_script('jquery'); //Remove WP's default jQuery

		    //libs
		    wp_register_script('jquery','//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false,null,false);
		    wp_register_script('underscore', get_template_directory_uri() . '/js/libs/underscore.js', array('jquery'));
			wp_register_script('timepicker', get_template_directory_uri() . '/js/libs/timepicker.js', array('jquery'));
		    wp_register_script('colorbox', get_template_directory_uri() . '/js/libs/jquery.colorbox-min.js', array('jquery'));
		    wp_register_script('googlemaps','//maps.googleapis.com/maps/api/js', false,null,false);

		    //custom scripts
		    wp_register_script('hydi-fb', get_template_directory_uri() . '/js/hydi-fb.js', array('jquery'));
		    wp_register_script('hydi-api', get_template_directory_uri() . '/js/hydi-api.js', array('jquery'));
		    wp_register_script('hydi-main', get_template_directory_uri() . '/js/hydi-main.js', array('jquery'));
		    wp_register_script('menu-js', get_template_directory_uri() . '/js/menu-js.js', array('jquery'));
		    wp_register_script('cells', get_template_directory_uri() . '/js/cells.js', array('jquery'));
		    wp_register_script('hydi-activity', get_template_directory_uri() . '/js/hydi-activity.js', array('jquery'));
		    wp_register_script('hydi-trends', get_template_directory_uri() . '/js/hydi-trends.js', array('jquery'));

		    wp_enqueue_script('underscore');
		    wp_enqueue_script('colorbox');
		    wp_enqueue_script('googlemaps');
			wp_enqueue_script('timepicker');
		    wp_enqueue_script('hydi-fb');
		    wp_enqueue_script('hydi-api');
		    wp_enqueue_script('hydi-main');
		    wp_enqueue_script('menu-js');
		    wp_enqueue_script('cells');
		    wp_enqueue_script('hydi-activity');
		    wp_enqueue_script('hydi-trends');
		}

		//WP actions (JS/CSS ini)
		add_action('wp_enqueue_scripts', 'hydi_theme');
		add_action('wp_enqueue_scripts', 'hydi_scripts'); 

	?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<?php include_once("analyticstracking.php") ?>
	<?php include("constants.php") ?>
	<?php include("config.php") ?>
	<?php include("hydi-functions.php") ?>
	<?php include_once("ajaxHandler.php") ?>

</head>

<body <?php body_class(); ?>>
	<?php 

		$site_home_url = hydiHomeUrl;
	?>

	<script>
		//var ajaxurl = "<?php echo get_template_directory_uri().'/ajaxHandler.php'; ?>";
		var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
		var home_url = "<?php echo $site_home_url ?>";
		var HYDI_PATHTOPROFILE = home_url+"/user-profile";
		var isLogin = "";//getLoginStatus();

		window.fbAsyncInit = function() {
			FB.init({
			  appId      : <?php echo hydiFBAppId ?>,
			  xfbml      : true,
			  version    : 'v2.0',
			});
			
			getLoginStatus(getCookie('uid'), function(){
				//IF isLogin -> get fb info
				if(isLogin){
					FB.getLoginStatus(function(response) {
						//if logged in
						if (response.status === 'connected') {
							isLogin = true;
							$('.login').addClass('logout');
							$('.logout').removeClass('login').text('Log Out');
							$('.logout').parent('a').attr("href", "#"); 

							FB.api('/me', function(response) {
								//attempt to find profile-name field (on user profile page)
								$('#profile-name').text(response.name);
								first_name = response.first_name;
				                user_name = response.name; //get user email
				                $('.login-banner .logout').removeClass('login').text('Log Out');

				                //populate name in login banner and add user profile link
				      			$('#login-name').text(user_name).attr('href', HYDI_PATHTOPROFILE);
				      			$('.login-banner a').eq(1).show(); //show the pipe separator

				      			//Do greeting in index page
				      			$('#index-greeting').text("Hello " + first_name + "! What do you want to do today?");
								
				            });

							var fbuid = getCookie('uid');
							if(!fbuid){
								fbuid = FB.getUserID();
								document.cookie='uid='+fbuid;
								document.cookie=<?php echo HYDI_AUTH_KEY ?>+' ='+response.authResponse.accessToken;
							}

							//get profile pic
							FB.api(
							    "/" + fbuid + "/picture?width=110",
							    function (response) {
									if (response && !response.error) {
										$('#profile-pic img').attr('src', response.data.url);
									}
							    }
							);
						}
						
					});
				//if logged out
				} else {
					//fb_logout();
					$('.login-banner a').eq(1).hide(); //hide the pipe separator
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
						<a href = "./hydi-trends"><div class= "mobile-menu-button trend">Trend</div></a>
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
