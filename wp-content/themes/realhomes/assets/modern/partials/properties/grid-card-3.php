<?php
/**
 * Grid Property Card
 *
 * Property grid card to be displayed on grid listing page.
 *
 * @package realhomes/modern
 */

$property_id = get_the_ID();
?>
<article class="rh_latest_properties_2 rh_property_card_stylish">
    <div class="rh_property_card_stylish_inner">
        <div class="rh_thumbnail_wrapper">
            <div class="rh_top_tags_box">
				<?php
				get_template_part( 'assets/modern/partials/properties/card-parts/media-count' );
				get_template_part( 'assets/modern/partials/properties/card-parts/tags' );
				?>
            </div>
            <div class="rh_bottom_tags_box">
				<?php
				if ( ! empty( inspiry_get_property_types_string( $property_id ) ) ) {
					?>
                    <span class="rh_stylish_property_types"><?php echo inspiry_get_property_types_string( $property_id ); ?></span>
					<?php
				}
				get_template_part( 'assets/modern/partials/properties/card-parts/status' );
				?>
            </div>
			<?php get_template_part( 'assets/modern/partials/properties/card-parts/thumbnail' ); ?>
        </div>
        <div class="rh_detail_wrapper rh_detail_wrapper_2">
			<?php
			get_template_part( 'assets/modern/partials/properties/card-parts/heading' );

			if ( 'no' === get_option( 'inspiry_hide_property_address', 'no' ) ) {
				get_template_part( 'assets/modern/partials/properties/card-parts/address' );
			}

			if ( inspiry_is_rvr_enabled() ) {
				?>
                <div class="rh_rvr_ratings_wrapper_stylish rvr_rating_left">
					<?php rh_rvr_rating_average(); ?>
                    <?php get_template_part( 'assets/modern/partials/properties/card-parts/added' ); ?>
                </div>
				<?php
			} else {
				get_template_part( 'assets/modern/partials/properties/card-parts/added' );
			}

			get_template_part( 'assets/modern/partials/properties/card-parts/grid-card-meta' );
			?>
            <div class="rh_price_fav_box">
                <div class="rh_price_box">
					<?php get_template_part( 'assets/modern/partials/properties/card-parts/price' ); ?>
                </div>
                <div class="rh_fav_icon_box rh_parent_fav_button">
					<?php
					if ( function_exists( 'inspiry_favorite_button' ) ) {
						inspiry_favorite_button( $property_id );
					}

					inspiry_add_to_compare_button();
					?>
                </div>
            </div>
        </div>
        <div class="rh_wrapper_bottom_agent">
			<?php
			if ( inspiry_is_rvr_enabled() ) {
				get_template_part( 'assets/modern/partials/properties/card-parts/rvr-owner' );
			} else {
				get_template_part( 'assets/modern/partials/properties/card-parts/agent-in-listing' );
			}
			?>
        </div>
    </div>
</article>
