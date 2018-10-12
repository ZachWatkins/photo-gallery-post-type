<?php
/**
 * Plugin Name: Photo Gallery Post Type
 * Plugin URI: https://github.com/ZachWatkins/photo-gallery-post-type
 * Description: Photo Gallery plugin using custom post types and taxonomies
 * Version: 1.0.0
 * Author: Zach Watkins
 * Author URI: http://github.com/ZachWatkins
 * Author Email: watkinza@gmail.com
 * License: GPL2+
 */

require 'vendor/autoload.php';

define( 'PHOTOPOSTS_DIRNAME', 'photo-gallery-post-type' );
define( 'PHOTOPOSTS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PHOTOPOSTS_DIR_FILE', __FILE__ );
define( 'PHOTOPOSTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'PHOTOPOSTS_TEMPLATE_PATH', PHOTOPOSTS_DIR_PATH . 'view' );

add_image_size( 'photo-posts-preview', 400, 400, array( 'center', 'center' ) );

add_action( 'init', function(){

  // flush_rewrite_rules();

  $post_type_slug = 'photo-gallery-zw';
  $namespace = 'pgpt';

  // Add taxonomies
  $taxonomy_album = new \PhotoPosts\Taxonomy(
    'Album', 'album', $post_type_slug, $namespace,
    array('hierarchical' => true, 'show_admin_column' => true) );
  
  $taxonomy_color = new \PhotoPosts\Taxonomy(
    'Color', 'color', $post_type_slug, $namespace,
    array('hierarchical' => false) );
  
  $taxonomy_subject = new \PhotoPosts\Taxonomy(
    'Subject', 'subject', $post_type_slug, $namespace,
    array('hierarchical' => false) );
  
  $taxonomy_size = new \PhotoPosts\Taxonomy(
    'Size', 'size', $post_type_slug, $namespace,
    array('hierarchical' => false) );
  
  $taxonomy_orientation = new \PhotoPosts\Taxonomy(
    'Orientation', 'orientation', $post_type_slug, $namespace,
    array('hierarchical' => false) );

  // Add custom post type
  $post_type = new \PhotoPosts\PostType(
    'Photo', $post_type_slug, $namespace, array(
      'album', 'color', 'size', 'orientation', 'subject'
    ), 'dashicons-portfolio',
    array(
      'title', 'editor', 'thumbnail', 'revisions', 'genesis-seo', 'genesis-layouts', 'genesis-scripts'
    )
  );

  $post_list_content = new \PhotoPosts\PostListContent( $post_type_slug );
  $single_post_content = new \PhotoPosts\SinglePostContent( $post_type_slug );

  // Add custom post type list shortcode
  $display_posts_shortcode = new \PhotoPosts\PostsShortcode(
    $post_type_slug,
    PHOTOPOSTS_TEMPLATE_PATH . '/shortcode-posts.php',
    array(
      'album' => array(
        'default' => '',
        'taxonomy' => 'album',
        'field' => 'slug'
      )
    )
  );

  // Add album list shortcode
  $display_albums_shortcode = new \PhotoPosts\AlbumsShortcode(
    $post_type_slug,
    PHOTOPOSTS_TEMPLATE_PATH . '/shortcode-albums.php'
  );

});

// Queue assets
add_action( 'wp_enqueue_scripts', 'pgpt_project_register' );
add_action( 'wp_enqueue_scripts', 'pgpt_project_enqueue' );

function pgpt_project_register(){

    wp_register_style(
        'photo-posts-styles',
        PHOTOPOSTS_DIR_URL . 'css/photo-posts.css',
        array(),
        filemtime(PHOTOPOSTS_DIR_PATH . 'css/photo-posts.css'),
        'screen'
    );

}

function pgpt_project_enqueue(){

    wp_enqueue_style( 'photo-posts-styles' );

}