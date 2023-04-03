<?php

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
        'clone' => false,
        'fields' => array(
          array(
            'id'   => 'prompt_text',
            'name' => __( 'Prompt text', 'aidrafter' ),
            'type' => 'textarea',
          ),
        ),
      ),
      array(
        'id'   => 'disclaimer',
        'name' => __( 'Disclaimer', 'aidrafter' ),
        'type' => 'textarea',
        // any other properties you want to add to the field
      ),
    ),
  );
  return $meta_boxes;
}