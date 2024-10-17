<?php
/**
 * This file contains card style two.
 *
 * @version 2.2.0
 */
global $settings;

$property_id = get_the_ID();
?>
<div class="rhea-ultra-property-card-two-wrapper">
    <div class="rhea-ultra-property-card-two">
        <div class="rhea-ultra-property-card-two-thumb">
			<?php rhea_get_template_part( 'assets/partials/ultra/thumbnail' ); ?>
            <div class="rhea-ultra-top-tags-box">
                <div class="rhea-ultra-status-box">
			        <?php
			        if ( 'yes' == $settings['ere_show_property_status'] && function_exists( 'ere_get_property_tags' ) ) {
				        ere_get_property_tags( $property_id, 'rhea-ultra-status' );
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
        </div>
        <div class="rhea-ultra-property-card-two-content">
            <h3 class="rhea-ultra-property-card-two-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php
			if ( 'yes' == $settings['show_address'] ) {
				$REAL_HOMES_property_address = get_post_meta( $property_id, 'REAL_HOMES_property_address', true );
				if ( ! empty( $REAL_HOMES_property_address ) ) {
					?>
                    <span class="rhea-ultra-property-card-two-address"><?php echo esc_html( $REAL_HOMES_property_address ); ?></span>
					<?php
				}
			}

			rhea_get_template_part( 'assets/partials/ultra/grid-card-meta-with-ratings' );
			?>
            <div class="rhea-ultra-property-card-two-footer">
                <p class="rhea-ultra-property-card-two-price <?php echo 'yes' !== $settings['show_price_slash'] ? esc_attr( 'hide-ultra-price-postfix-separator' ) : ' '; ?>">
					<?php
					if ( function_exists( 'ere_property_price' ) ) {
						ere_property_price( $property_id, false, true );
					}
					?>
                </p>
                <a class="rhea-ultra-property-card-two-link" href="<?php the_permalink(); ?>">
					<?php esc_html_e( 'View Details', 'realhomes-elementor-addon' ); ?>
                    <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L5 5L1 9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
    </div><!-- .rhea-ultra-property-card-two -->
</div><!-- .rhea-ultra-property-card-two-wrapper -->