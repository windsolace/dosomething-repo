<?php 
/**
* Custom functions and definitions
* Contains helper functions
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0
*/

/**
* Lists child pages
*/
function list_all_pages(){
	$args = array(
			'post_type' => 'page',
			'orderby' => 'title',
			'order' => 'ASC'
	);

	$all_pages = get_posts($args);

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
?>