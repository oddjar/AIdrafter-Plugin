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

// Add a new menu item under the "Settings" menu
add_action( 'admin_menu', 'add_aidrafter_settings_page' );
function add_aidrafter_settings_page() {
  add_options_page( 'AIdrafter', 'AIdrafter', 'manage_options', 'aidrafter', 'aidrafter_settings' );
}

// Display the settings screen
function aidrafter_settings() {
  ?>
  <div class="wrap">
    <h2><?php esc_html_e( 'AIdrafter Settings', 'aidrafter' ); ?></h2>
    <?php if ( isset( $_GET['settings-updated'] ) ) {
      echo '<div id="message" class="updated below-h2"><p>' . 
        esc_html__( 'Settings saved.', 'aidrafter' ) . '</p></div>';
      }
    ?>
    <form action="options.php" method="post">
      <?php
      settings_fields( 'aidrafter_settings_group' );
      do_settings_sections( 'aidrafter' );
      submit_button( __( 'Save Settings', 'aidrafter' ) );
      ?>
    </form>
  </div>
  <?php
}

// Register the settings section and fields
add_action( 'admin_init', 'register_aidrafter_settings' );
function register_aidrafter_settings() {
  $capability = 'manage_options';
  $option_group = 'aidrafter_settings_group';
  $option_name = 'aidrafter_api_key';
  $sanitization_callback = 'sanitize_text_field';
  
  add_settings_section(
    'aidrafter_settings_general',
    __( 'General Settings', 'aidrafter' ),
    '__return_false',
    'aidrafter'
  );
  
  add_settings_field(
    'aidrafter_api_key',
    __( 'OpenAI API Key', 'aidrafter' ),
    'aidrafter_api_key_field_callback',
    'aidrafter',
    'aidrafter_settings_general'
  );
  
  register_setting( $option_group, $option_name, array(
    'type' => 'string',
    'sanitize_callback' => $sanitization_callback,
    'default' => '',
  ) );
}

// Add the text field for the OpenAI API key
function aidrafter_api_key_field_callback() {
  $option_name = 'aidrafter_api_key';
  $value = get_option( $option_name );
  ?>
  <input type="text" name="<?php echo esc_attr( $option_name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
  <?php
}


// Validate the OpenAI API key before saving it
add_filter( 'pre_update_option_aidrafter_api_key', 'validate_aidrafter_api_key', 10, 2 );
function validate_aidrafter_api_key( $new_value, $old_value ) {
  // Add your validation logic here
  if ( ! preg_match( '/^[a-f0-9]{32}$/i', $new_value ) ) {
    add_settings_error(
      'aidrafter_api_key',
      'invalid-api-key',
      __( 'Invalid OpenAI API Key.', 'aidrafter' )
    );
    return $old_value;
  }
  return $new_value;
}