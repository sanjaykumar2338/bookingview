<?php
/**
 * Archive: Property Grid Container
 *
 * Grid properties container for archive template.
 *
 * @package  realhomes
 * @subpackage modern
 */

// Page Head.
$header_variation = get_option( 'inspiry_listing_header_variation', 'none' );

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
?>
<section class="rh_section rh_section--flex rh_wrap--padding rh_wrap--topPadding">
	<?php
	// Display any contents after the page banner and before the contents.
	do_action( 'inspiry_before_page_contents' );
	?>
    <div class="rh_page rh_page__listing_page rh_page__main">
        <div class="rh_page__head">
			<?php if ( empty( $header_variation ) || ( 'none' === $header_variation ) ) : ?>
                <h2 class="rh_page__title">
                    <p class="title"><?php post_type_archive_title(); ?></p>
                </h2>
			<?php endif; ?>
            <div class="rh_page__controls">
				<?php get_template_part( 'assets/modern/partials/properties/sort-controls' ); ?>
				<?php if ( empty( $header_variation ) || ( 'none' === $header_variation ) ) : ?>
					<?php get_template_part( 'assets/modern/partials/properties/view-buttons' ); ?>
				<?php endif; ?>
            </div><!-- /.rh_page__controls -->
        </div><!-- /.rh_page__head -->
        <div class="rh_page__listing rh_page__listing_grid <?php printf( 'rh-grid-%s-columns', esc_attr( get_option( 'realhomes_grid_template_column', '2' ) ) ); ?>">
			<?php
			global $wp_query;
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					// Display property in list layout.
					get_template_part( 'assets/modern/partials/properties/grid-card-' . get_option( 'inspiry_property_card_variation', '1' ) );
				endwhile;
			else :
				realhomes_print_no_result();
			endif;
			?>
        </div><!-- /.rh_page__listing -->

		<?php inspiry_theme_pagination( $wp_query->max_num_pages ); ?>

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