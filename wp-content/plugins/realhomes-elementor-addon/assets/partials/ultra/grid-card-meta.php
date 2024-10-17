<?php
global $settings, $rhea_add_meta_select;

$rhea_add_meta_select = array();
$property_id          = get_the_ID();

$property_bedrooms   = get_post_meta( $property_id, 'REAL_HOMES_property_bedrooms', true );
$property_bathrooms  = get_post_meta( $property_id, 'REAL_HOMES_property_bathrooms', true );
$property_size       = get_post_meta( $property_id, 'REAL_HOMES_property_size', true );
$size_postfix        = get_post_meta( $property_id, 'REAL_HOMES_property_size_postfix', true );
$property_year_built = get_post_meta( $property_id, 'REAL_HOMES_property_year_built', true );
$property_garage     = get_post_meta( $property_id, 'REAL_HOMES_property_garage', true );
$lot_size            = get_post_meta( $property_id, 'REAL_HOMES_property_lot_size', true );
$lot_size_postfix    = get_post_meta( $property_id, 'REAL_HOMES_property_lot_size_postfix', true );

$rvr_guests_capacity = get_post_meta( $property_id, 'rvr_guests_capacity', true );
$rvr_min_stay        = get_post_meta( $property_id, 'rvr_min_stay', true );

if ( ! empty( $settings['rhea_add_meta_select'] ) ) {
	$rhea_add_meta_select = $settings['rhea_add_meta_select'];
}

$bedroom_label = '';
if ( isset( $settings['ere_property_bedrooms_label'] ) ) {
	$bedroom_label = $settings['ere_property_bedrooms_label'];
}

$bathroom_label = '';
if ( isset( $settings['ere_property_bathrooms_label'] ) ) {
	$bathroom_label = $settings['ere_property_bathrooms_label'];
}

$area_label = '';
if ( isset( $settings['ere_property_area_label'] ) ) {
	$area_label = $settings['ere_property_area_label'];
}

$settings_to_keys = array(
	'bedrooms'   => array(
		'key'     => 'REAL_HOMES_property_bedrooms',
		'icon'    => 'ultra-bedrooms',
		'postfix' => ''
	),
	'bathrooms'  => array(
		'key'     => 'REAL_HOMES_property_bathrooms',
		'icon'    => 'ultra-bathrooms',
		'postfix' => ''
	),
	'area'       => array(
		'key'     => 'REAL_HOMES_property_size',
		'icon'    => 'ultra-area',
		'postfix' => 'REAL_HOMES_property_size_postfix'
	),
	'garage'     => array(
		'key'     => 'REAL_HOMES_property_garage',
		'icon'    => 'ultra-garagers',
		'postfix' => ''
	),
	'year-built' => array(
		'key'     => 'REAL_HOMES_property_year_built',
		'icon'    => 'ultra-calender',
		'postfix' => ''
	),
	'lot-size'   => array(
		'key'     => 'REAL_HOMES_property_lot_size',
		'icon'    => 'ultra-lot-size',
		'postfix' => 'REAL_HOMES_property_lot_size_postfix'
	),
);

if ( rhea_is_rvr_enabled() ) {
	$rvr_meta = array(
		'guests'   => array(
			'key'     => 'rvr_guests_capacity',
			'icon'    => 'guests-icons',
			'postfix' => ''
		),
		'min-stay' => array(
			'key'     => 'rvr_min_stay',
			'icon'    => 'icon-min-stay',
			'postfix' => ''
		),
	);

	$settings_to_keys = array_merge( $settings_to_keys, $rvr_meta );
}

if ( isset( $rhea_add_meta_select ) && ! empty( $rhea_add_meta_select ) ) {
	?>
    <div class="rh_prop_card_meta_wrap_ultra rh-ul-tooltip">
		<?php
		foreach ( $rhea_add_meta_select as $i => $meta ) {
			if ( ! empty( $meta['rhea_property_meta_display'] ) &&
				isset( $settings_to_keys[ $meta['rhea_property_meta_display'] ] )
			) {
				rhea_ultra_meta(
					$meta['rhea_meta_repeater_label'],
					$settings_to_keys[ $meta['rhea_property_meta_display'] ]['key'],
					$settings_to_keys[ $meta['rhea_property_meta_display'] ]['icon'],
					$settings_to_keys[ $meta['rhea_property_meta_display'] ]['postfix'],
					$i + 1

				);
			}
		}

		// display additional fields icons
		do_action( 'rhea_property_listing_additional_fields_icons', $property_id );
		?>
    </div>
	<?php
}
