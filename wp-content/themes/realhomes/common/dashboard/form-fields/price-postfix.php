<?php
/**
 * Field: Price Postfix
 *
 * @since 	3.0.0
 * @package realhomes/dashboard
 */
global $edit_property_id;

$property_price_postfix_label = get_option( 'realhomes_submit_property_price_postfix_label' );
if ( empty( $property_price_postfix_label ) ) {
	$property_price_postfix_label = esc_html__( 'Price Postfix Text', 'framework' );
}
?>
<p>
	<label for="price-postfix"><?php echo esc_html( $property_price_postfix_label ); ?> <span><?php esc_html_e( 'Example: Per Month', 'framework' ); ?></span></label>
	<input id="price-postfix" name="price-postfix" type="text" value="<?php echo esc_attr( realhomes_get_price_postfix( $edit_property_id ) ); ?>" />
</p>