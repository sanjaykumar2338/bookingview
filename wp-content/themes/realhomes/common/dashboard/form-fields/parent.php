<?php
/**
 * Field: Parent Property
 *
 * @since    3.0.0
 * @package  realhomes/dashboard
 */

$property_parent_label = get_option( 'realhomes_submit_property_parent_property_label' );
if ( empty( $property_parent_label ) ) {
	$property_parent_label = esc_html__( 'Parent Property', 'framework' );
}

$post_type_object = get_post_type_object( 'property' );
if ( ! empty( $post_type_object ) && $post_type_object->hierarchical ) {
	$parent_properties_dropdown_args = array(
		'post_type'        => 'property',
		'name'             => 'property_parent_id',
		'show_option_none' => esc_html__( 'No Parent', 'framework' ),
		'sort_column'      => 'menu_order, post_title',
		'echo'             => 0,
		'class'            => 'inspiry_select_picker_trigger show-tick',

	);

	if ( realhomes_dashboard_edit_property() ) {
		global $edit_property_id;
		global $target_property;
		$parent_properties_dropdown_args['exclude_tree'] = $edit_property_id;
		$parent_properties_dropdown_args['selected']     = $target_property->post_parent;
	}

	$parent_properties_dropdown = wp_dropdown_pages( $parent_properties_dropdown_args );

	if ( ! empty( $parent_properties_dropdown ) ) {
		?>
        <div class="col-md-6 col-lg-4">
            <p>
                <label for="property_parent_id"><?php echo esc_html( $property_parent_label ); ?> <span><?php esc_html_e( '( If Any )', 'framework' ); ?></span></label>
				<?php
				$parent_properties_dropdown = str_replace( '<select', ' <select data-live-search="true" ', $parent_properties_dropdown, );

				echo wp_kses( $parent_properties_dropdown, array(
					'select' => array(
						'id'               => array(),
						'class'            => array(),
						'name'             => array(),
						'value'            => array(),
						'type'             => array(),
						'data-live-search' => array(),
					),
					'option' => array(
						'value'    => array(),
						'selected' => array(),
						'class'    => array(),
					),
				) );
				?>
            </p>
        </div>
		<?php
	}
}