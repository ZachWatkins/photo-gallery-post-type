<?php

namespace PhotoPosts;

class SinglePostContent {

	protected $slug;

	public function __construct( $slug ){

		$this->slug = $slug;
		
		add_filter( 'the_content', array( $this, 'content' ) );

	}

	public function content( $content ){

		global $post;

		if( $post->post_type == $this->slug && is_single() ){

			$terms = get_the_taxonomies();
			
			$album = '';
			if( $terms['album'] ){
				$album = '<div class="detail">Album: ' . preg_replace('/^Albums: |\.$/', '', $terms['album']) . '</div>';
			}
			
			$size = '';
			if( $terms['size'] ){
				$size = preg_replace('/^Sizes: |\.$/', '', $terms['size']);
			}

			$orientation = '';
			if( $terms['orientation'] ){
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
			if( $terms['subject'] ){
				$subject = '<div class="detail">Subject(s): ' . preg_replace('/^Subjects: |\.$/', '', $terms['subject']) . '</div>';
			}

			$color = '';
			if( $terms['color'] ){
				$color = '<div class="detail">Colors: ' . preg_replace('/^Colors: |\.$/', '', $terms['color']) . '</div>';
			}

			$content = sprintf('%s%s%s%s%s  ',
				$album,
				$dimensions,
				$subject,
				$color,
				$content
			);

		}

		return $content;

	}

}