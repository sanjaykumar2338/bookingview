<?php
/**
 * Property meta of single property template.
 *
 * @since      4.0.0
 * @package    realhomes
 * @subpackage ultra
 */

global $post;

$post_id        = get_the_ID();
$post_meta_data = get_post_custom( $post_id );

$meta_to_display = array(
	[
		'id'       => 'REAL_HOMES_property_bedrooms',
		'label'    => 'inspiry_bedrooms_field_label',
		'default'  => esc_html__( 'Bedrooms', 'framework' ),
		'icon'     => 'ultra-bedrooms.svg',
		'post-fix' => ''
	],

	[
		'id'       => 'REAL_HOMES_property_bathrooms',
		'label'    => 'inspiry_bathrooms_field_label',
		'default'  => esc_html__( 'Bathrooms', 'framework' ),
		'icon'     => 'ultra-bathrooms.svg',
		'post-fix' => ''
	],
	[
		'id'       => 'REAL_HOMES_property_garage',
		'label'    => 'inspiry_garages_field_label',
		'default'  => esc_html__( 'Garage', 'framework' ),
		'icon'     => 'garage.svg',
		'post-fix' => ''
	],

	[
		'id'       => 'REAL_HOMES_property_year_built',
		'label'    => 'inspiry_year_built_field_label',
		'default'  => esc_html__( 'Year Built', 'framework' ),
		'icon'     => 'calendar.svg',
		'post-fix' => ''
	],
	[
		'id'       => 'REAL_HOMES_property_size',
		'label'    => 'inspiry_area_field_label',
		'default'  => esc_html__( 'Area', 'framework' ),
		'icon'     => 'ultra-area.svg',
		'post-fix' => 'REAL_HOMES_property_size_postfix'
	],
	[
		'id'       => 'REAL_HOMES_property_lot_size',
		'label'    => 'inspiry_lot_size_field_label',
		'default'  => esc_html__( 'Lot Size', 'framework' ),
		'icon'     => 'ultra-lot-size.svg',
		'post-fix' => 'REAL_HOMES_property_lot_size_postfix'
	],
);

if ( inspiry_is_rvr_enabled() ) {
	$rvr_meta_to_display = array(
		[
			'id'      => 'rvr_guests_capacity',
			'label'   => 'inspiry_rvr_guests_field_label',
			'default' => esc_html__( 'Capacity', 'framework' ),
			'icon'    => 'ultra-guests.svg'
		],
		[
			'id'      => 'rvr_min_stay',
			'label'   => 'inspiry_rvr_min_stay_label',
			'default' => esc_html__( 'Min Stay', 'framework' ),
			'icon'    => 'ultra-min-stay.svg'
		],
	);
	array_splice( $meta_to_display, 2, 0, $rvr_meta_to_display );
}

$meta_to_display = apply_filters( 'inspiry_property_detail_meta', $meta_to_display );
?>
<div class="rh_ultra_prop_card_meta_wrap margin-bottom-40px">
	<?php
	foreach ( $meta_to_display as $key => $value ) {
		if ( ! empty( $post_meta_data[ $value['id'] ][0] ) ) {
			?>
            <div class="rh_ultra_prop_card__meta">
                <div class="rh_ultra_meta_icon_wrapper">
                    <span class="rh-ultra-meta-label">
                    <?php
                    $label = get_option( $value['label'] );
                    echo ( empty ( $label ) ) ? $value['default'] : esc_html( $label );
                    ?>
                    </span>
                    <div class="rh-ultra-meta-icon-wrapper">
                        <span class="rh_ultra_meta_icon">
                        <?php realhomes_property_meta_icon( $value['id'], '/ultra/icons/' . $value['icon'], '/assets/' ); ?>
                        </span>
                        <span class="rh_ultra_meta_box">
                            <span class="figure"><?php echo esc_html( $post_meta_data[ $value['id'] ][0] ); ?></span>
                            <?php
                            if ( ! empty( $value['post-fix'] ) ) {
	                            $postfix = $value['post-fix'];

	                            if ( 'REAL_HOMES_property_size_postfix' === $postfix ) {
		                            $get_postfix = realhomes_get_area_unit( $post_id );
	                            } else if ( 'REAL_HOMES_property_lot_size_postfix' === $postfix ) {
		                            $get_postfix = realhomes_get_lot_unit( $post_id );
	                            } else {
		                            $get_postfix = get_post_meta( $post_id, $postfix, true );
	                            }
	                            ?>
                                <span class="label"><?php echo esc_html( $get_postfix ); ?></span>
                                <?php
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	/**
	 * This hook can be used to display more property meta fields
	 */
	do_action( 'inspiry_additional_property_meta_fields', $post_id );
	?>
</div><!-- /.rh_property__row rh_property__meta -->
