<?php
/**
 * Grid Property Card
 *
 * Property grid card to be displayed on grid listing page.
 *
 * @since    3.0.0
 * @package  realhomes/modern
 */

global $post;
$property_id = get_the_ID();
$is_featured = get_post_meta( $property_id, 'REAL_HOMES_featured', true );
?>
<article class="rh_prop_card rh_prop_card--listing">
    <div class="rh_prop_card__wrap">
		<?php if ( $is_featured ) : ?>
            <div class="rh_label rh_label__property_grid">
                <div class="rh_label__wrap">
					<?php realhomes_featured_label(); ?>
                    <span></span>
                </div>
            </div><!-- /.rh_label -->
		<?php endif; ?>

        <figure class="rh_prop_card__thumbnail">
            <div class="rh_figure_property_one">
                <a href="<?php the_permalink(); ?>">
					<?php
					if ( has_post_thumbnail( $property_id ) ) {
						the_post_thumbnail( 'modern-property-child-slider' );
					} else {
						inspiry_image_placeholder( 'modern-property-child-slider' );
					}
					?>
                </a>

                <div class="rh_overlay"></div>
                <div class="rh_overlay__contents rh_overlay__fadeIn-bottom">
                    <a href="<?php the_permalink(); ?>"><?php inspiry_property_detail_page_link_text(); ?></a>
                </div>
                <!-- /.rh_overlay__contents -->

				<?php inspiry_display_property_label( $property_id ); ?>
            </div>
            <div class="rh_prop_card__btns">
				<?php
				inspiry_favorite_button(); // Display add to favorite button.
				inspiry_add_to_compare_button(); // Display add to compare button.
				?>
            </div>
            <!-- /.rh_prop_card__btns -->
        </figure>
        <!-- /.rh_prop_card__thumbnail -->

        <div class="rh_prop_card__details">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php
			$theme_listing_excerpt_length = get_option( 'theme_listing_excerpt_length' );

			if ( ! empty( $theme_listing_excerpt_length ) && ( 0 < $theme_listing_excerpt_length ) ) {
				$card_excerpt = $theme_listing_excerpt_length;
			} else {
				$card_excerpt = 10;
			}
			?>
            <p class="rh_prop_card__excerpt"><?php framework_excerpt( $card_excerpt ); ?></p>
            <div class="rh_prop_card__meta_wrap">
				<?php get_template_part( 'assets/modern/partials/properties/card-parts/grid-card-meta' ); ?>
            </div><!-- /.rh_prop_card__meta_wrap -->

			<?php
			if ( inspiry_is_rvr_enabled() ) {
				?>
                <div class="rh_prop_card__priceLabel rh_prop_card__priceLabel_box">
                    <!-- /.rh_prop_card__type -->
                    <div class="rh_rvr_price_status_box">
                        <span class="rh_prop_card__status">
                            <?php echo esc_html( display_property_status( $property_id ) ); ?>
                        </span>
                        <p class="rh_prop_card__price">
                            <?php
                            if ( function_exists( 'ere_property_price' ) ) {
                                ere_property_price( '', true );
                            }
                            ?>
                        </p>
                    </div>
                    <!-- /.rh_prop_card__price -->
                </div>
				<?php
			} else {
				?>
                <div class="rh_prop_card__priceLabel">
                    <span class="rh_prop_card__status"><?php echo esc_html( display_property_status( $property_id ) ); ?></span>
                    <p class="rh_prop_card__price">
						<?php
						if ( function_exists( 'ere_property_price' ) ) {
							ere_property_price( '', true );
						}
						?>
                    </p>
                </div>
                <!-- /.rh_prop_card__priceLabel -->
			<?php
            }

			if ( 'true' === get_option( 'inspiry_property_ratings', 'false' ) ) {
                ?>
                <div class="inspiry_rating_right">
                    <?php
                    inspiry_rating_average( [ 'rating_string' => false ] );
                    ?>
                </div>
                <?php
			}
            ?>
        </div>
        <!-- /.rh_prop_card__details -->

    </div>
    <!-- /.rh_prop_card__wrap -->

</article><!-- /.rh_prop_card -->