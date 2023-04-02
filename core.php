<?php
/*
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

