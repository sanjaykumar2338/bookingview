<?php
/**
 * This file contains card style three.
 *
 * @version 2.3.0
 */
global $settings;

$property_id = get_the_ID();
$is_featured = get_post_meta( $property_id, 'REAL_HOMES_featured', true );

if ( ! empty( get_the_post_thumbnail( $property_id ) ) ) {
	$image_url = get_the_post_thumbnail_url( $property_id, 'large' );
} else {
	$image_url = get_inspiry_image_placeholder_url( 'large' );
}
?>
<div class="rhea-ultra-property-card-three-wrapper">
    <div class="rhea-ultra-top-tags-box">
		<?php if ( 'yes' == $settings['ere_show_property_status'] ||
			( 'yes' == $settings['ere_show_featured_tag'] && $is_featured == '1' ) ||
			'yes' == $settings['ere_show_label_tags'] ) {
			?>
            <div class="rhea-ultra-status-box">
				<?php if ( 'yes' == $settings['ere_show_property_status'] && function_exists( 'ere_get_property_tags' ) ) {
					ere_get_property_tags( $property_id, 'rhea-ultra-status' );
				}
				if ( 'yes' == $settings['ere_show_featured_tag'] && ( $is_featured == '1' ) ) {
					?><span class="rhea_ultra_featured"><?php
					if ( ! empty( $settings['ere_property_featured_label'] ) ) {
						echo esc_html( $settings['ere_property_featured_label'] );
					} else {
						esc_html_e( 'Featured', 'realhomes-elementor-addon' );
					} ?>
                    </span>
				<?php }
				if ( 'yes' == $settings['ere_show_label_tags'] ) {
					rhea_display_property_label( $property_id, 'rhea_ultra_hot' );
				} ?>
            </div>
		<?php } ?>
        <div class="rhea_action_count_wrapper">
			<?php
			if ( 'yes' == $settings['ere_show_property_media_count'] ) {
				?>
                <div class="rhea-ultra-media-count-box">
					<?php rhea_get_template_part( 'assets/partials/ultra/media-count' ); ?>
                </div>
				<?php
			}
			?>
            <div class="rhea-ultra-flex-end">
                <div class="rhea-ultra-action-buttons rh-ultra-action-light hover-light">
		            <?php
		            if ( 'yes' === $settings['ere_enable_fav_properties'] && function_exists( 'inspiry_favorite_button' ) ) {
			            inspiry_favorite_button( $property_id, $settings['ere_property_fav_label'], $settings['ere_property_fav_added_label'], '/common/images/icons/ultra-favourite.svg', 'ultra' );
		            }
		            rhea_get_template_part( 'assets/partials/ultra/compare' );
		            ?>
                </div>
            </div>
        </div>
    </div>

    <div class="rhea-ultra-property-card-three" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">

        <div class="rhea-ultra-property-card-three-content">
            <h3 class="rhea-ultra-property-card-three-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php
			$excerpt_length = ! empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 11;
			$excerpt        = rhea_get_framework_excerpt( $excerpt_length, '' );

			if ( ! empty( $excerpt ) ) {
				?>
                <p><?php echo esc_html( $excerpt ); ?></p>
				<?php
			}

			rhea_get_template_part( 'assets/partials/ultra/grid-card-meta-with-ratings' );
			?>
        </div>
    </div><!-- .rhea-ultra-property-card-three -->
</div><!-- .rhea-ultra-property-card-three-wrapper -->
