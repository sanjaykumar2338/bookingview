<?php
global $settings;

$rhea_add_meta_select = array();

if ( isset( $settings['rhea_add_meta_select'] ) && ! empty( $settings['rhea_add_meta_select'] ) ) {
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
		'icon'    => 'icon-bed',
		'postfix' => ''
	),
	'bathrooms'  => array(
		'key'     => 'REAL_HOMES_property_bathrooms',
		'icon'    => 'icon-shower',
		'postfix' => ''
	),
	'area'       => array(
		'key'     => 'REAL_HOMES_property_size',
		'icon'    => 'icon-area',
		'postfix' => 'REAL_HOMES_property_size_postfix'
	),
	'garage'     => array(
		'key'     => 'REAL_HOMES_property_garage',
		'icon'    => 'icon-garage',
		'postfix' => ''
	),
	'year-built' => array(
		'key'     => 'REAL_HOMES_property_year_built',
		'icon'    => 'icon-calendar',
		'postfix' => ''
	),
	'lot-size'   => array(
		'key'     => 'REAL_HOMES_property_lot_size',
		'icon'    => 'icon-lot',
		'postfix' => 'REAL_HOMES_property_lot_size_postfix'
	),
);

if ( inspiry_is_rvr_enabled() ) {
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
    <div class="rh_prop_card_meta_wrap_stylish">
		<?php
		foreach ( $rhea_add_meta_select as $meta ) {
			if ( isset( $meta['rhea_property_meta_display'] ) &&
				! empty( $meta['rhea_property_meta_display'] ) &&
				isset( $settings_to_keys[ $meta['rhea_property_meta_display'] ] )
			) {
				ere_stylish_meta_smart(
					$meta['rhea_meta_repeater_label'],
					$settings_to_keys[ $meta['rhea_property_meta_display'] ]['key'],
					$settings_to_keys[ $meta['rhea_property_meta_display'] ]['icon'],
					$settings_to_keys[ $meta['rhea_property_meta_display'] ]['postfix']
				);
			}
		}
		?>
    </div>
	<?php
}