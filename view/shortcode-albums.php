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

		// echo '<pre>';
		// print_r($term);
		// print_r($album_fields);
		// echo '</pre>';

		?><h2><a href="<?php echo $album['link'] ?>"><?php echo $album['thumbnail'] ?><span class="album-name"><?php echo $term->name; ?></span></a></h2><?php

		?></div><?php

	}

	?></div><?php

}