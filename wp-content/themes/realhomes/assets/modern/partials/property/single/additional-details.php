<?php
/**
 * Single Property: Additional Details
 *
 * @package    realhomes
 * @subpackage modern
 */

global $post;

ere_additional_details_migration( get_the_ID() ); // Migrate property additional details from old metabox key to new key.
$additional_details = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', true );

if ( ! empty( $additional_details ) ) {
	$additional_details = array_filter( $additional_details ); // remove empty values.
}

if ( ! empty( $additional_details ) ) {
	?>
    <div id="property-content-section-additional-details" class="property-content-section <?php realhomes_printable_section( 'additional-details' ); ?>">
		<?php

		$additional_details_title = get_option( 'theme_additional_details_title' );
		if ( ! empty( $additional_details_title ) ) {
			echo '<h4 class="rh_property__heading rh_property__additional_details">' . esc_html( $additional_details_title ) . '</h4>';
		}

		echo '<div class="rh-property-additional-inner-wrap"><ul class="rh_property__additional clearfix">';
			$property_link = '';
			foreach ( $additional_details as $detail ) {
				?>
				<li>
					<?php 
					if ( strcasecmp( trim($detail[0]), 'Powered by Realtor.ca' ) === 0 ) { 
						$property_link = esc_url( $detail[1] );
					?>
						<!-- Disclaimer div -->
						<div class="disclaimerlogo">
							<a alt="Powered by: REALTOR.ca" target="_blank" rel="noopener noreferrer" href="<?php echo $property_link; ?>">
								<img src="https://www.realtor.ca/images/en-ca/powered_by_realtor.svg" width="125" height="60">
							</a>
						</div>
					<?php } else { ?>
						<span class="title"><?php echo esc_html( $detail[0] ); ?>:</span>
						<span class="value"><?php echo esc_html( $detail[1] ); ?></span>
					<?php } ?>
				</li>
				<?php
			}
			echo '</ul></div>';

		?>

		<br>
    </div>
	<?php
}