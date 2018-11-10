<?php

if( !empty( $albums ) ){

	?><div class="photo-albums-listing"><?php

	foreach ($albums as $index => $term) {

		?><div class="item entry"><?php 

		$album_fields = get_field('thumbnail', 'album_' . $term->term_id);

		$album = array();
		$album['link'] = get_term_link($term);
		$srcset = wp_get_attachment_image_srcset($album_fields['id']);
		$sizes = wp_get_attachment_image_sizes($album_fields['id']);

		$album['thumbnail'] = sprintf(
			'<img src="%s" alt="%s" srcset="%s" sizes="%s">',
			$album_fields['sizes']['photo-posts-preview'],
			$term->title,
			$srcset,
			$sizes
		);

		?><h2><a href="<?php echo $album['link'] ?>"><?php echo $term->name; ?><br><?php echo $album['thumbnail'] ?></a><?php
		
		if( is_user_logged_in() ){
			$edit_term = sprintf( '<a class="photo-album-thumbnail-edit" href="%s">%s</a>',
				get_edit_tag_link($term->term_id, 'album', 'photo-post'),
				'(Edit Album)'
			);
			echo $edit_term;
		}

		?></h2></div><?php

	}

	?></div><?php

}