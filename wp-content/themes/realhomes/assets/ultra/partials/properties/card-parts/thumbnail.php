<a class="rh-permalink" href="<?php the_permalink() ?>">
	<?php
	if ( ! empty( get_the_post_thumbnail( get_the_ID() ) ) ) {
		the_post_thumbnail( 'modern-property-child-slider' );
	} else {
		inspiry_image_placeholder( 'modern-property-child-slider' );
	}
	?>
</a>