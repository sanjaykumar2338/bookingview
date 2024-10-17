<?php
/**
 * Radius Search Field
 *
 * @version 4.3.2
 */
if ( 'properties-map' === get_option( 'theme_search_module', 'properties-map' ) && inspiry_is_search_page_map_visible() ) {
	$default_radius = ! empty( $_GET['geolocation-radius'] ) ? $_GET['geolocation-radius'] : get_option( 'realhomes_search_radius_range_initial', '20' );
	$radius_unit    = esc_html__( 'miles', 'framework' );

	if ( 'kilometers' === get_option( 'realhomes_search_radius_range_type', 'miles' ) ) {
		$radius_unit = esc_html__( 'km', 'framework' );
	}
	?>
    <div class="test rh_prop_search__option rh-radius-slider-field rh_mod_text_field ">
        <div id="geolocation-radius-slider-wrapper" class=" geolocation-radius-slider-wrapper">
            <p class="geolocation-radius-info">
				<?php esc_html_e( 'Radius:', 'framework' ); ?>
                <strong><?php printf( '%s %s', esc_html( $default_radius ), esc_html( $radius_unit ) ); ?></strong>
            </p>
            <div id="geolocation-radius-slider" data-unit="<?php echo esc_attr( $radius_unit ); ?>" data-value="<?php echo esc_attr( $default_radius ); ?>" data-min-value="<?php echo get_option( 'realhomes_search_radius_range_min', '10' ); ?>" data-max-value="<?php echo get_option( 'realhomes_search_radius_range_max', '50' ); ?>"></div>
            <input type="hidden" name="geolocation-radius" id="rh-geolocation-radius" value="<?php echo esc_attr( $default_radius ); ?>">
        </div>
    </div>
	<?php
}
?>

