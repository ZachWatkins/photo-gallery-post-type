<?php

if( !empty( $albums ) ){

	?><div class="photo-albums-listing"><?php

	foreach ($albums as $index => $term) {

		?><div class="item"><?php 

		$album_fields = get_field('thumbnail', 'album_' . $term->term_id);

		$album = array();
		$album['link'] = get_term_link($term);
		$album['thumbnail'] = sprintf(
			'<img src="%s" alt="%s">',
			$album_fields['sizes']['photo-posts-preview'],
			$term->title
		);

		?><h2><a href="<?php echo $album['link'] ?>"><?php echo $term->name; ?><br><?php echo $album['thumbnail'] ?></a></h2><?php

		?></div><?php

	}

	?></div><?php

}