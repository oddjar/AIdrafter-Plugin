<?php

add_action( 'init', 'create_aidrafts_post_type' );
function create_aidrafts_post_type() {
  register_post_type( 'aidrafts',
    array(
      'labels' => array(
        'name' => __( 'AiDrafts' ),
        'singular_name' => __( 'AiDraft' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
      'register_meta_box_cb' => 'add_aidrafts_metaboxes'
    )
  );
}

function add_aidrafts_metaboxes() {
  add_meta_box(
    'source',
    'Source',
    'source_meta_box',
    'aidrafts',
    'normal',
    'default'
  );
  add_meta_box(
    'prompt',
    'Prompt',
    'prompt_meta_box',
    'aidrafts',
    'normal',
    'default'
  );
}

function source_meta_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'aidrafts_fields' );
  $source = get_post_meta( $post->ID, 'source', true );
  echo '<input type="text" id="source" name="source" value="' . esc_attr( $source ) . '">';
}

function prompt_meta_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'aidrafts_fields' );
  $prompt = get_post_meta( $post->ID, 'prompt', true );
  echo '<textarea id="prompt" name="prompt">' . esc_attr( $prompt ) . '</textarea>';
}

add_action( 'save_post_aidrafts', 'save_aidrafts_meta', 10, 2 );
function save_aidrafts_meta( $post_id, $post ) {
  if ( !isset( $_POST['aidrafts_fields'] ) || !wp_verify_nonce( $_POST['aidrafts_fields'], plugin_basename( __FILE__ ) ) )
    return;

  if ( !current_user_can( 'edit_post', $post_id ) )
    return;

  $source = $_POST['source'];
  $prompt = $_POST['prompt'];

  update_post_meta( $post_id, 'source', $source );
  update_post_meta( $post_id, 'prompt', $prompt );
}