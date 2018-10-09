<?php
namespace PhotoPosts;

/**
 * Create shortcode to list posts filtered by taxonomy
 * @package Photo Gallery Post Type
 * @since 1.0.0
 */
class PostsShortcode {

	protected $post_type;
	protected $template;
	protected $atts;
	protected $taxonomy;
	protected $name = 'display_';

	/**
	 * Creates the shortcode
	 * @param string $posttype The post type slug
	 * @param string $template The path to the shortcode content template
	 * @param array  $taxonomy The taxonomy to use for post filtering via shortcode attributes. Requires
	 *                          'default', 'taxonomy', and 'field'. Default is the default shortcode
	 *                          attribute value. Taxonomy is the taxonomy slug. Field is the attribute of
	 *                          the taxonomy to select by (Possible values are 'term_id', 'name', 'slug',
	 *                          or 'term_taxonomy_id').
	 * @return void
	 */
	public function __construct( $posttype, $template, $taxonomy = array() ) {

		$this->post_type = $posttype;
		$this->template = $template;
		$this->name .= str_replace('-', '_', $posttype);

		// Copy shortcode attributes from taxonomy to atts
		if( !empty( $taxonomy ) ){
			$this->taxonomy = $taxonomy;
			$this->atts = array();
			foreach ($taxonomy as $key => $value) {
				// Set default value
				$this->atts[$key] = '';
			}
		}

		// Establish shortcode
		add_shortcode( $this->name, array( $this, 'display_posts_shortcode' ) );

	}

	/**
	 * Renders the shortcode
	 * @param  string $atts The shortcode attributes
	 * @return string       The shortcode output
	 */
	public function display_posts_shortcode( $atts ) {

		extract( shortcode_atts( $this->atts, $atts ));

		$posts = Shortcode_Post_Query::get_posts( $this->post_type, $atts, $this->taxonomy );

		ob_start();

		require $this->template;

		$output = ob_get_contents();
		ob_clean();

		return $output;

	}

}
