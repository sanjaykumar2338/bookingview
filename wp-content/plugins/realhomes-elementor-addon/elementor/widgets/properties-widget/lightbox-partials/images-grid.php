<?php
/**
 * Property thumbnail to be displayed in lightbox
 *
 * Partial for
 * * elementor/widgets/properties-widget/lightbox-partials/lightbox.php
 *
 * @version 2.3.2
 */
global $post_id, $widget_id;
$size              = 'post-featured-image';
$properties_images = rwmb_meta( 'REAL_HOMES_property_images', 'type=plupload_image&size=' . $size, $post_id );
?>
<div class="rhea-single-property-gallery-wrapper" id="rhea-gallery-wrapper-<?php echo esc_attr( $widget_id ) ?>">
    <div class="rhea-single-property-gallery grid-box">
		<?php
		$i = 0;
		if ( ! empty( $properties_images ) ) {
			foreach ( $properties_images as $prop_image_id => $prop_image_meta ) {
				$lightbox_caption = 'data-caption="' . $prop_image_meta['title'] . '"';
				if ( $i < 5 ) {
					?>
                    <a class="rhea-gallery-item" href="<?php echo esc_url( $prop_image_meta['full_url'] ) ?>" data-bg-image='<?php echo esc_url( $prop_image_meta['full_url'] ) ?>' data-fancybox="gallery-<?php echo esc_attr( $widget_id ) . '-' . esc_attr( $post_id ); ?>"<?php echo esc_attr( $lightbox_caption ) ?>>
						<?php
						if ( 4 === $i ) {
							?>
                            <span class="overlay-counter"><?php echo esc_html( __( 'See All Photos', 'realhomes-elementor-addon' ) . ' ( ' . count( $properties_images ) . ' )' ) ?></span>
							<?php
						}
						?>
                    </a>
					<?php
					$i++;
				} else {
					?>
                    <span style="display: none;" data-fancybox="gallery-<?php echo esc_attr( $widget_id ) . '-' . esc_attr( $post_id ); ?>" data-src="<?php echo esc_url( $prop_image_meta['full_url'] ); ?>" data-thumb="<?php echo esc_url( $prop_image_meta['full_url'] ); ?>"></span>
					<?php
				}
			}
		} else {
			if ( has_post_thumbnail() ) {
				$post_thumb_url = get_the_post_thumbnail_url( $post_id, $size );
			} else {
				$post_thumb_url = get_inspiry_image_placeholder( $size );
			}
			?>
            <a class="rhea-gallery-item" href="<?php echo esc_url( get_the_permalink() ) ?>" data-bg-image='<?php echo esc_url( $post_thumb_url ) ?>'></a>
			<?php
		}
		?>
    </div>
</div>