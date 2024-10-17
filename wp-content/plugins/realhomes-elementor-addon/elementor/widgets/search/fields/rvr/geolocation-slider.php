<?php /**
 * RVR search form geo location slider
 *
 * @since 2.2.0
 */
global $settings, $the_widget_id;

global $search_fields_to_display;
if ( 'yes' === $settings['location_radius_search']
	&& function_exists( 'inspiry_get_maps_type' )
	&& 'google-maps' === inspiry_get_maps_type()
	&& is_array( $search_fields_to_display )
	&& in_array( 'radius-search', $search_fields_to_display ) ) {
	$field_key = array_search( 'radius-search', $search_fields_to_display );
	$field_key = intval( $field_key ) + 1;

	$default_radius = ! empty( $_GET['geolocation-radius'] ) ? $_GET['geolocation-radius'] : get_option( 'realhomes_search_radius_range_initial', '20' );
	$radius_unit    = esc_html__( 'miles', 'realhomes-elementor-addon' );

	if ( 'kilometers' === get_option( 'realhomes_search_radius_range_type', 'miles' ) ) {
		$radius_unit = esc_html__( 'km', 'realhomes-elementor-addon' );
	}
	?>
    <div class="rhea_prop_search__option rhea_prop_search__select rhea_radius_slider_field" style="order: <?php echo esc_attr( $field_key ); ?>" data-key-position="<?php echo esc_attr( $field_key ); ?>" id="radius-search-<?php echo esc_attr( $the_widget_id ); ?>">
        <div class="rhea_radius_slider_field_inner">
            <div id="geolocation-radius-slider-wrapper-<?php echo esc_attr( $the_widget_id ) ?>" class="rhea-geolocation-radius-slider-wrapper">
                <p class="geolocation-radius-info">
					<?php echo esc_html( $settings['radius_label'] ); ?>
                    <strong><?php printf( '%s %s', esc_html( $default_radius ), esc_html( $radius_unit ) ); ?></strong>
                </p>
                <div id="geolocation-radius-slider-<?php echo esc_attr( $the_widget_id ) ?>" data-unit="<?php echo esc_attr( $radius_unit ); ?>" data-value="<?php echo esc_attr( $default_radius ); ?>" data-min-value="<?php echo get_option( 'realhomes_search_radius_range_min', '10' ); ?>" data-max-value="<?php echo get_option( 'realhomes_search_radius_range_max', '50' ); ?>"></div>
                <input type="hidden" name="geolocation-radius" id="rh-geolocation-radius-<?php echo esc_attr( $the_widget_id ) ?>" value="<?php echo esc_attr( $default_radius ); ?>">
            </div>
        </div>
    </div>
	<?php

}
?>