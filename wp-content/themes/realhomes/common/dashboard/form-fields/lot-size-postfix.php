<?php
/**
 * Field: Lot Size Postfix
 *
 * @since    3.9.2
 * @package realhomes/dashboard
 */
global $edit_property_id;

$property_lot_size_postfix_label = get_option( 'realhomes_submit_property_lot_size_postfix_label' );
if ( empty( $property_lot_size_postfix_label ) ) {
	$property_lot_size_postfix_label = esc_html__( 'Lot Size Postfix Text', 'framework' );
}
?>
<p>
    <label for="lot-size-postfix"><?php echo esc_html( $property_lot_size_postfix_label ); ?></label>
    <input id="lot-size-postfix" name="lot-size-postfix" type="text" value="<?php echo esc_attr( realhomes_get_lot_unit( $edit_property_id ) ); ?>"/>
</p>