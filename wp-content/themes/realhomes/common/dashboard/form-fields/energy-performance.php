<?php
/**
 * Fields: Energy Performance
 *
 * @since 3.9.5
 * @package realhomes/dashboard
 */

$property_epc_section_label        = get_option( 'realhomes_submit_property_ep_section_label' );
$property_energy_class_label       = get_option( 'realhomes_submit_property_energy_class_label' );
$property_energy_performance_label = get_option( 'realhomes_submit_property_energy_performance_label' );
$property_current_rating_label     = get_option( 'realhomes_submit_property_epc_current_rating_label' );
$property_potential_rating_label   = get_option( 'realhomes_submit_property_epc_potential_rating_label' );

if ( empty( $property_epc_section_label ) ) {
	$property_epc_section_label = esc_html__( 'Energy Performance', 'framework' );
}

if ( empty( $property_energy_class_label ) ) {
	$property_energy_class_label = esc_html__( 'Energy Class', 'framework' );
}

if ( empty( $property_energy_performance_label ) ) {
	$property_energy_performance_label = esc_html__( 'Energy Performance', 'framework' );
}

if ( empty( $property_current_rating_label ) ) {
	$property_current_rating_label = esc_html__( 'EPC Current Rating', 'framework' );
}

if ( empty( $property_potential_rating_label ) ) {
	$property_potential_rating_label = esc_html__( 'EPC Potential Rating', 'framework' );
}

if ( realhomes_dashboard_edit_property() ) {
	global $post_meta_data;

	if ( isset( $post_meta_data['REAL_HOMES_energy_class'] ) ) {
		$energy_class = $post_meta_data['REAL_HOMES_energy_class'][0];
	}

	if ( isset( $post_meta_data['REAL_HOMES_energy_performance'] ) ) {
		$energy_performance = $post_meta_data['REAL_HOMES_energy_performance'][0];
	}

	if ( isset( $post_meta_data['REAL_HOMES_epc_current_rating'] ) ) {
		$current_rating = $post_meta_data['REAL_HOMES_epc_current_rating'][0];
	}

	if ( isset( $post_meta_data['REAL_HOMES_epc_potential_rating'] ) ) {
		$potential_rating = $post_meta_data['REAL_HOMES_epc_potential_rating'][0];
	}
}
?>
<div class="energy-performance-wrapper">
    <label class="label-boxed"><?php echo esc_html( $property_epc_section_label ); ?></label>
    <div class="row">
        <div class="col-lg-6">
            <p>
                <label for="energy-class"><?php echo esc_html( $property_energy_class_label ); ?></label>
                <select name="energy-class" id="energy-class" class="inspiry_select_picker_trigger">
					<?php
					$selected = '-1';
					if ( ! empty( $energy_class ) ) {
						$selected = $energy_class;
					}

					$energy_classes_data = get_option( 'inspiry_property_energy_classes' );

					if ( empty( $energy_classes_data ) ) {
						$energy_classes = array(
							'none' => esc_html__( 'None', 'framework' ),
						);

						if ( function_exists( 'ere_epc_default_fields' ) ) {
							$energy_classes_data = ere_epc_default_fields();

							if ( ! empty( $energy_classes_data ) && is_array( $energy_classes_data ) ) {
								foreach ( $energy_classes_data as $energy_class ) {
									$energy_classes[ $energy_class['name'] ] = $energy_class['name'];
								}
							}
						}
					} else {
						$energy_classes = array(
							'none' => esc_html__( 'None', 'framework' ),
						);
						foreach ( $energy_classes_data as $class => $data ) {
							$energy_classes[ $data['name'] ] = $data['name'];
						}
					}

					foreach ( $energy_classes as $key => $value ) {
						?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $key === $selected ) ? 'selected' : ''; ?>><?php echo esc_html( $value ); ?></option>
						<?php
					}
					?>
                </select>
            </p>
        </div>
        <div class="col-lg-6">
            <p>
                <label for="energy-performance"><?php echo esc_html( $property_energy_performance_label ); ?><span><?php esc_html_e( '( Example: 100 kWh/m²a )', 'framework' ); ?></span></label>
                <input id="energy-performance" name="energy-performance" type="text" value="<?php echo ( ! empty( $energy_performance ) ) ? esc_attr( $energy_performance ) : false; ?>" title="<?php esc_attr_e( 'Energy Performance', 'framework' ); ?>"/>
            </p>
        </div>
        <div class="col-lg-6">
            <p>
                <label for="epc-current-rating"><?php echo esc_html( $property_current_rating_label ); ?></label>
                <input id="epc-current-rating" name="epc-current-rating" type="text" value="<?php echo ( ! empty( $current_rating ) ) ? esc_attr( $current_rating ) : false; ?>" title="<?php esc_attr_e( 'EPC Current Rating', 'framework' ); ?>"/>
            </p>
        </div>
        <div class="col-lg-6">
            <p>
                <label for="epc-potential-rating"><?php echo esc_html( $property_potential_rating_label ); ?></label>
                <input id="epc-potential-rating" name="epc-potential-rating" type="text" value="<?php echo ( ! empty( $potential_rating ) ) ? esc_attr( $potential_rating ) : false; ?>" title="<?php esc_attr_e( 'EPC Potential Rating', 'framework' ); ?>"/>
            </p>
        </div>
    </div>
</div>
