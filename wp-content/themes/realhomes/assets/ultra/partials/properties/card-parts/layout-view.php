<div class="rh-ultra-view-type">
	<?php
	$page_url = null;
	// Page url.
	if ( is_post_type_archive( 'property' ) ) {
		$page_url = get_post_type_archive_link( 'property' );
	} else if ( is_tax() ) {

		$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$page_url     = get_term_link( $current_term );

	} else {
		global $post;
		$page_url = get_permalink( get_the_ID() );
	}

	// Separator.
	$separator = ( null == parse_url( $page_url, PHP_URL_QUERY ) ) ? '?' : '&';

	// View Type.
	$view_type = 'list';
	if ( is_page_template( array( 'templates/properties.php', 'templates/properties-search.php' ) ) ) {
		if ( isset( $args['is_grid_card'] ) && $args['is_grid_card'] ) {
			$view_type = 'grid';
		}
	}

	if ( isset( $_GET['view'] ) && in_array( $_GET['view'], array( 'grid', 'list' ) ) ) {
		$view_type = $_GET['view'];
	}
	?>
    <a class="grid <?php echo ( 'grid' === $view_type ) ? 'active' : ''; ?>" href="<?php echo esc_url( $page_url . $separator . 'view=grid' ); ?>">
		<?php inspiry_safe_include_svg( '/icons/icon-sort-grid.svg' ); ?>
    </a>
    <a class="list <?php echo ( 'list' === $view_type ) ? 'active' : ''; ?>" href="<?php echo esc_url( $page_url . $separator . 'view=list' ); ?>">
		<?php inspiry_safe_include_svg( '/icons/icon-sort-list.svg' ); ?>
    </a>
</div>