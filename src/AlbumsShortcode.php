<?php
namespace PhotoPosts;

/**
 * Create shortcode to list posts filtered by taxonomy
 * @package Photo Gallery Post Type
 * @since 1.0.0
 */
class AlbumsShortcode {

	protected $post_type;
	protected $template;
	protected $name = 'display_';
	protected $atts = array();
	protected $taxonomy = 'album';

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
	public function __construct( $posttype, $template ) {

		$this->post_type = $posttype;
		$this->template = $template;
		$this->name .= str_replace('-', '_', $posttype);
		$this->name .= '_' . $this->taxonomy . 's';

		// Establish shortcode
		add_shortcode( $this->name, array( $this, 'display_albums_shortcode' ) );

	}

	/**
	 * Renders the shortcode
	 * @param  string $atts The shortcode attributes
	 * @return string       The shortcode output
	 */
	public function display_albums_shortcode( $atts ) {

		extract( shortcode_atts( $this->atts, $atts ));

		// $albums = Shortcode_Post_Query::get_posts( $this->post_type, $atts, $this->taxonomy );
		$albums = get_terms( array(
			'taxonomy' => 'album',
			'parent' => 0
		) );

		ob_start();

		require $this->template;

		$output = ob_get_contents();
		ob_clean();

		return $output;

	}

}
