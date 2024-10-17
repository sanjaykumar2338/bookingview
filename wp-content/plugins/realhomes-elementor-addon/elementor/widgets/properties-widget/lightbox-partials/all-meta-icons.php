<?php
/**
 * All meta icons to be displayed in lightbox
 *
 * Partial for
 * * elementor/widgets/properties-widget/lightbox-partials/lightbox.php
 *
 * @version 2.3.2
 */
global $post_id;
$meta_to_display = array(
	[
		'id'       => 'REAL_HOMES_property_bedrooms',
		'label'    => 'inspiry_bedrooms_field_label',
		'default'  => esc_html__( 'Bedrooms', 'realhomes-elementor-addon' ),
		'icon'     => 'ultra-bedrooms',
		'post-fix' => ''
	],
	[
		'id'       => 'REAL_HOMES_property_bathrooms',
		'label'    => 'inspiry_bathrooms_field_label',
		'default'  => esc_html__( 'Bathrooms', 'realhomes-elementor-addon' ),
		'icon'     => 'ultra-bathrooms',
		'post-fix' => ''
	],
	[
		'id'       => 'REAL_HOMES_property_garage',
		'label'    => 'inspiry_garages_field_label',
		'default'  => esc_html__( 'Garage', 'realhomes-elementor-addon' ),
		'icon'     => 'ultra-garagers',
		'post-fix' => ''
	],
	[
		'id'       => 'REAL_HOMES_property_size',
		'label'    => 'inspiry_area_field_label',
		'default'  => esc_html__( 'Area', 'realhomes-elementor-addon' ),
		'icon'     => 'ultra-area',
		'post-fix' => 'REAL_HOMES_property_size_postfix'
	],
	[
		'id'       => 'REAL_HOMES_property_lot_size',
		'label'    => 'inspiry_lot_size_field_label',
		'default'  => esc_html__( 'Lot Size', 'realhomes-elementor-addon' ),
		'icon'     => 'ultra-lot-size',
		'post-fix' => 'REAL_HOMES_property_lot_size_postfix'
	],
	[
		'id'       => 'REAL_HOMES_property_year_built',
		'label'    => 'inspiry_year_built_field_label',
		'default'  => esc_html__( 'Year Built', 'realhomes-elementor-addon' ),
		'icon'     => 'ultra-calender',
		'post-fix' => ''
	]
);

if ( rhea_is_rvr_enabled() ) {
	$rvr_meta_to_display = array(
		[
			'id'      => 'rvr_guests_capacity',
			'label'   => 'inspiry_rvr_guests_field_label',
			'default' => esc_html__( 'Capacity', 'realhomes-elementor-addon' ),
			'icon'    => 'guests-icons'
		],
		[
			'id'      => 'rvr_min_stay',
			'label'   => 'inspiry_rvr_min_stay_label',
			'default' => esc_html__( 'Min Stay', 'realhomes-elementor-addon' ),
			'icon'    => 'icon-min-stay'
		],
	);
	array_splice( $meta_to_display, 2, 0, $rvr_meta_to_display );
}

$meta_to_display = apply_filters( 'inspiry_property_detail_meta', $meta_to_display );
?>
<div class="rh_ultra_prop_card_meta_wrap">
	<?php
	$post_meta_data = get_post_custom( $post_id );
	foreach ( $meta_to_display as $key => $value ) {

		if ( ! empty( $post_meta_data[ $value['id'] ][0] ) ) {
			$label = get_option( $value['label'] );
			?>
            <div class="rh_ultra_prop_card__meta">
                <div class="rh_ultra_meta_icon_wrapper">
                    <span class="rh-ultra-meta-label"><?php echo ( empty ( $label ) ) ? $value['default'] : esc_html( $label ); ?></span>
                    <div class="rh-ultra-meta-icon-wrapper">
                        <span class="rh_ultra_meta_icon"><?php rhea_property_meta_icon( $value['id'], $value['icon'] ); ?></span>
                        <span class="rh_ultra_meta_box">
                            <span class="figure"><?php echo esc_html( $post_meta_data[ $value['id'] ][0] ); ?></span>
                            <?php if ( isset( $value['post-fix'] ) && ! empty( $post_meta_data[ $value['post-fix'] ][0] ) ) { ?>
                                <span class="label"><?php echo esc_html( $post_meta_data[ $value['post-fix'] ][0] ); ?></span>
                            <?php } ?>
                            </span>
                    </div>
                </div>
            </div>
			<?php
		}
	}
	/**
	 * Additional fields created by New Field Builder
	 */
	do_action( 'inspiry_additional_property_meta_fields', $post_id );
	?>
</div>
