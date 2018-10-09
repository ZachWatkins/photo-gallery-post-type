<?php
namespace PhotoPosts;

/**
 * Get posts for shortcode by post type and taxonomy
 * @package Photo Gallery Post Type
 * @since 1.0.0
 */
class Shortcode_Post_Query {

	/**
	 * Queries for the custom post list shortcode
	 * @param	string $post_type The slug for the custom post type
	 * @param	array  $atts      The shortcode attributes in use
	 * @param array  $taxonomy  The taxonomy to use for post filtering via shortcode attributes. Accepts
	 *                          'default', 'taxonomy', and 'field'. Default is the default shortcode
	 *                          attribute value. Taxonomy is the taxonomy slug. Field is the attribute of
	 *                          the taxonomy to select by (Possible values are 'term_id', 'name', 'slug',
	 *                          or 'term_taxonomy_id').
	 * @return object				  A WP_Query object with the results
	 */
	public static function get_posts( $post_type = 'posts', $atts = array(), $taxonomy = array() ) {

		// Set default arguments for every People query
		$args = array(
			'post_type'			 => $post_type,
			'post_status'		 => 'any',
			'posts_per_page' => -1,
			'orderby'			   => 'title',
			'order'					 => 'ASC'
		);

		if( !empty( $atts ) && !empty( $taxonomy ) ){

			$args['tax_query'] = array();

			foreach ($taxonomy as $key => $value) {

				if( array_key_exists( $key, $atts ) ){

					$args['tax_query'][] = array(
						'taxonomy' => $value['taxonomy'],
						'field'    => $value['field'],
						'terms'    => explode( ',', $atts[$key] )
					);

				}

			}

		}

		return new \WP_Query( $args );

	}

}
