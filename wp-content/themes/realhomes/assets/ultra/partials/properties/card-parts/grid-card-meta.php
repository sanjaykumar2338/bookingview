<?php
$default                = array( 'bedrooms', 'bathrooms', 'area' );
$inspiry_meta_selection = get_option( 'inspiry_property_card_meta', $default );
$inspiry_meta_selection = array_filter( $inspiry_meta_selection );
$rhea_add_meta_select   = array();

$settings_to_keys = array(
	'bedrooms'   => array(
		'label'   => get_option( 'inspiry_bedrooms_field_label' ) ? esc_html( get_option( 'inspiry_bedrooms_field_label' ) ) : esc_html__( 'Bedrooms', 'framework' ),
		'key'     => 'REAL_HOMES_property_bedrooms',
		'icon'    => 'ultra-bedrooms',
		'postfix' => ''
	),
	'bathrooms'  => array(
		'label'   => get_option( 'inspiry_bathrooms_field_label' ) ? esc_html( get_option( 'inspiry_bathrooms_field_label' ) ) : esc_html__( 'Bathrooms', 'framework' ),
		'key'     => 'REAL_HOMES_property_bathrooms',
		'icon'    => 'ultra-bathrooms',
		'postfix' => ''
	),
	'area'       => array(
		'label'   => get_option( 'inspiry_area_field_label' ) ? esc_html( get_option( 'inspiry_area_field_label' ) ) : esc_html__( 'Area', 'framework' ),
		'key'     => 'REAL_HOMES_property_size',
		'icon'    => 'ultra-area',
		'postfix' => 'REAL_HOMES_property_size_postfix'
	),
	'garage'     => array(
		'label'   => get_option( 'inspiry_garages_field_label' ) ? esc_html( get_option( 'inspiry_garages_field_label' ) ) : esc_html__( 'Garage', 'framework' ),
		'key'     => 'REAL_HOMES_property_garage',
		'icon'    => 'ultra-garages',
		'postfix' => ''
	),
	'year-built' => array(
		'label'   => get_option( 'inspiry_year_built_field_label' ) ? esc_html( get_option( 'inspiry_year_built_field_label' ) ) : esc_html__( 'Year', 'framework' ),
		'key'     => 'REAL_HOMES_property_year_built',
		'icon'    => 'ultra-calender',
		'postfix' => ''
	),
	'lot-size'   => array(
		'label'   => get_option( 'inspiry_lot_size_field_label' ) ? esc_html( get_option( 'inspiry_lot_size_field_label' ) ) : esc_html__( 'Lot Size', 'framework' ),
		'key'     => 'REAL_HOMES_property_lot_size',
		'icon'    => 'ultra-lot-size',
		'postfix' => 'REAL_HOMES_property_lot_size_postfix'
	),
);

if ( inspiry_is_rvr_enabled() ) {
	$rvr_meta = array(
		'guests'   => array(
			'label'   => get_option( 'inspiry_rvr_guests_field_label' ) ? esc_html( get_option( 'inspiry_rvr_guests_field_label' ) ) : esc_html__( 'Guests', 'framework' ),
			'key'     => 'rvr_guests_capacity',
			'icon'    => 'ultra-guests',
			'postfix' => ''
		),
		'min-stay' => array(
			'label'   => get_option( 'inspiry_rvr_min_stay_label' ) ? esc_html( get_option( 'inspiry_rvr_min_stay_label' ) ) : esc_html__( 'Min Stay', 'framework' ),
			'key'     => 'rvr_min_stay',
			'icon'    => 'ultra-min-stay',
			'postfix' => ''
		),
	);

	$settings_to_keys = array_merge( $settings_to_keys, $rvr_meta );
}

if ( ! empty( $inspiry_meta_selection ) && is_array( $inspiry_meta_selection ) ) {
	$inspiry_meta_selection_flip = array_flip( $inspiry_meta_selection );
	$array_replaced              = array_intersect_key( array_replace( $inspiry_meta_selection_flip, $settings_to_keys ), $inspiry_meta_selection_flip );
	?>
    <div class="rh-properties-card-meta-ultra">
		<?php
		foreach ( $array_replaced as $meta ) {
			if ( ! empty( $meta ) && is_array( $meta ) ) {
				rh_ultra_meta( $meta['label'], $meta['key'], $meta['icon'], $meta['postfix'] );
			}
		}
		?>
    </div>
	<?php
}