<?php
/**
 * For Taxonomy List Layout
 *
 * @package    realhomes
 * @subpackage modern
 */

/* Theme Listing Page Module */
$theme_listing_module = get_option( 'theme_listing_module' );

switch ( $theme_listing_module ) {
	case 'properties-map':
		echo '<div class="rh_map rh_map__search">';
		get_template_part( 'assets/modern/partials/properties/map' );
		echo '</div>';
		break;

	default:
		break;
}

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
    <div class="rh_page rh_page__listing_page rh_page__main">

		<?php get_template_part( 'assets/modern/partials/taxonomy/taxonomy', 'title' ); ?>

        <div class="rh_page__listing">
			<?php
			$sort_query_args = array();
			$sort_query_args = sort_properties( $sort_query_args );

			global $wp_query;
			$args           = array_merge( $wp_query->query_vars, $sort_query_args );
			$taxonomy_query = new WP_Query( $args );

			if ( $taxonomy_query->have_posts() ) :
				while ( $taxonomy_query->have_posts() ) :
					$taxonomy_query->the_post();
					// Display property in list layout.
					get_template_part( 'assets/modern/partials/properties/list-card' );
				endwhile;
				wp_reset_postdata();
			else :
				realhomes_print_no_result();
			endif;
			?>
        </div><!-- /.rh_page__listing -->

		<?php
		if ( $ajax_pagination_enabled ) {
			realhomes_ajax_pagination( $wp_query->max_num_pages, $wp_query );
		} else {
			inspiry_theme_pagination( $wp_query->max_num_pages );
		}
		?>
    </div><!-- /.rh_page rh_page__main -->

	<?php
	$attached_sidebar = RealHomes_Custom_Sidebar::get_attached_sidebar( 'property-listing-sidebar' );
    if ( is_active_sidebar( $attached_sidebar ) ) :
        ?>
        <div class="rh_page rh_page__sidebar">
			<?php get_sidebar( 'property-listing' ); ?>
        </div><!-- /.rh_page rh_page__sidebar -->
	<?php endif; ?>

</section><!-- /.rh_section rh_wrap rh_wrap--padding -->