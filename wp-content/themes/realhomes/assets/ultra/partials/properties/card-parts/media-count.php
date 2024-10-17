<?php
$get_post_meta_image = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', false );
$get_prop_images     = count( $get_post_meta_image );
$inspiry_video_group = get_post_meta( get_the_ID(), 'inspiry_video_group', true );
?>
<div class="rh-ultra-media-count <?php echo esc_attr( $args['styles_selectors'] ) ?>">

	<?php if ( $get_prop_images > 0 ) { ?>
        <div class="rh-media rh-media-image" data-fancybox-trigger="gallery-<?php echo esc_attr( get_the_ID() ); ?>" data-this-id="<?php echo esc_attr( get_the_ID() ); ?>">
			<?php inspiry_safe_include_svg( '/ultra/icons/photos.svg', '/assets/' ); ?>
            <span><?php echo esc_html( $get_prop_images ); ?></span>
        </div>
		<?php
	}

	$count_videos = '';
	if ( ! empty( $inspiry_video_group ) ) {
		$count_videos = count( $inspiry_video_group );
	}
	if ( $count_videos > 0 ) {
		?>
        <div class="rh-media rh-media-video" data-fancybox-trigger="video-<?php echo esc_attr( get_the_ID() ); ?>" data-this-id="<?php echo esc_attr( get_the_ID() ); ?>">
			<?php inspiry_safe_include_svg( '/ultra/icons/video.svg', '/assets/' ); ?>
            <span><?php echo esc_html( $count_videos ); ?></span>
        </div>
		<?php
	}
	?>
</div>
<div class="rh-property-images-load" style="display: none">
	<?php
	if ( ! empty( $get_post_meta_image ) ) {
		foreach ( $get_post_meta_image as $item ) {
			$images_src = wp_get_attachment_image_src( $item, 'post-featured-image' );
			if ( ! empty( $images_src[0] ) ) {
				?>
                <span style="display: none;" data-fancybox="gallery-<?php echo esc_attr( get_the_ID() ); ?>" data-src="<?php echo esc_url( $images_src[0] ); ?>" data-thumb="<?php echo esc_url( $images_src[0] ); ?>"></span>
				<?php
			}
		}
	}

	if ( ! empty( $inspiry_video_group ) ) {
		foreach ( $inspiry_video_group as $video ) {
			?>
            <span style="display: none;" data-fancybox="video-<?php echo esc_attr( get_the_ID() ); ?>" data-src="<?php echo esc_url( $video['inspiry_video_group_url'] ); ?>"></span>
			<?php
		}
	}
	?>
</div>