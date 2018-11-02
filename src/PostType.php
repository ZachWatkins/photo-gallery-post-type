<?php
namespace PhotoPosts;

/**
 * Builds and registers a custom post type.
 * @package Photo Gallery Post Type
 * @since 1.0.0
 */
class PostType {

	/**
	 * Builds and registers the custom taxonomy.
	 * @param  string $name       The post type name.
	 * @param  string $slug       The post type slug.
	 * @param  string $tag        The namespace of the plugin for translation purposes.
	 * @param  array  $taxonomies The taxonomies this post type supports. Accepts arguments found in
	 *                            WordPress core register_post_type function.
	 * @param  string $icon       The icon used in the admin navigation sidebar.
	 * @param  array  $supports   The attributes this post type supports. Accepts arguments found in
	 *                            WordPress core register_post_type function.
	 * @return void
	 */
	public function __construct( $name, $slug, $tag, $taxonomies = array( 'category', 'post_tag' ), $icon = 'dashicons-portfolio', $supports = array( 'title' ) ) {

		$singular = $name;
		$plural = $name . 's';

		// Backend labels
		$labels = array(
			'name' => __( $plural, $tag ),
			'singular_name' => __( $plural, $tag ),
			'add_new' => __( 'Add New', $tag ),
			'add_new_item' => __( 'Add New ' . $singular, $tag ),
			'edit_item' => __( 'Edit ' . $singular, $tag ),
			'new_item' => __( 'New ' . $singular, $tag ),
			'view_item' => __( 'View ' . $singular, $tag ),
			'search_items' => __( 'Search ' . $plural, $tag ),
			'not_found' => __( 'No ' . $plural . ' Found', $tag ),
			'not_found_in_trash' => __( 'No ' . $plural . ' found in trash', $tag ),
			'parent_item_colon' => '',
			'menu_name' => __( $plural, $tag ),
		);

		// Post type arguments
		$args = array(
			'can_export' => true,
			'has_archive' => true,
			'labels' => $labels,
			'menu_icon' => $icon,
			'menu_position' => 5,
			'public' => true,
			'publicly_queryable' => true,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'supports' => $supports,
			'taxonomies' => $taxonomies
		);

		// Register the Reports post type
		register_post_type( $slug, $args );

	}

}
