<?php
/**
 * Properties advance search.
 *
 * @package    realhomes
 * @subpackage modern
 */

// Get current page ID other home page.
$page_id = get_the_ID();
if ( is_home() ) {
	$page_id = get_queried_object_id();
} else if ( is_singular( 'post' ) ) {
	// Use posts page (REAL_HOMES_hide_advance_search) meta setting for single post page.
	$posts_page_id = get_option( 'page_for_posts' );
	if ( ! empty( $posts_page_id ) ) {
		$page_id = $posts_page_id;
	}
}

$realhomes_custom_search_form               = get_option( 'realhomes_custom_search_form', 'default' );
$realhomes_custom_search_form_margin_top    = get_option( 'realhomes_custom_search_form_margin_top' );
$realhomes_custom_search_form_margin_bottom = get_option( 'realhomes_custom_search_form_margin_bottom' );

$REAL_HOMES_custom_search_form        = get_post_meta( $page_id, 'REAL_HOMES_custom_search_form', true );
$REAL_HOMES_search_form_margin_top    = get_post_meta( $page_id, 'REAL_HOMES_search_form_margin_top', true );
$REAL_HOMES_search_form_margin_bottom = get_post_meta( $page_id, 'REAL_HOMES_search_form_margin_bottom', true );

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

if ( '1' !== get_post_meta( $page_id, 'REAL_HOMES_hide_advance_search', true ) && inspiry_show_header_search_form() ) {

	if ( class_exists( 'RHEA_Elementor_Search_Form' ) && ( 'default' !== $realhomes_custom_search_form || ( ! empty( $REAL_HOMES_custom_search_form ) && 'default' !== $REAL_HOMES_custom_search_form ) ) ) {
		?>
        <div class="rh-custom-search-form-wrapper rhea-hide-before-load" style="margin-top: <?php echo esc_attr( $margin_top ); ?>; margin-bottom:  <?php echo esc_attr( $margin_bottom ) ?>">
			<?php do_action( 'realhomes_elementor_search_form' ); ?>
        </div>
		<?php
	} else {
		$show_search = is_page_template( 'templates/home.php' ) ? get_post_meta( $page_id, 'theme_show_home_search', true ) : inspiry_show_header_search_form();
		if ( inspiry_is_search_page_configured() && $show_search ) {
			?>
            <div class="inspiry_show_on_doc_ready rh_prop_search rh_prop_search_init">
				<?php
				switch ( get_option( 'inspiry_search_form_mod_layout_options', 'default' ) ) {
					case 'default';
						get_template_part( 'assets/modern/partials/properties/search/form' );
						break;
					case 'smart';
						get_template_part( 'assets/modern/partials/properties/search/form-smart' );
						break;
				}
				?>
            </div><!-- /.rh_prop_search -->
			<?php
		}
	}
}
