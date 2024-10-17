<?php
/**
 * Card 5 lightbox info
 *
 * Partial for
 * * elementor/widgets/properties-widget/card-5.php
 *
 * @version 2.3.2
 */
global $post_id;
$post_id = get_the_ID();
?>
<div id="rhea-popup-<?php echo esc_attr( $post_id ) ?>" class="rhea_popup_more_info" style="display:none">
    <div class="rhea-popup-box">
        <div class="thumb-title-wrapper">
            <div class="thumbnail-wrapper">
				<?php
				rhea_get_template_part( 'elementor/widgets/properties-widget/lightbox-partials/images-grid' );
				?>
            </div>
            <div class="title-wrapper">
                <div class="title-area">
                    <div class="title-box">
                        <h3><?php the_title(); ?></h3>
                        <div class="rhea-ultra-status-box">
							<?php
							if ( function_exists( 'ere_get_property_tags' ) ) {
								ere_get_property_tags( $post_id, 'rhea-ultra-status' );
							}
							$is_featured = get_post_meta( $post_id, 'REAL_HOMES_featured', true );
							if ( $is_featured == '1' ) {
								?>
                                <span class="rhea_ultra_featured"><?php esc_html_e( 'Featured', 'realhomes-elementor-addon' ); ?></span>
								<?php
							}
							rhea_display_property_label( $post_id, 'rhea_ultra_hot' );
							?>
                        </div>
                    </div>
                    <div class="popup-price-box">
						<?php
						$type_terms = get_the_terms( $post_id, "property-type" );
						if ( ! empty( $type_terms ) ) {
							if ( function_exists( 'ere_get_property_types' ) && ! empty( ere_get_property_types( $post_id ) ) ) {
								?>
                                <span class="rhea-ultra-property-types">
                            <?php echo ere_get_property_types( $post_id, true ); ?>
                        </span>
								<?php
							}
						}
						if ( function_exists( 'ere_property_price' ) ) {
							?><p class="rhea-ultra-slider-price"><?php ere_property_price( $post_id, true, true ); ?></p><?php
						}
						?>
                    </div>

                </div>
				<?php
				rhea_get_template_part( 'elementor/widgets/properties-widget/lightbox-partials/all-meta-icons' );
				?>

            </div>

        </div>
        <div class="card-additional-info">
			<?php
			rhea_get_template_part( 'elementor/widgets/properties-widget/lightbox-partials/price-details' );
			rhea_get_template_part( 'elementor/widgets/properties-widget/lightbox-partials/features' );
			?>
        </div>
    </div>
</div>