<?php

if ( $posts->have_posts() ) :

	?><ul class="photo-posts-listing-ul"><?php

		while ( $posts->have_posts() ) : $posts->the_post();

		?><li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php

		endwhile;

	?></ul><?php

endif;
