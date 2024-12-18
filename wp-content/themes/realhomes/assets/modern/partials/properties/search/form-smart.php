<?php
/**
 * Properties Search Form
 *
 * @package    realhomes
 * @subpackage modern
 */

if ( inspiry_is_search_fields_configured() ) :
	$theme_search_fields = inspiry_get_search_fields();
	$theme_top_row_fields = get_option( 'inspiry_search_fields_main_row', '4' );

	$form_classes = '';
	if ( 'geo-location' === get_option( 'realhomes_location_field_type', 'default' ) ) {
		$form_classes .= ' has-geolocation-field';
	}
	$check_in_out_key    = array_search( 'check-in-out', $theme_search_fields ) + 1;
	$radius_slider_key   = array_search( 'radius-search', $theme_search_fields ) + 1;
	$top_field_int_value = (int)$theme_top_row_fields;

	if ( $check_in_out_key <= $top_field_int_value && in_array( 'check-in-out', $theme_search_fields ) ) {
		$top_fields_counter = 1;
	} else {
		$top_fields_counter = 0;
	}
	if ( $radius_slider_key + $top_fields_counter <= $top_field_int_value && in_array( 'radius-search', $theme_search_fields ) ) {
		$form_classes .= ' rh-radius-search-in-top';
	}

	?>
    <form class="rh_prop_search__form_smart rh_prop_search_form_header advance-search-form rh_grid_size <?php echo esc_attr( $form_classes ); ?>" action="<?php echo esc_url( inspiry_get_search_page_url() ); ?>" method="get">
        <div class="rh_prop_search__fields_smart">
			<?php
			if ( ! empty( $theme_search_fields ) && is_array( $theme_search_fields ) ) {
			?>
            <div class="rh_prop_search__wrap_smart rh_prop_search_data" data-top-bar="<?php echo esc_attr( $theme_top_row_fields ) ?>">
                <div class="rh_form_smart_top_fields rh_search_top_field_common">
					<?php
					foreach ( $theme_search_fields as $field ) {
						if ( inspiry_is_rvr_enabled() && ( 'check-in-out' === $field || 'guest' === $field ) ) {
							$field = 'rvr/' . $field;
						}
						get_template_part( 'assets/modern/partials/properties/search/fields/' . $field );
					}
					do_action( 'inspiry_additional_search_fields' );
					?>
                </div>

                <div class="rh_form_smart_collapsed_fields_wrapper ">
                    <div class="rh_form_smart_collapsed_fields rh_search_fields_prepend_to">

						<?php
						$feature_search_option = get_option( 'inspiry_search_fields_feature_search', 'true' );

						if ( ! empty( $feature_search_option ) && $feature_search_option == 'true' ) {
							get_template_part( 'assets/modern/partials/properties/search/fields/property-features' );
						}

						?>
                    </div>
                </div>

            </div>

        </div>
		<?php
		}
		?>
        <!-- /.rh_prop_search__fields -->

        <div class="rh_prop_search__buttons_smart">
			<?php
			/**
			 * Search Button
			 */
			get_template_part( 'assets/modern/partials/properties/search/fields/button' );
			?>
        </div>
        <!-- /.rh_prop_search__buttons -->

    </form><!-- /.rh_prop_search__form -->

<?php
endif;
