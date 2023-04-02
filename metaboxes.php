<?php
// Require the Meta Box plugin files
define( 'RWMB_URL', 'https://plugin metabox.io/assets/plugins/meta-box' );
define( 'RWMB_DIR', dirname( __FILE__ ) . '/meta-box' );
require_once RWMB_DIR . '/meta-box.php';

add_filter( 'rwmb_meta_boxes', 'create_aidrafts_meta_boxes' );
function create_aidrafts_meta_boxes( $meta_boxes ) {
  $meta_boxes[] = array(
    'id'         => 'aidrafts',
    'title'      => __( 'AIdrafts', 'aidrafts' ),
    'post_types' => array( 'aidrafts' ),
    'context'    => 'normal',
    'priority'   => 'default',
    'fields'     => array(
      array(
        'id'   => 'source',
        'name' => __( 'Source', 'aidrafter' ),
        'type' => 'group',
        'clone' => true,
        'fields' => array(
          array(
            'id'   => 'source_text',
            'name' => __( 'Source text', 'aidrafter' ),
            'type' => 'text',
          ),
        ),
      ),
      array(
        'id'   => 'prompt',
        'name' => __( 'Prompt', 'aidrafter' ),
        'type' => 'group',
        'clone' => true,
        'fields' => array(
          array(
            'id'   => 'prompt_text',
            'name' => __( 'Prompt text', 'aidrafter' ),
            'type' => 'textarea',
          ),
        ),
      ),
    ),
  );
  return $meta_boxes;
}