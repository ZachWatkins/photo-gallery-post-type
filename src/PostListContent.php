<?php

namespace PhotoPosts;

class PostListContent {

	protected $slug;

	public function __construct( $slug ){

		$this->slug = $slug;

		add_filter( 'the_content', array( $this, 'content' ), 10 );
		add_filter( 'post_thumbnail_size', array( $this, 'thumbnail_size' ), 10, 2 );
		add_filter( 'get_the_archive_description', array( $this, 'show_child_album_thumbnails' ), 11 );

		// Give developers an action hook before this post type shows a group of posts
		add_action( 'loop_start', array( $this, 'before_photos_list_loop' ) );

	}

	public function content( $content ){

		global $post;

		if( $post->post_type == $this->slug && !is_single() ){

			$terms = get_the_taxonomies();

			$desc = $content;
			
			$size = '';
			if( array_key_exists('size', $terms) ){
				$size = preg_replace('/^Sizes: |\.$/', '', $terms['size']);
			}

			$orientation = '';
			if( array_key_exists('orientation', $terms) ){
				$orientation = preg_replace('/^Orientations: |\.$/', '', $terms['orientation']);
			}

			$dimensions = '';
			if( !empty($size) || !empty($orientation) ){

				$dimensions = array();

				if( !empty($size) )
					$dimensions[] = $size;

				if( !empty($orientation) )
					$dimensions[] = $orientation;

				if( !empty($dimensions) )
					$dimensions = '<div class="detail">Dimensions: ' . join('; ', $dimensions) . '</div>';
			}

			$subject = '';
			if( array_key_exists('subject', $terms) ){
				$subject = '<div class="detail">Subject(s): ' . preg_replace('/^Subjects: |\.$/', '', $terms['subject']) . '</div>';
			}

			$color = '';
			if( array_key_exists('color', $terms) ){
				$color = '<div class="detail">Colors: ' . preg_replace('/^Colors: |\.$/', '', $terms['color']) . '</div>';
			}

			$content = sprintf('%s%s%s%s',
				$dimensions,
				$subject,
				$color,
				$desc
			);

			$content = apply_filters( 'photo-gallery-post-archive-content', $content, $desc, $dimensions, $subject, $color );

		}

		return $content;

	}


  
	public function thumbnail_size( $size, $id ){

		global $post;

		if( $post->post_type == $this->slug && is_archive() ){

			$size = 'photo-posts-preview';

		}

		return $size;

	}

	public function show_child_album_thumbnails($content){

		if(is_tax('album')){

			$album_obj = get_queried_object();
			$term_id = $album_obj->term_id;
			$children = get_term_children($term_id, 'album');

			if( !empty($children) ){

				$child_albums = array();

				foreach ($children as $child_id) {
					$child_name = get_term($child_id, 'album')->name;
					$child_albums[] = '<a href="' . get_term_link($child_id) . '">' . $child_name . '</a>';
				}

				$plural = count($children) > 1 ? 's' : '';
				
				$content .= sprintf('<p>Child album%s: %s</p>', $plural, implode(', ', $child_albums) );
			}

		}

		return $content;

	}

	public function before_photos_list_loop( $wp_query = null ){

		if( is_admin() || is_single() ) return false;

		$taxonomies = get_object_taxonomies( PHOTOPOSTS_POST_TYPE_SLUG );
		$is_photo_post_type = $wp_query->query_vars['post_type'] === PHOTOPOSTS_POST_TYPE_SLUG;
		$is_photo_term = array_key_exists('taxonomy', $wp_query->query_vars) && in_array( $wp_query->query_vars['taxonomy'], $taxonomies );

		if( !$is_photo_post_type && !$is_photo_term ){
			return false;
		}

		$sanitized_post_type_slug = str_replace( '-', '_', PHOTOPOSTS_POST_TYPE_SLUG );

		$args = array();
		$args['type'] = $is_photo_post_type ? 'post' : 'term';
		$args['query_object'] = $wp_query->queried_object;

		do_action( 'before_' . $sanitized_post_type_slug . '_list', $args );

	}

}