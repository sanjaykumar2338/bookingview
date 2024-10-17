<?php
/**
 * This file contains card style five.
 *
 * @version 2.3.0
 */
global $settings;

$property_id = get_the_ID();
?>
<div class="rhea-ultra-property-card-five-wrapper">
    <div class="rhea-ultra-property-card-five">
        <div class="rhea-ultra-property-card-five-thumb">
            <div class="rhea-ultra-top-tags-box">
                <div class="rhea-ultra-status-box">
					<?php
					if ( 'yes' == $settings['ere_show_property_status'] ) {
						if ( function_exists( 'ere_get_property_tags' ) ) {
							ere_get_property_tags( $property_id, 'rhea-ultra-status' );
						}
					}

					if ( 'yes' == $settings['ere_show_featured_tag'] ) {
						$is_featured = get_post_meta( $property_id, 'REAL_HOMES_featured', true );
						if ( $is_featured == '1' ) {
							?>
                            <span class="rhea_ultra_featured">
                                <?php
                                if ( ! empty( $settings['ere_property_featured_label'] ) ) {
	                                echo esc_html( $settings['ere_property_featured_label'] );
                                } else {
	                                esc_html_e( 'Featured', 'realhomes-elementor-addon' );
                                }
                                ?>
                            </span>
							<?php
						}
					}

					if ( 'yes' == $settings['ere_show_label_tags'] ) {
						rhea_display_property_label( $property_id, 'rhea_ultra_hot' );
					}
					?>
                </div>
				<?php
				if ( 'yes' == $settings['ere_show_property_media_count'] ) {
					?>
                    <div class="rhea-ultra-media-count-box">
						<?php rhea_get_template_part( 'assets/partials/ultra/media-count' ); ?>
                    </div>
					<?php
				}
				?>
            </div>
            <div class="rhea-ultra-bottom-box rhea-ultra-flex-end">
                <div class="rhea-ultra-action-buttons rh-ultra-action-light hover-light">
					<?php
					if ( 'yes' === $settings['ere_enable_fav_properties'] && function_exists( 'inspiry_favorite_button' ) ) {
						inspiry_favorite_button( $property_id, $settings['ere_property_fav_label'], $settings['ere_property_fav_added_label'], '/common/images/icons/ultra-favourite.svg', 'ultra' );
					}
					rhea_get_template_part( 'assets/partials/ultra/compare' );
					?>
                </div>
            </div>
			<?php rhea_get_template_part( 'assets/partials/ultra/thumbnail' ); ?>
        </div>
        <div class="rhea-ultra-property-card-five-content">
            <div class="rhea-ultra-property-card-five-content-inner <?php echo 'yes' !== $settings['show_price_slash'] ? esc_attr( 'hide-ultra-price-postfix-separator' ) : ' '; ?>">
                <h3 class="rhea-ultra-property-card-five-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php
				if ( function_exists( 'ere_property_price' ) ) {
					?><p class="rhea-ultra-property-card-five-price"><?php ere_property_price( $property_id, true, true ); ?></p><?php
				}
				?>
            </div>
			<?php
			rhea_get_template_part( 'assets/partials/ultra/address' );
			rhea_get_template_part( 'assets/partials/ultra/grid-card-meta' );

			if ( $settings['rhea_rating_enable'] && ( 'true' === get_option( 'inspiry_property_ratings', 'false' ) ) ) {
				inspiry_rating_average();
			}

			$excerpt_length = ! empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 29;
			$excerpt        = rhea_get_framework_excerpt( $excerpt_length, '' );
			if ( ! empty( $excerpt ) ) {
				?>
                <p><?php echo esc_html( $excerpt ); ?></p>
				<?php
			}
			?>

            <div class="rhea-ultra-property-card-five-footer">
                <a class="rhea-ultra-property-card-five-link rhea-equal-width-btn-1" href="<?php the_permalink(); ?>">
					<?php
					$button_text = esc_html__( 'Make a Reservation', 'realhomes-elementor-addon' );

					if ( ! empty( $settings['button_text'] ) ) {
						$button_text = $settings['button_text'];
					}

					echo esc_html( $button_text );
					?>
                </a>

                <a data-fancybox data-src="#rhea-popup-<?php echo esc_attr( $property_id ) ?>" href="javascript:;" class="rhea-ultra-property-card-five-popup rhea-equal-width-btn-2">
					<?php
					echo ! empty( $settings['quick_details_button_text'] ) ? esc_html( $settings['quick_details_button_text'] ) : esc_html__( 'Quick Details', 'realhomes-elementor-addon' );
					?>
                </a>
				<?php rhea_get_template_part( 'elementor/widgets/properties-widget/lightbox-partials/lightbox' ); ?>

            </div>

        </div>
    </div><!-- .rhea-ultra-property-card-five -->
</div><!-- .rhea-ultra-property-card-five-wrapper -->