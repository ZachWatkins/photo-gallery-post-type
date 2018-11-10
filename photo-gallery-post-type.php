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
define( 'PHOTOPOSTS_NAMESPACE', 'pgpt' );

if( !defined( 'PHOTOPOSTS_POST_TYPE_SLUG' ) ){
  define( 'PHOTOPOSTS_POST_TYPE_SLUG', 'photo-post' );
}

add_image_size( 'photo-posts-preview', 400, 400, array( 'center', 'center' ) );

// Code for plugins
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'photoposts_activation' );
function photoposts_activation() {
  if ( ! get_option( 'photoposts_flush_rewrite_rules_flag' ) ) {
    add_option( 'photoposts_flush_rewrite_rules_flag', true );
  }
}

add_action( 'init', function(){

  if ( class_exists( 'acf' ) ) {
    require_once(PHOTOPOSTS_DIR_PATH . 'fields/album_tax_fields.php');
  }

  // Add taxonomies
  $taxonomy_album = new \PhotoPosts\Taxonomy(
    'Album', 'album', PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE,
    array(
      'hierarchical' => true,
      'publicly_queryable' => true,
      'rewrite' => array(
        'hierarchical' => true
      )
    )
  );

  $taxonomy_color = new \PhotoPosts\Taxonomy(
    'Color', 'color', PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE,
    array('hierarchical' => false) );

  $taxonomy_subject = new \PhotoPosts\Taxonomy(
    'Subject', 'subject', PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE,
    array('hierarchical' => false) );

  $taxonomy_size = new \PhotoPosts\Taxonomy(
    'Size', 'size', PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE,
    array('hierarchical' => false) );

  $taxonomy_orientation = new \PhotoPosts\Taxonomy(
    'Orientation', 'orientation', PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE,
    array('hierarchical' => false) );

  $additional_taxonomies = apply_filters( PHOTOPOSTS_POST_TYPE_SLUG . '_additional_taxonomies', array() );

  foreach ($additional_taxonomies as $key => $value) {
    new \PhotoPosts\Taxonomy(
    $key, strtolower($key), PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE,
    $value );
  }

  // Add custom post type
  $post_type = new \PhotoPosts\PostType(
    'Photo', PHOTOPOSTS_POST_TYPE_SLUG, PHOTOPOSTS_NAMESPACE, array(
      'album', 'color', 'size', 'orientation', 'subject'
    ), 'dashicons-portfolio',
    array(
      'title', 'editor', 'thumbnail', 'revisions', 'genesis-seo', 'genesis-layouts', 'genesis-scripts'
    )
  );

  if ( get_option( 'photoposts_flush_rewrite_rules_flag' ) ) {
      flush_rewrite_rules();
      delete_option( 'photoposts_flush_rewrite_rules_flag' );
  }

  $post_list_content = new \PhotoPosts\PostListContent( PHOTOPOSTS_POST_TYPE_SLUG );
  $single_post_content = new \PhotoPosts\SinglePostContent( PHOTOPOSTS_POST_TYPE_SLUG );

  // Add custom post type list shortcode
  $display_posts_shortcode = new \PhotoPosts\PostsShortcode(
    PHOTOPOSTS_POST_TYPE_SLUG,
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
    PHOTOPOSTS_POST_TYPE_SLUG,
    PHOTOPOSTS_TEMPLATE_PATH . '/shortcode-albums.php'
  );

  if( function_exists('genesis') ){
    add_post_type_support( PHOTOPOSTS_POST_TYPE_SLUG, 'genesis-cpt-archives-settings' );
  }

});

// Customize photo post list page in admin
function pgpt_set_custom_photo_post_columns( $columns ) {
  $value = array('photo' => __( 'Photo', PHOTOPOSTS_NAMESPACE ) );
  $oldColumns = $columns;
  $columns = array_slice( $oldColumns, 0, 1, true ) + $value + array_slice( $oldColumns, 1, NULL, true );

  return $columns;
}

function pgpt_custom_photo_post_column( $column, $post_id ) {
  if( $column == 'photo' ){
    echo get_the_post_thumbnail( $post_id, 'thumbnail' );
  }
}

function pgpt_register_sortable_columns($columns) {
  
  $taxes = get_object_taxonomies( PHOTOPOSTS_POST_TYPE_SLUG );

  foreach ($taxes as $key => $value) {
    $columns["taxonomy-$value"] = "taxonomy-$value";
  }

  return $columns;
}

function pgpt_taxonomy_orderby( $orderby, $wp_query ) {
  global $wpdb;
  
  $taxes = get_object_taxonomies( PHOTOPOSTS_POST_TYPE_SLUG, 'objects' );
  $taxonomies = array();
  
  foreach ($taxes as $key => $value) {
    $taxonomies["taxonomy-$key"] = $value->labels->singular_name;
  }

  if ( isset( $wp_query->query['orderby'] ) && array_key_exists($wp_query->query['orderby'], $taxonomies) ) {
    $tax_key = $wp_query->query['orderby'];
    $taxonomy = $taxonomies[ $tax_key ];
    
    $orderby = "(
      SELECT GROUP_CONCAT(name ORDER BY name ASC)
      FROM $wpdb->term_relationships
      INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
      INNER JOIN $wpdb->terms USING (term_id)
      WHERE $wpdb->posts.ID = object_id
      AND taxonomy = '{$taxonomy}'
      GROUP BY object_id
    ) ";
    $orderby .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
  }

  return $orderby;
}

add_filter( 'manage_' . PHOTOPOSTS_POST_TYPE_SLUG . '_posts_columns', 'pgpt_set_custom_photo_post_columns' );
add_action( 'manage_' . PHOTOPOSTS_POST_TYPE_SLUG . '_posts_custom_column' , 'pgpt_custom_photo_post_column', 10, 2 );
add_filter( 'manage_edit-' . PHOTOPOSTS_POST_TYPE_SLUG . '_sortable_columns', 'pgpt_register_sortable_columns' );
add_filter( 'posts_orderby', 'pgpt_taxonomy_orderby', 10, 2 );

// Queue public assets
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

// Queue admin assets
add_action( 'admin_enqueue_scripts', 'pgpt_project_admin_enqueue' );
function pgpt_project_admin_enqueue(){

  wp_register_style(
      'photo-posts-admin-styles',
      PHOTOPOSTS_DIR_URL . 'css/photo-posts-admin.css',
      array(),
      filemtime(PHOTOPOSTS_DIR_PATH . 'css/photo-posts-admin.css'),
      'screen'
  );

  wp_enqueue_style( 'photo-posts-admin-styles' );

}
