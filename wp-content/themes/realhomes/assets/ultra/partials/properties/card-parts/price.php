<div class="rh_prop_card__priceLabel_ultra">
    <p class="rh_prop_card__price_ultra <?php realhomes_hide_price_separator_class(); ?>">
		<?php
		if ( function_exists( 'ere_property_price' ) ) {
			ere_property_price( get_the_ID(), true, true );
		}
		?>
    </p>
</div>