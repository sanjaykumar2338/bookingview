<?php
/**
 * Banner: Taxonomy
 *
 * Banner for taxonomy templates.
 *
 * @package realhomes
 * @subpackage modern
 */

// Banner Image.
$banner_image_path = get_default_banner();

// Banner Title.
$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$banner_title = $current_term->name;
$taxonomy_name = '';

if ( 'show' === get_option( 'realhomes_taxonomy_name_before_title', 'show' ) ) {
    $taxonomy_name = ucwords( str_replace('-', ' ', $current_term->taxonomy ));
}
?>
<section id="rh-banner-attachment-<?php echo esc_attr( get_option( 'inspiry_banner_image_attachment', 'parallax' ) ); ?>" class="rh_banner rh_banner__image" style="background-image: url('<?php echo esc_url( $banner_image_path ); ?>');">
	<div class="rh_banner__cover"></div>
	<div class="rh_banner__wrap">
		<h1 class="rh_banner__title">
            <?php
            echo ! empty( $taxonomy_name ) ? '<span class="tax-title">' . esc_html( $taxonomy_name ) . '</span>' : '';
            echo esc_html( $banner_title );
            ?>
        </h1>
		<div class="rh_banner__controls">
			<?php get_template_part( 'assets/modern/partials/properties/view-buttons' ); ?>
		</div>
	</div>
</section>