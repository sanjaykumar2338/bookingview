<?php
$realhomes_custom_search_form               = get_option( 'realhomes_custom_search_form', 'default' );
$realhomes_custom_search_form_max_width     = get_option( 'realhomes_custom_search_form_max_width' );
$realhomes_custom_search_form_margin_top    = get_option( 'realhomes_custom_search_form_margin_top' );
$realhomes_custom_search_form_margin_bottom = get_option( 'realhomes_custom_search_form_margin_bottom' );

$REAL_HOMES_custom_search_form        = get_post_meta( get_queried_object_id(), 'REAL_HOMES_custom_search_form', true );
$REAL_HOMES_search_form_margin_top    = get_post_meta( get_queried_object_id(), 'REAL_HOMES_search_form_margin_top', true );
$REAL_HOMES_search_form_margin_bottom = get_post_meta( get_queried_object_id(), 'REAL_HOMES_search_form_margin_bottom', true );

if ( ! empty( $realhomes_custom_search_form_max_width ) ) {
	$max_width = $realhomes_custom_search_form_max_width;
} else {
	$max_width = '1320px';
}

if ( ! empty( $REAL_HOMES_search_form_margin_top ) ) {
	$margin_top = $REAL_HOMES_search_form_margin_top;
} else if ( ! empty( $realhomes_custom_search_form_margin_top ) ) {
	$margin_top = $realhomes_custom_search_form_margin_top;
} else {
	$margin_top = 'initial';
}

if ( ! empty( $REAL_HOMES_search_form_margin_bottom ) ) {
	$margin_bottom = $REAL_HOMES_search_form_margin_bottom;
} else if ( ! empty( $realhomes_custom_search_form_margin_bottom ) ) {
	$margin_bottom = $realhomes_custom_search_form_margin_bottom;
} else {
	$margin_bottom = 'initial';
}
if ( '1' !== get_post_meta( get_queried_object_id(), 'REAL_HOMES_hide_advance_search', true ) && inspiry_show_header_search_form() ) {
	if ( class_exists( 'RHEA_Elementor_Search_Form' ) && ( ! empty ( $realhomes_custom_search_form ) || ( ! empty( $REAL_HOMES_custom_search_form ) && 'default' !== $REAL_HOMES_custom_search_form ) ) ) {
		?>
        <div class="rh-custom-search-form-wrapper rhea-hide-before-load" style="margin-top: <?php echo esc_attr( $margin_top ); ?>;margin-bottom:  <?php echo esc_attr( $margin_bottom ); ?>;max-width: <?php echo esc_attr( $max_width ); ?>;">
			<?php
			do_action( 'realhomes_elementor_search_form' );
			?>
        </div>
        <div class="rh-custom-search-form-gutter clearfix"></div>

		<?php
	}
}