<?php
/**
 * View: Property List Card
 *
 * Property card for listing view.
 *
 * @since    3.0.0
 * @package  realhomes
 */

global $post;
$property_id        = get_the_ID();
$is_featured        = get_post_meta( $property_id, 'REAL_HOMES_featured', true );
$card_classes       = array( 'rh_list_card' );

if ( $is_featured ) {
	$card_classes[] = 'rh_has_featured_tag';
}
?>
<article class="<?php echo join( " ", $card_classes ); ?>">
    <div class="rh_list_card__wrap">
        <figure class="rh_list_card__thumbnail">
            <div class="rh_figure_property_list_one">
				<?php
				if ( $is_featured ) {
					?>
                    <div class="rh_label rh_label__list">
                        <div class="rh_label__wrap">
							<?php realhomes_featured_label(); ?>
                            <span></span>
                        </div>
                    </div>
					<?php
				}
				?>
                <a href="<?php the_permalink(); ?>">
					<?php
					if ( has_post_thumbnail( $property_id ) ) {
						$post_thumbnail_url = get_the_post_thumbnail_url( $property_id, 'property-thumb-image' );
					} else {
						$post_thumbnail_url = get_inspiry_image_placeholder_url( 'property-thumb-image' );
					}
					?>
                    <div class="post_thumbnail" style="background: url('<?php echo esc_url( $post_thumbnail_url ); ?>') 50% 50% no-repeat; background-size: cover;"></div>
                </a>
                <div class="rh_overlay"></div>
                <div class="rh_overlay__contents rh_overlay__fadeIn-bottom">
                    <a href="<?php the_permalink(); ?>"><?php inspiry_property_detail_page_link_text(); ?></a>
                </div>
				<?php inspiry_display_property_label( $property_id ); ?>
            </div>
            <div class="rh_list_card__btns">
				<?php
				inspiry_favorite_button(); // Display add to favorite button.
				inspiry_add_to_compare_button(); // Display add to compare button.
				?>
            </div>
        </figure><!-- /.rh_list_card__thumbnail -->

        <div class="rh_list_card__details_wrap">
            <div class="rh_list_card__details">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="rh_rvr_ratings_wrapper_stylish inspiry_rating_left inspiry_rating_margin_bottom">
                    <?php
                    if ( 'true' === get_option( 'inspiry_property_ratings', 'false' ) ) {
                        inspiry_rating_average();
                    }
                    ?>
                </div>
                <?php
				$theme_listing_excerpt_length = get_option( 'theme_listing_excerpt_length' );
				if ( ! empty( $theme_listing_excerpt_length ) && ( 0 < $theme_listing_excerpt_length ) ) {
					$card_excerpt = $theme_listing_excerpt_length;
				} else {
					$card_excerpt = 5;
				}
				?>
                <p class="rh_list_card__excerpt"><?php framework_excerpt( $card_excerpt ); ?></p>
                <div class="rh_list_card__meta_wrap">
					<?php get_template_part( 'assets/modern/partials/properties/card-parts/grid-card-meta' ); ?>
                </div>
            </div><!-- /.rh_list_card__details -->

            <div class="rh_list_card__priceLabel">
                <div class="rh_list_card__price">
                    <span class="status"><?php echo esc_html( display_property_status( $property_id ) ); ?></span>
                    <p class="price">
						<?php
						if ( function_exists( 'ere_property_price' ) ) {
							ere_property_price( '', true );
						}
						?>
                    </p>
                </div>
				<?php
				if ( inspiry_is_rvr_enabled() ) {
					$REAL_HOMES_property_owner = get_post_meta( $property_id, 'rvr_property_owner', true );
					if ( ! empty( $REAL_HOMES_property_owner ) ) {
						?>
                        <p class="rh_list_card__author">
							<?php esc_html_e( 'By', 'framework' ); ?>
                            <span class="author"><?php echo esc_html( get_the_title( $REAL_HOMES_property_owner ) ); ?></span>
                        </p>
						<?php
					}
				} else {
					$agent_display_option = get_post_meta( $property_id, 'REAL_HOMES_agent_display_option', true );
					if ( ( ! empty( $agent_display_option ) ) && ( 'none' !== $agent_display_option ) ) {
						if ( 'my_profile_info' === $agent_display_option ) {
							$author_display_name = get_the_author_meta( 'display_name' );
							if ( ! empty( $author_display_name ) ) {
								?>
                                <p class="rh_list_card__author">
									<?php esc_html_e( 'By', 'framework' ); ?>
                                    <span class="author"><?php echo esc_html( $author_display_name ); ?></span>
                                </p>
								<?php
							}
						} else {
							$agents_names = inspiry_get_property_agents_names();
							if ( ! empty( $agents_names ) ) {
								?>
                                <p class="rh_list_card__author">
									<?php esc_html_e( 'By', 'framework' ); ?>
                                    <span class="author"><?php echo esc_html( $agents_names ); ?></span>
                                </p>
								<?php
							}
						}
					}
				}
				?>
            </div><!-- /.rh_list_card__priceLabel -->
        </div><!-- /.rh_list_card__details_wrap -->
    </div><!-- /.rh_list_card__wrap -->
</article><!-- /.rh_list_card -->