<?php
/**
 * Dashboard: Bookings List
 *
 * @package realhomes
 * @subpackage dashboard
 * @since 3.21.0
 */

global $paged, $posts_per_page, $property_status_filter, $dashboard_posts_query, $current_module;

// Get the current logged-in user's email
$current_user_email = wp_get_current_user()->user_email;

$bookings_args = array(
	'post_type'   => 'booking',
	'post_status' => 'publish',
	'paged'       => $paged,
	'meta_query' => array(
		array(
			'key'     => 'rvr_property_author_email',
			'value'   => $current_user_email,
			'compare' => '=',
		),
	),
);

// Posts per page parameter
$bookings_args['posts_per_page'] = realhomes_dashboard_posts_per_page();

// Add booking status filter parameter
if ( isset( $_GET['property_status_filter'] ) && ! empty( $_GET['property_status_filter'] ) ) {
	$bookings_args['meta_key']     = 'rvr_booking_status';
	$bookings_args['meta_value']   = sanitize_text_field( $_GET['property_status_filter'] );
	$bookings_args['meta_compare'] = '=';
}

/**
 * Add searched parameter.
 * Note: Adding based on the properties settings.
 */
if ( isset( $_GET['posts_search'] ) && 'show' == get_option( 'inspiry_my_properties_search', 'show' ) ) {
	$bookings_args['s'] = sanitize_text_field( $_GET['posts_search'] );
	printf( '<div class="dashboard-notice"><p>%s <strong>%s</strong></p></div>', esc_html__( 'Search results for: ', 'framework' ), esc_html( $_GET['posts_search'] ) );
}

$dashboard_posts_query = new WP_Query( apply_filters( 'realhomes_dashboard_bookings_args', $bookings_args ) );

if ( $dashboard_posts_query->have_posts() ) {
	?>
    <div id="dashboard-bookings" class="dashboard-bookings dashboard-content-inner">
		<?php
		// Adding top nav
		get_template_part( 'common/dashboard/top-nav' );
		?>
        <div class="dashboard-posts-list">
			<?php get_template_part( 'common/dashboard/partials/booking-columns' ); ?>
            <div class="dashboard-posts-list-body">
				<?php
				while ( $dashboard_posts_query->have_posts() ) {
					$dashboard_posts_query->the_post();
					get_template_part( 'common/dashboard/partials/booking-card', null, array( 'user_email' => $current_user_email ) );
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
		<?php get_template_part( 'common/dashboard/bottom-nav' ); ?>
    </div><!-- #dashboard-bookings -->
	<?php
} else {
	$bookings_not_found_title = esc_html__( 'No Booking Found!', 'framework' );
	if ( isset( $_GET['property_status_filter'] ) && ! empty( $_GET['property_status_filter'] ) ) {
		$bookings_not_found_title = sprintf( esc_html__( 'No "%s" Bookings Found!', 'framework' ), ucfirst( sanitize_text_field( $_GET['property_status_filter'] ) ) );
	}

	$bookings_not_found_statement = esc_html__( 'There are no bookings falling under this criteria.', 'framework' );

	realhomes_dashboard_no_items( $bookings_not_found_title, $bookings_not_found_statement, 'no-booking.svg' );
}
?>