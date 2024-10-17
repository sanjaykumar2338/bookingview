<?php
/**
 * This file contains card style four.
 *
 * @version 2.3.0
 */
global $settings;

$property_id = get_the_ID();
?>
<div class="rhea-ultra-property-card-four-wrapper">
    <div class="rhea-ultra-property-card-four">
        <div class="rhea-ultra-property-card-four-thumb <?php echo 'yes' !== $settings['show_price_slash'] ? esc_attr( 'hide-ultra-price-postfix-separator' ) : ' '; ?>">
			<?php
			if ( function_exists( 'ere_property_price' ) ) {
				?><p class="rhea-ultra-property-card-four-price"><?php ere_property_price( $property_id, false, true ); ?></p><?php
			}
			?>
			<?php rhea_get_template_part( 'assets/partials/ultra/thumbnail' ); ?>
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

            </div>
            <div class="rhea-ultra-bottom-box rhea-ultra-flex-between rhea-ultra-align-center">
				<?php
				if ( 'yes' == $settings['ere_show_property_media_count'] ) {
					?>
                    <div class="rhea-ultra-media-count-box">
						<?php rhea_get_template_part( 'assets/partials/ultra/media-count' ); ?>
                    </div>
					<?php
				}
				?>
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
        <div class="rhea-ultra-property-card-four-content">
            <h3 class="rhea-ultra-property-card-four-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php
			$excerpt_length = ! empty( $settings['excerpt_length'] ) ? $settings['excerpt_length'] : 24;
			$excerpt        = rhea_get_framework_excerpt( $excerpt_length, '' );

			if ( ! empty( $excerpt ) ) {
				?>
                <p><?php echo esc_html( $excerpt ); ?></p>
				<?php
			}

			$wrapper_classes = 'rhea-ultra-property-card-four-footer';
			if ( 'yes' === $settings['hide_meta_label'] ) {
				$wrapper_classes .= ' rhea-ultra-property-hide-meta-label';
			}
			?>
            <div class="<?php echo esc_attr( $wrapper_classes ); ?>">
                <a class="rhea-ultra-property-card-four-link" href="<?php the_permalink(); ?>">
					<?php
					if ( ! empty( $settings['button_text'] ) ) {
						$button_text = $settings['button_text'];
					} else {
						$button_text = esc_html__( 'Check Availability', 'realhomes-elementor-addon' );
					}

					echo esc_html( $button_text );
					?>
                </a>
				<?php
				rhea_get_template_part( 'assets/partials/ultra/grid-card-meta-with-ratings' );
				?>
            </div>
        </div>
    </div><!-- .rhea-ultra-property-card-four -->
</div><!-- .rhea-ultra-property-card-four-wrapper -->