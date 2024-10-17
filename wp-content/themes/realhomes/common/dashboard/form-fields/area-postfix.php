<?php
/**
 * Field: Area Postfix
 *
 * @since 	3.0.0
 * @package realhomes/dashboard
 */
global $edit_property_id;

$property_area_postfix_label = get_option( 'realhomes_submit_property_area_postfix_label' );
if ( empty( $property_area_postfix_label ) ) {
	$property_area_postfix_label = esc_html__( 'Area Postfix Text', 'framework' );
}
?>
<p>
	<label for="area-postfix"><?php echo esc_html( $property_area_postfix_label ); ?></label>
	<input id="area-postfix" name="area-postfix" type="text" value="<?php echo esc_attr( realhomes_get_area_unit( $edit_property_id ) ); ?>" />
</p>

