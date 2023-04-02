<?php

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
  add_settings_field(
    'aidrafter_user_id',
    __( 'User ID', 'aidrafter' ),
    'aidrafter_user_id_field_callback',
    'aidrafter',
    'aidrafter_settings_general'
  );
  
  register_setting( $option_group, $option_name, array(
    'type' => 'string',
    'sanitize_callback' => $sanitization_callback,
    'default' => '',
  ) );
  
  register_setting( $option_group, 'aidrafter_user_id', array(
    'type' => 'integer',
    'sanitize_callback' => 'absint',
    'default' => 0,
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

/**
 * Validate the OpenAI API key before saving it
 *
 * @param string $new_value The new value of the option
 * @param string $old_value The old value of the option
 * @return string The validated value
 */
function validate_aidrafter_api_key( $new_value, $old_value ) {
  $api_key = $new_value;
  $url = 'https://api.openai.com/v1/engines';
  $args = array(
    'headers' => array(
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $api_key,
    ),
  );

  // Send a request to the OpenAI API to check the status of the key
  $response = wp_remote_get( $url, $args );

  // If the response is an error, return the old value
  if ( is_wp_error( $response ) ) {
    add_settings_error(
      'aidrafter_api_key',
      'invalid-api-key',
      __( 'Error validating the OpenAI API Key.', 'aidrafter' )
    );
    return $old_value;
  }

  // If the response is successful, return the new value
  $response_body = json_decode( $response['body'], true );
  if ( isset( $response_body['data'] ) ) {
    return $new_value;
  } else {
    add_settings_error(
      'aidrafter_api_key',
      'invalid-api-key',
      __( 'Invalid OpenAI API Key.', 'aidrafter' )
    );
    return $old_value;
  }
}

add_filter( 'pre_update_option_aidrafter_api_key', 'validate_aidrafter_api_key', 10, 2 );

// Add the dropdown field for the user ID
function aidrafter_user_id_field_callback() {
  $option_name = 'aidrafter_user_id';
  $value = get_option( $option_name );
  $users = get_users();
  ?>
  <select name="<?php echo esc_attr( $option_name ); ?>">
    <?php foreach ( $users as $user ) { ?>
      <option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $value, $user->ID ); ?>><?php echo esc_html( $user->display_name ); ?></option>
    <?php } ?>
  </select>
  <?php
}