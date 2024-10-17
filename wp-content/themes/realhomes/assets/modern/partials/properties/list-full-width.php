<?php
/**
 * Properties List Layout
 *
 * Displays properties in list layout
 *
 * @package    realhomes
 * @subpackage modern
 */

/*
 * 1. Apply sticky posts filter.
 * 2. Display google maps.
 */
get_template_part( 'assets/modern/partials/properties/common-top' );

$ajax_pagination_enabled = realhomes_get_ajax_pagination_status();
$ajax_class              = '';

if ( $ajax_pagination_enabled ) {
	$ajax_class = 'ajax-pagination';
}

?>

<section id="properties-listing" class="rh_section rh_section--flex rh_wrap--padding rh_wrap--topPadding <?php echo esc_attr( $ajax_class ); ?>">
	<?php
	// Display any contents after the page banner and before the contents.
	do_action( 'inspiry_before_page_contents' );
	?>
    <div class="rh_page rh_page__listing_page listing__list_fullwidth rh_page__main">

		<?php
		$number_of_properties = intval( get_option( 'theme_number_of_properties' ) );
		if ( ! $number_of_properties ) {
			$number_of_properties = 6;
		}

		global $paged;
		$paged = 1;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) { // if is static front page
			$paged = get_query_var( 'page' );
		}

		$property_listing_args = array(
			'post_type'      => 'property',
			'posts_per_page' => $number_of_properties,
			'paged'          => $paged,
		);

		// Apply properties filter.
		$property_listing_args = apply_filters( 'inspiry_properties_filter', $property_listing_args );

		$property_listing_args = sort_properties( $property_listing_args );

		// making the query scope global to use inside the template files
		global $property_listing_query;

		$property_listing_query = new WP_Query( $property_listing_args );

		if ( $property_listing_query->have_posts() ) :


			/*
			 * 1. Display page's title.
			 * 2. Display page's sort controls.
			 * 3. Display page's layout buttons.
			 */
			get_template_part( 'assets/modern/partials/properties/common-content-top' );
			?>

			<?php
			/*
			 * 1. Display page contents.
			 * 2. Display compare properties module.
			 */
			$get_content_position = get_post_meta( get_the_ID(), 'REAL_HOMES_content_area_above_footer', true );

			if ( $get_content_position !== '1' ) {
				get_template_part( 'assets/modern/partials/properties/common-content' );
			}
			?>

            <div class="rh_page__listing">

				<?php
				while ( $property_listing_query->have_posts() ) :
					$property_listing_query->the_post();

					// display property for list layout.
					get_template_part( 'assets/modern/partials/properties/list-card' );

				endwhile;
				wp_reset_postdata();
				?>
            </div><!-- /.rh_page__listing -->

		<?php
		else :
			realhomes_print_no_result();
		endif;

		if ( $ajax_pagination_enabled ) {
			realhomes_ajax_pagination( $property_listing_query->max_num_pages, $property_listing_query );
		} else {
			inspiry_theme_pagination( $property_listing_query->max_num_pages );
		}
		?>
    </div><!-- /.rh_page rh_page__main -->

</section>
<?php
if ( '1' === $get_content_position ) {
	get_template_part( 'assets/modern/partials/properties/common-content' );
}
?>
<!-- /.rh_section rh_wrap rh_wrap--padding -->
