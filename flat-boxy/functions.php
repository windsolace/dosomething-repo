<?php 
/**
* Custom functions and definitions
* Contains helper functions
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0
*/
require("ajaxHandler.php");
/**
* Lists child pages
*/
function list_all_pages(){
	$args = array(
			'post_type' => 'page',
			'sort_column' => 'title',
			'sort_order' => 'ASC'
	);

	$all_pages = get_pages($args);
	//$all_pages = wp_list_pages($args);

	return $all_pages;
}

/**
*Get the id of a page by its name
**/
function get_page_id($page_name){
	global $wpdb;
	$page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name;
}

/**
*Get the permalink of a page by its id
**/
function get_page_permalink($page_name){
	$page_id = get_page_id($page_name);
	$page_permalink = get_permalink($page_id);
	return $page_permalink;
}

/**
*Get the first image of a post
**/
function echo_first_image( $postID ) {
	$args = array(
		'numberposts' => 1,
		'order' => 'ASC',
		'post_mime_type' => 'image',
		'post_parent' => $postID,
		'post_status' => null,
		'post_type' => 'attachment',
	);

	$attachments = get_children( $args );

	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {

			//echo wp_get_attachment_thumb_url( $attachment->ID );
			echo wp_get_attachment_url( $attachment->ID, 'full');

		}
	}
}

/**
*Get the parent page title
**/
function get_parent_title($post) {
	$parent_title = get_the_title($post->post_parent);
	return $parent_title;
}

/**
*Get the parent page title
**/
function get_template_name() {
	$page_template_path = get_page_template();
	$extension_length = strlen(substr($page_template_path, strrpos($page_template_path, '.')));
	$page_template_name = substr($page_template_path, strrpos($page_template_path, '/')+1, -$extension_length);
	return $page_template_name;
}

/**
*Breadcrumbs
**/
function the_breadcrumb() {
    global $post;
    echo '<ul id="breadcrumbs">';
    if (!is_home()) {
        echo '<li><a class = "blk-arrow" href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo '</a></li><li class="separator"> / </li>';
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li class="separator"> / </li><li> ');
            if (is_single()) {
                echo '</li><li class="separator"> / </li><li>';
                the_title();
                echo '</li>';
            } ?><script>console.log("in breadcrumbs");</script><?php
            return the_breadcrumb();
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a class = "blk-arrow" href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">/</li>';
                }
                echo $output;
                echo '<strong class = "blk-arrow" title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<li><strong> '.get_the_title().'</strong></li>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
    elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
    elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
    elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
    elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
    echo '</ul>';
}

/*
* Log a message to front end
*/
function consolelog($message){
    $obj = new stdClass();
    $obj -> message = $message;

    echo json_encode($obj);
}

/************************ AUTHENTICATION SECTION ************************/
/*
* Authenticates the user using cookie.sessionID and database user.sessionID
* @params sessionID - FB's accessToken or genSessionID()
*/
function isAuthenticated($userid){
    $activeSession = getSession($userid);
    $browserSession = $_COOKIE[HYDI_AUTH_KEY];
    $isAuthenticated = false;

    if($browserSession === $activeSession){
        $isAuthenticated = true;
    }

    $jsonObj = new stdClass();
    $jsonObj->userid = $userid;
    $jsonObj->isLoggedIn = $isAuthenticated;
    
    return json_encode($jsonObj);
}

/*
* Store sessionID into database (DO NOT USE ON FRONT END)
* @params sessionID - FB's accessToken or genSessionID()
*/
function storeSessionID($userid, $sessionID){
    global $wpdb;

    //Store into database
    $wpdb->update(
        TABLE_HYDI_USERS,
        array(
            'sessionid'=> $sessionID
        ),
        array(
            'fbuid'    =>$userid,
        )
    );
};

/*
* GET session from database (DO NOT USE ON FRONT END)
* @params userid - currently only support fbuid (22 April 2015)
* used in isAuthenticated()
*/
function getSession($userid){
    global $wpdb;

    //GET sessionid from database
    $sessionid = $wpdb->get_var("SELECT sessionid FROM ".TABLE_HYDI_USERS." WHERE fbuid = '".$userid."'");

    return $sessionid;
};

/*
* Generate Unique Session ID
* @params maxLength - determine the length of the sessionID
*/
function genSessionID($maxLength = null){
    $entropy = '';

    // try ssl first
    if (function_exists('openssl_random_pseudo_bytes')) {
        $entropy = openssl_random_pseudo_bytes(64, $strong);
        // skip ssl since it wasn't using the strong algo
        if($strong !== true) {
            $entropy = '';
        }
    }

    // add some basic mt_rand/uniqid combo
    $entropy .= uniqid(mt_rand(), true);

    // try to read from the windows RNG
    if (class_exists('COM')) {
        try {
            $com = new COM('CAPICOM.Utilities.1');
            $entropy .= base64_decode($com->GetRandom(64, 0));
        } catch (Exception $ex) {
        }
    }

    // try to read from the unix RNG
    if (is_readable('/dev/urandom')) {
        $h = fopen('/dev/urandom', 'rb');
        $entropy .= fread($h, 64);
        fclose($h);
    }

    $hash = hash('whirlpool', $entropy);
    if ($maxLength) {
        return substr($hash, 0, $maxLength);
    }
    return $hash;
}

/************************ END AUTHENTICATION SECTION ************************/

/*
* GET ajax request and pass data to function to be processed
*/
add_action('wp_ajax_nopriv_callHydiApi', 'callHydiApi');
add_action('wp_ajax_callHydiApi', 'callHydiApi');
function callHydiApi(){
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        $requestPath = $_GET["requestPath"];
        $data = $_GET["params"];

    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $requestPath = $_POST["requestPath"];
        $data = $_POST["params"];
    }
    $data = json_encode($data);
    routeRequest($requestPath, $data);
    die();
}

/*
* Remove metatag with wordpress version from head
* To be used at header.php with in add_filter();
*/
function remove_version_from_head() { 
    return ''; 
} 
?>