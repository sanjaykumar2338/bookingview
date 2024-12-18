<?php
/**
 * Common Taxonomy File
 *
 * @since 1.0.0
 * @package realhomes/classic
 */

get_header();

/* Determine the type header to be used for taxonomy */
$theme_listing_module = get_option( 'theme_listing_module' );

switch ( $theme_listing_module ) {
    case 'properties-map':
	    get_template_part( 'assets/classic/partials/banners/map' );
        break;
    default:
        get_template_part( 'assets/classic/partials/banners/taxonomy' );
        break;
}

/* Check View Type */
if ( isset( $_GET['view'] ) ) {
    $view_type = $_GET['view'];
} else {
    /* Theme Options Listing Layout */
    $view_type = get_option( 'theme_listing_layout' );
}

$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$banner_title = $current_term->name;
$taxonomy_name = '';

if ( 'show' === get_option( 'realhomes_taxonomy_name_before_title', 'show' ) ) {
	$taxonomy_name = ucwords( str_replace('-', ' ', $current_term->taxonomy ));
}
?>
<div class="container contents listing-grid-layout">
	<?php
		// Display any contents after the page banner and before the contents.
		do_action( 'inspiry_before_page_contents' );
	?>
    <div class="row">
        <div class="span9 main-wrap">

            <!-- Main Content -->
            <div class="main">

                <section class="listing-layout <?php if ( 'grid' == $view_type ) { echo 'property-grid'; } ?>">

                    <?php
                    $theme_listing_module = get_option( 'theme_listing_module' );
                    if ( 'properties-map' == $theme_listing_module ) {
	                    ?>
                        <h1 class="title-heading">
		                    <?php
		                    echo ! empty( $taxonomy_name ) ? '<span class="tax-title">' . esc_html( $taxonomy_name ) . ': </span>' : '';
		                    echo esc_html( $banner_title );
		                    ?>
                        </h1>
	                    <?php
                    } else {
	                    ?>
                        <h3 class="title-heading">
		                    <?php
		                    echo ! empty( $taxonomy_name ) ? '<span class="tax-title">' . esc_html( $taxonomy_name ) . ': </span>' : '';
		                    echo esc_html( $banner_title );
		                    ?>
                        </h3>
	                    <?php
                    }

                    // Listing view type.
                    get_template_part( 'assets/classic/partials/properties/view-buttons' );
                    ?>

                    <div class="list-container clearfix">
                        <?php
                        get_template_part( 'assets/classic/partials/properties/sort-controls' );

                        inspiry_term_description();

                        $sort_query_args = array();
                        $sort_query_args = sort_properties( $sort_query_args );

                        global $wp_query;
                        $args = array_merge( $wp_query->query_vars, $sort_query_args );
                        $taxonomy_query = new WP_Query( $args );

                        if ( $taxonomy_query->have_posts() ) :
	                        $counter = 1;
                            while ( $taxonomy_query->have_posts() ) :
                                $taxonomy_query->the_post();

                                if ( 'grid' == $view_type ) {
                                    /* Display Property for Grid */
                                    get_template_part( 'assets/classic/partials/properties/grid-card' );
	                                if ( $counter % 2 == 0 ) {
		                                ?>
                                        <div class="clearfix rh-visible-xs"></div>
		                                <?php
	                                }
	                                if ( $counter % 3 == 0 ) {
		                                ?>
                                        <div class="clearfix rh-visible-sm rh-visible-md rh-visible-lg"></div>
		                                <?php
	                                }
	                                $counter ++;
                                } else {
                                    /* Display Property for Listing */
                                    get_template_part( 'assets/classic/partials/properties/list-card' );
                                }

                            endwhile;
                            wp_reset_postdata();
                        else :
	                        realhomes_print_no_result( '', array( 'container_class' => 'alert-wrapper' ) );
                        endif;
                        ?>
                    </div>

                    <?php theme_pagination( $wp_query->max_num_pages ); ?>

                </section>

            </div><!-- End Main Content -->

        </div> <!-- End span9 -->

        <?php get_sidebar( 'property-listing' ); ?>

    </div><!-- End contents row -->
</div>

<?php get_footer(); ?>
