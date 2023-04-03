<?php
/*;
Plugin Name: AIdrafter
Plugin URI: https://example.com
Description: This is a sample WordPress plugin for AIdrafter.
Version: 1.0.0
Author: AIdrafter
Author URI: https://example.com
License: GPLv2 or later
Text Domain: aidrafter
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( 'inc/box.php' );
require_once( 'inc/cpt.php' );
require_once( 'inc/settings.php' );


function on_publish_update_aidrafter_post( $post_id, $post ) {
  if ( $post->post_type == 'ai_drafter' && ( $post->post_status == 'publish' || $post->post_status == 'future' ) ) {

    // get the value of prompt meta field
    $prompt = get_post_meta($post_id, 'prompt', true);

	if( !$prompt ) {
	        return;
	}

    // set up the API request
    $url = 'https://api.openai.com/v1/completions';
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . 'YOUR_API_SECRET_KEY'
    );
    $data = array(
        'prompt' => $prompt,
        'max_tokens' => 16
    );
    $data_string = json_encode($data);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $data_string
    ));

    $result = curl_exec($curl);

    curl_close($curl);

    // process $result as needed
  }
}

add_action('publish_post', 'on_publish_update_aidrafter_post', 10, 2);
add_action('future_post', 'on_publish_update_aidrafter_post', 10, 2);
