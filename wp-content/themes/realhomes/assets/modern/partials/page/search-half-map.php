<?php
/**
 * Template: Property Search Half Map
 *
 * Property search template.
 *
 * @package    realhomes
 * @subpackage modern
 */

get_header();

// Page Head.
$header_variation = get_option( 'inspiry_search_header_variation' );

if ( empty( $header_variation ) || ( 'none' === $header_variation ) ) {
	get_template_part( 'assets/modern/partials/banner/header' );
} elseif ( 'banner' === $header_variation ) {
	get_template_part( 'assets/modern/partials/banner/image' );
}

if ( ! is_page_template( 'templates/properties-search.php' ) ) {
	echo '</div>'; // close inspiry_half_map_header_wrapper in header.php
}

if ( inspiry_show_header_search_form() ) {
	get_template_part( 'assets/modern/partials/properties/search/advance' );
}

// Ajax Search Results
$ajax_results_status = realhomes_get_ajax_search_results_status();
$ajax_search_class   = '';

if ( $ajax_results_status ) {
	$ajax_search_class = 'realhomes_ajax_search';
}

// Ajax pagination
$ajax_pagination_enabled = realhomes_get_ajax_pagination_status();
$ajax_class              = '';

if ( $ajax_pagination_enabled ) {
	$ajax_class = 'ajax-pagination';
}

$show_search_form_widget = inspiry_show_search_form_widget();
$rh_section_class        = '';

if ( ! $show_search_form_widget ) {
	$rh_section_class = 'rh_section__map_listing';
}

$number_of_properties = intval( get_option( 'theme_properties_on_search' ) );
if ( ! $number_of_properties ) {
	$number_of_properties = 6;
}

global $paged;
$paged = 1;
if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) { // if is static front page
	$paged = get_query_var( 'page' );
}

$search_args = array(
	'post_type'      => 'property',
	'posts_per_page' => $number_of_properties,
	'paged'          => $paged,
);

if ( inspiry_is_search_page_map_visible() ) {

	$search_args['meta_query'] = array(
		array(
			'key'     => 'REAL_HOMES_property_address',
			'compare' => 'EXISTS',
		),
	);

}

/* Apply Search Filter */
$search_args = apply_filters( 'real_homes_search_parameters', $search_args );

/* Sort Properties */
$search_args = sort_properties( $search_args );

/* Globalising the search query to use in the inner templates */
global $search_query;

/* Search Query */
$search_query = new WP_Query( $search_args ); ?>

    <section id="properties-listing" class="rh_section rh_section--flex <?php echo sanitize_html_class( $rh_section_class ) . ' ' . sanitize_html_class( $ajax_search_class ) . ' ' . sanitize_html_class( $ajax_class ); ?>">

        <div class="rh_page rh_page__listing_map" data-search-url="<?php echo inspiry_get_search_page_url(); ?>">
            <?php get_template_part( 'assets/modern/partials/properties/map' ); ?>
        </div><!-- /.rh_page rh_page__listing_map -->

        <div class="rh_page rh_page__map_properties">
			<?php
			$get_content_position = get_post_meta( get_the_ID(), 'REAL_HOMES_content_area_above_footer',true );

			if(  $get_content_position !== '1'){

				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						?>
                        <div class="rh_content <?php if(get_the_content()){ echo esc_attr('rh_page__content');} ?>">
							<?php the_content(); ?>
                        </div>
                        <!-- /.rh_content -->
						<?php

					}
				}
			}
			?>

            <div class="rh_page__head">

	            <?php get_template_part( 'assets/modern/partials/properties/search/page-stats' ); ?>

                <div class="rh_page__controls">
					<?php
						get_template_part( 'assets/modern/partials/properties/save-alert-button', '', array( 'search_args' => $search_args ) );
						get_template_part( 'assets/modern/partials/properties/sort-controls' );
					?>
                </div>
                <!-- /.rh_page__controls -->

            </div>
            <!-- /.rh_page__head -->

            <div class="rh_page__listing" data-search-url="<?php echo inspiry_get_search_page_url(); ?>">
				<?php
				if ( $search_query->have_posts() ) : while ( $search_query->have_posts() ) : $search_query->the_post();
                        get_template_part( 'assets/modern/partials/properties/half-map-card' );
					endwhile;
					wp_reset_postdata();
				else :
					realhomes_print_no_result( get_option( 'inspiry_search_template_no_result_text' ) );
                endif;
                ?>
            </div><!-- /.rh_page__listing -->

            <?php
            if ( $ajax_pagination_enabled ) {
              realhomes_ajax_pagination( $search_query->max_num_pages, $search_query );
            } else {
              inspiry_theme_pagination( $search_query->max_num_pages );
            }
            ?>

        </div><!-- /.rh_page rh_page__map_properties -->

    </section>

<?php
if ('1' === $get_content_position ) {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
            <div class="rh_content rh_content_above_footer <?php if ( get_the_content() ) {echo esc_attr( 'rh_page__content' );} ?>">
				<?php the_content(); ?>
            </div><!-- /.rh_content -->
			<?php
		}
	}
}
?><!-- /.rh_section rh_wrap rh_wrap--padding -->

<?php
get_footer();
