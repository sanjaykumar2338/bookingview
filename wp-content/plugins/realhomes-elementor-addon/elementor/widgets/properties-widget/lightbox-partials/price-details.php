<?php
/**
 * Property price details to be displayed in lightbox
 *
 * Partial for
 * * elementor/widgets/properties-widget/lightbox-partials/lightbox.php
 *
 * @version 2.3.2
 */
global $post_id;
$bulk_prices       = get_post_meta( $post_id, 'rvr_bulk_pricing', true );
$service_charges   = get_post_meta( $post_id, 'rvr_service_charges', true );
$govt_tax          = get_post_meta( $post_id, 'rvr_govt_tax', true );
$additional_fees   = get_post_meta( $post_id, 'rvr_additional_fees', true );
$extra_guest_price = get_post_meta( $post_id, 'rvr_extra_guest_price', true );
$all_prices        = [ $bulk_prices, $service_charges, $govt_tax, $additional_fees, $extra_guest_price ];
$sections_enable   = array_filter( $all_prices ); // check if any of the top section is available so that we can display the wrapper

if ( ! empty( $sections_enable ) ) {
	?>
    <div class="rvr_price_details_wrap <?php realhomes_printable_section( 'rvr/price-details' ); ?>">
        <h4 class="rh_property__heading"><?php esc_html_e( 'Price Details', 'realhomes-elementor-addon' ); ?></h4>
        <div class="rvr_price_details">
            <ul>
				<?php
				if ( ! empty( $additional_fees ) && is_array( $additional_fees ) ) {
					foreach ( $additional_fees as $additional_fee ) {
						if ( ! empty( $additional_fee['rvr_fee_label'] ) && ! empty( $additional_fee['rvr_fee_amount'] ) ) {

							if ( 'fixed' === $additional_fee['rvr_fee_type'] ) {
								$fee_amount = ere_format_amount( $additional_fee['rvr_fee_amount'] );
							} else {
								$fee_amount = intval( $additional_fee['rvr_fee_amount'] ) . '%';
							}

							switch ( $additional_fee['rvr_fee_calculation'] ) {
								case 'per_night':
									$fee_info_label = esc_html__( 'per night', 'realhomes-elementor-addon' );
									break;
								case 'per_guest':
									$fee_info_label = esc_html__( 'per guest', 'realhomes-elementor-addon' );
									break;
								case 'per_night_guest':
									$fee_info_label = esc_html__( 'per night per guest', 'realhomes-elementor-addon' );
									break;
								case 'per_stay':
									$fee_info_label = esc_html__( 'per stay', 'realhomes-elementor-addon' );
									break;
								default:
									$fee_info_label = '';
							}

							$fee_info = '(' . $fee_info_label . ')';
							?>
                            <li><strong><?php echo esc_html( $additional_fee['rvr_fee_label'] ); ?>
                                    :</strong><span><?php echo ( ! empty( $fee_info ) ) ? '<i>' . esc_html( $fee_info ) . '</i>' : ''; ?></span><?php echo esc_html( $fee_amount ); ?>
                            </li>
							<?php
						}
					}
				}

				if ( ! empty( $extra_guest_price ) ) {
					?>
                    <li><strong><?php esc_html_e( 'Extra Guest Price', 'realhomes-elementor-addon' ); ?>
                            :</strong><span><i><?php esc_html_e( '(per night)', 'realhomes-elementor-addon' ); ?></i></span><?php echo ere_format_amount( $extra_guest_price ); ?>
                    </li>
					<?php
				}

				if ( ! empty( $service_charges ) ) {
					?>
                    <li><strong><?php esc_html_e( 'Service Charges', 'realhomes-elementor-addon' ); ?>:</strong><span><i><?php esc_html_e( '(per stay)', 'realhomes-elementor-addon' ); ?></i></span><?php echo intval( $service_charges ) . '%'; ?>
                    </li>
					<?php
				}
				if ( ! empty( $govt_tax ) ) {
					?>
                    <li><strong><?php esc_html_e( 'Govt Tax', 'realhomes-elementor-addon' ); ?>
                            :</strong><span><i><?php esc_html_e( '(per stay)', 'realhomes-elementor-addon' ); ?></i></span><?php echo intval( $govt_tax ) . '%'; ?>
                    </li>
					<?php
				}

				if ( ! empty( $bulk_prices ) ) {
					?>
                    <li class="bulk-pricing-heading"><?php esc_html_e( "Bulk Price", 'realhomes-elementor-addon' ); ?></li>
					<?php
					foreach ( $bulk_prices as $bulk_price ) {
						if ( ! empty( $bulk_price['number_of_nights'] ) && ! empty( $bulk_price['price_per_night'] ) ) {
							?>
                            <li>
                                <strong><?php echo sprintf( esc_html__( 'Price per night (%sdays+)', 'realhomes-elementor-addon' ), $bulk_price['number_of_nights'] ); ?>
                                    :</strong><?php echo ere_format_amount( $bulk_price['price_per_night'] ); ?>
                            </li>
							<?php
						}
					}
				}
				?>
            </ul>
        </div>
    </div>
	<?php
} else {
	rhea_print_no_result_for_editor();
}