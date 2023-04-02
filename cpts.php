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
      'supports' => array( 'title', 'thumbnail' )
    )
  );
}

