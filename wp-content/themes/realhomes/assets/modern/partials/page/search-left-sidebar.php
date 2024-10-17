<?php
/**
 * Page: Property Search Left Sidebar
 *
 * Property search left sidebar page of the theme.
 *
 * @since    3.0.0
 * @package realhomes/modern
 */

get_header();

// Page Head.
$header_variation = get_option( 'inspiry_search_header_variation' );

if ( empty( $header_variation ) || ( 'none' === $header_variation ) ) {
	get_template_part( 'assets/modern/partials/banner/header' );
} elseif ( ! empty( $header_variation ) && ( 'banner' === $header_variation ) ) {
	get_template_part( 'assets/modern/partials/banner/image' );
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
$ajax_class = '';

if ( $ajax_pagination_enabled ) {
	$ajax_class = 'ajax-pagination';
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

/* Apply Search Filter */
$search_args = apply_filters( 'real_homes_search_parameters', $search_args );

/* Sort Properties */
$search_args = sort_properties( $search_args );

/* Globalising the search query to use in the inner templates */
global $search_query;

/* Search Query */
$search_query = new WP_Query( $search_args );
?>

<?php if ( inspiry_is_search_page_map_visible() ) : ?>
    <div class="rh_map rh_map__search">
		<?php get_template_part( 'assets/modern/partials/properties/map' ); ?>
    </div><!-- /.rh_map rh_map__search -->
<?php endif; ?>

    <section id="properties-listing" class="rh_section rh_section--flex rh_section__left_sidebar rh_wrap--padding rh_wrap--topPadding <?php echo sanitize_html_class( $ajax_search_class ) . ' ' . sanitize_html_class( $ajax_class ); ?>">
		<?php
			// Display any contents after the page banner and before the contents.
			do_action( 'inspiry_before_page_contents' );
		?>
	    <?php
	    $attached_sidebar = RealHomes_Custom_Sidebar::get_attached_sidebar( 'property-search-sidebar' );
        if ( is_active_sidebar( $attached_sidebar ) )  : ?>
            <div class="rh_page rh_page__sidebar">
                <aside class="rh_sidebar">
				    <?php dynamic_sidebar( $attached_sidebar ); ?>
                </aside>
            </div><!-- /.rh_page rh_page__sidebar -->
	    <?php endif; ?>

		<?php $rh_page_class = inspiry_is_search_page_map_visible() ? '' : 'rh_page__listing_page-no-map'; ?>
        <div class="rh_page rh_page__listing_page rh_page__main <?php echo esc_attr( $rh_page_class ); ?>" data-search-url="<?php echo inspiry_get_search_page_url(); ?>">

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

			<?php
			$get_content_position = get_post_meta( get_the_ID(), 'REAL_HOMES_content_area_above_footer', true );
			if ( $get_content_position !== '1' ) {
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						?>
                        <div class="rh_content <?php if ( get_the_content() ) { echo esc_attr( 'rh_page__content' ); } ?>">
							<?php the_content(); ?>
                        </div><!-- /.rh_content -->
						<?php
					}
				}
			}

			$search_results_page_layout = get_option('inspiry_search_results_page_layout', 'list');
			$listing_page_class = '';

			if( 'grid' === $search_results_page_layout  ){
				$listing_page_class .= ' rh_page__listing_grid';
				$inspiry_property_card_variation = get_option( 'inspiry_property_card_variation','1' );
			}

			$attached_sidebar = RealHomes_Custom_Sidebar::get_attached_sidebar( 'property-search-sidebar' );
			if( ! is_active_sidebar( $attached_sidebar ) ){
				$listing_page_class .= ' rh_page__listing_grid-three-column';
			}
			?>
            <div class="rh_page__listing<?php echo esc_attr( $listing_page_class ); ?>">
				<?php
				if ( $search_query->have_posts() ) :
					while ( $search_query->have_posts() ) :
						$search_query->the_post();

						if( 'grid' === $search_results_page_layout ){
							get_template_part( 'assets/modern/partials/properties/grid-card-'.$inspiry_property_card_variation );
						}else{
							get_template_part( 'assets/modern/partials/properties/list-card' );
						}
					endwhile;
					wp_reset_postdata();
				else :
					realhomes_print_no_result( get_option( 'inspiry_search_template_no_result_text' ) );
				endif;
				?>
            </div>
            <!-- /.rh_page__listing -->

            <?php
            if ( $ajax_pagination_enabled ) {
              realhomes_ajax_pagination( $search_query->max_num_pages, $search_query );
            } else {
              inspiry_theme_pagination( $search_query->max_num_pages );
            }
            ?>

        </div>
        <!-- /.rh_page rh_page__main -->

    </section><!-- /.rh_section rh_wrap rh_wrap--padding -->

<?php

if ( '1' === $get_content_position ) {

	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
            <div class="rh_content <?php if ( get_the_content() ) {
				echo esc_attr( 'rh_page__content' );
			} ?>">
				<?php the_content(); ?>
            </div>
            <!-- /.rh_content -->
			<?php

		}
	}
}

get_footer();
