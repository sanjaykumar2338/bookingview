<?php
/**
 * This file is responsible for the property meta-boxes related to RVR plugin.
 *
 * @package    realhomes_vacation_rentals
 * @subpackage realhomes_vacation_rentals/admin
 */
if ( ! function_exists( 'rvr_add_metabox_tabs' ) ) {
	/**
	 * Adds RVR related metabox tabs to property metaboxes
	 *
	 * @param $property_metabox_tabs
	 *
	 * @return array
	 */
	function rvr_add_metabox_tabs( $property_metabox_tabs ) {

		if ( is_array( $property_metabox_tabs ) ) {

			$property_metabox_tabs['rvr'] = array(
				'label' => esc_html__( 'Vacation Rentals', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);
		}

		return $property_metabox_tabs;
	}

	add_filter( 'ere_property_metabox_tabs', 'rvr_add_metabox_tabs', 10 );
}

if ( ! function_exists( 'rvr_add_remaining_metabox_tabs' ) ) {
	/**
	 * Adds RVR related remaining metabox tabs to property metaboxes
	 *
	 * @param $property_metabox_tabs
	 *
	 * @since 1.4.3
	 *
	 * @return array
	 */
	function rvr_add_remaining_metabox_tabs( $property_metabox_tabs ) {

		if ( is_array( $property_metabox_tabs ) ) {
			$property_metabox_tabs['rvr_bulk_seasonal_prices'] = array(
				'label' => esc_html__( 'Bulk & Seasonal Prices', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);

			$property_metabox_tabs['rvr_reserve_booking_dates'] = array(
				'label' => esc_html__( 'Reserve Booking Dates', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);
			
			$property_metabox_tabs['rvr_fees_amenities'] = array(
				'label' => esc_html__( 'Fees & Amenities', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);

			$property_metabox_tabs['rvr_guests_accommodation'] = array(
				'label' => esc_html__( 'Guests Accommodation', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);

			$property_metabox_tabs['rvr_outdoor_surroundings'] = array(
				'label' => esc_html__( 'Outdoor & Surroundings', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);

			$property_metabox_tabs['rvr_included_excluded'] = array(
				'label' => esc_html__( 'Included vs. Not Included', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);

			$property_metabox_tabs['rvr_property_policies'] = array(
				'label' => esc_html__( 'Policies or Rules', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);

			$property_metabox_tabs['rvr_icalendar_sync'] = array(
				'label' => esc_html__( 'iCalendar Sync', 'realhomes-vacation-rentals' ),
				'icon'  => 'dashicons-palmtree',
			);
		}

		return $property_metabox_tabs;
	}

	add_filter( 'ere_property_metabox_tabs', 'rvr_add_remaining_metabox_tabs', 12 );
}

if ( ! function_exists( 'rvr_add_metabox_fields' ) ) {
	/**
	 * Adds RVR related metabox fields to property metaboxes
	 *
	 * @param $property_metabox_fields
	 *
	 * @return array
	 */
	function rvr_add_metabox_fields( $property_metabox_fields ) {

		$property_id = false;
		if ( isset( $_GET['post'] ) ) {
			$property_id = intval( $_GET['post'] );
		} else if ( isset( $_POST['post_ID'] ) ) {
			$property_id = intval( $_POST['post_ID'] );
		}

		// Owners.
		// todo: make it dynamic to search and load required owner
		$owners_array = get_posts( array(
			'post_type'        => 'owner',
			'posts_per_page'   => 500,
			'suppress_filters' => 0,
		) );

		$owners_posts = array( 0 => esc_html__( 'None', 'realhomes-vacation-rentals' ) );
		if ( count( $owners_array ) > 0 ) {
			foreach ( $owners_array as $owner_post ) {
				$owners_posts[ $owner_post->ID ] = $owner_post->post_title;
			}
		}

		// Get RVR settings to use meta tabs label information
		$rvr_settings = get_option( 'rvr_settings' );

		// Instant booking option data.
		if ( rvr_is_wc_payment_enabled() && ! empty( $rvr_settings['rvr_wc_payments_scope'] ) && 'property' === $rvr_settings['rvr_wc_payments_scope'] ) {
			$bm_option_label = esc_html__( 'Booking Scope', 'realhomes-vacation-rentals' );
			$instant_desc    = sprintf( esc_html__( '%sInstant Payment:%s Payment is made immediately, and booking is confirmed automatically.%s', 'realhomes-vacation-rentals' ), '<strong>', '</strong>', '<br>' );
			$deferred_desc   = sprintf( esc_html__( '%sDeferred Payment:%s Booking is made, and the renter can only pay to confirm it after the property owner issues the invoice.', 'realhomes-vacation-rentals' ), '<strong>', '</strong>' );
			$bm_option_desc  = $instant_desc . $deferred_desc;
			$bm_option_type  = 'radio';
		} else {
			$bm_option_label = '';
			$bm_option_desc  = '';
			$bm_option_type  = 'hidden';
		}

		// RVR - meta fields information
		$rvr_metabox_fields = array(
			array(
				'id'                => 'rvr_booking_widget_display',
				'name'              => esc_html__( 'Booking Widget on this Property?', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'Control the display of the booking widget on this property with following option.', 'realhomes-vacation-rentals' ),
				'type'              => 'radio',
				'options'           => array(
					'show' => esc_html__( 'Show', 'realhomes-vacation-rentals' ),
					'hide' => esc_html__( 'Hide', 'realhomes-vacation-rentals' ),
				),
				'std'               => 'show',
				'columns'           => 6,
				'tab'               => 'rvr',
			),
			array(
				'name'    => $bm_option_label,
				'desc'    => $bm_option_desc,
				'id'      => 'rvr_booking_mode',
				'type'    => $bm_option_type,
				'std'     => 'deferred',
				'options' => array(
					'instant'  => esc_html__( 'Instant Payment', 'realhomes-vacation-rentals' ),
					'deferred' => esc_html__( 'Deferred Payment ', 'realhomes-vacation-rentals' ),
				),
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'type' => 'divider',
				'tab'  => 'rvr',
			),
			array(
				'id'      => "rvr_govt_tax",
				'name'    => esc_html__( 'Govt Tax', 'realhomes-vacation-rentals' ),
				'desc'    => esc_html__( 'Example: 16', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'std'     => '',
				'columns' => 4,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_govt_tax_type',
				'name'    => esc_html__( 'Type', 'realhomes-vacation-rentals' ),
				'type'    => 'select',
				'default' => 'percentage',
				'options' => array(
					'percentage' => esc_html__( 'Percentage', 'realhomes-vacation-rentals' ),
					'fixed'      => esc_html__( 'Fixed', 'realhomes-vacation-rentals' ),
				),
				'columns' => 4,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_govt_tax_calculation',
				'name'    => esc_html__( 'Calculation', 'realhomes-vacation-rentals' ),
				'type'    => 'select',
				'default' => 'per_stay',
				'options' => array(
					'per_stay'        => esc_html__( 'Per Stay', 'realhomes-vacation-rentals' ),
					'per_night'       => esc_html__( 'Per Night', 'realhomes-vacation-rentals' ),
					'per_guest'       => esc_html__( 'Per Guest', 'realhomes-vacation-rentals' ),
					'per_night_guest' => esc_html__( 'Per Night Per Guest', 'realhomes-vacation-rentals' ),
				),
				'columns' => 4,
				'tab'     => 'rvr',
			),
			array(
				'id'      => "rvr_service_charges",
				'name'    => esc_html__( 'Service Charges', 'realhomes-vacation-rentals' ),
				'desc'    => esc_html__( 'Example: 3', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'std'     => '',
				'columns' => 4,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_service_charges_type',
				'name'    => esc_html__( 'Type', 'realhomes-vacation-rentals' ),
				'type'    => 'select',
				'default' => 'percentage',
				'options' => array(
					'percentage' => esc_html__( 'Percentage', 'realhomes-vacation-rentals' ),
					'fixed'      => esc_html__( 'Fixed', 'realhomes-vacation-rentals' ),
				),
				'columns' => 4,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_service_charges_calculation',
				'name'    => esc_html__( 'Calculation', 'realhomes-vacation-rentals' ),
				'type'    => 'select',
				'default' => 'per_stay',
				'options' => array(
					'per_stay'        => esc_html__( 'Per Stay', 'realhomes-vacation-rentals' ),
					'per_night'       => esc_html__( 'Per Night', 'realhomes-vacation-rentals' ),
					'per_guest'       => esc_html__( 'Per Guest', 'realhomes-vacation-rentals' ),
					'per_night_guest' => esc_html__( 'Per Night Per Guest', 'realhomes-vacation-rentals' ),
				),
				'columns' => 4,
				'tab'     => 'rvr',
			),
			array(
				'type' => 'divider',
				'tab'  => 'rvr',
			),
			array(
				'id'      => "rvr_guests_capacity",
				'name'    => esc_html__( 'Guests Capacity', 'realhomes-vacation-rentals' ),
				'desc'    => esc_html__( 'Example: 4', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'std'     => '',
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_book_child_as',
				'name'    => esc_html__( 'Book child as an adult?', 'realhomes-vacation-rentals' ),
				'type'    => 'radio',
				'options' => array(
					'adult' => esc_html__( 'Yes', 'realhomes-vacation-rentals' ),
					'child' => esc_html__( 'No', 'realhomes-vacation-rentals' ),
				),
				'std'     => 'child',
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'id'      => "rvr_min_stay",
				'name'    => esc_html__( 'Minimum Number of Nights to Stay', 'realhomes-vacation-rentals' ),
				'desc'    => esc_html__( 'Example: 1', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'std'     => '',
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_guests_capacity_extend',
				'name'    => esc_html__( 'Allow Extra Guests?', 'realhomes-vacation-rentals' ),
				'type'    => 'radio',
				'options' => array(
					'allowed'     => esc_html__( 'Yes', 'realhomes-vacation-rentals' ),
					'not_allowed' => esc_html__( 'No', 'realhomes-vacation-rentals' ),
				),
				'std'     => 'not_allowed',
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'id'      => 'rvr_extra_guest_price',
				'name'    => esc_html__( 'Price Per Extra Guest', 'realhomes-vacation-rentals' ),
				'desc'    => esc_html__( 'Example: 50', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'std'     => '',
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'type' => 'divider',
				'tab'  => 'rvr',
			),
			array(
				'id'      => "rvr_property_owner",
				'name'    => esc_html__( 'Owner', 'realhomes-vacation-rentals' ),
				'desc'    => sprintf( esc_html__( 'You can add new owner by %s clicking here%s.', 'realhomes-vacation-rentals' ), '<a style="color: #ea723d;" target="_blank" href="' . get_home_url() . '/wp-admin/post-new.php?post_type=owner">', '</a>' ),
				'type'    => 'select',
				'options' => $owners_posts,
				'std'     => '',
				'columns' => 6,
				'tab'     => 'rvr',
			),
			array(
				'name'              => esc_html__( 'Bulk Prices', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'To provide discount on longer stays.', 'realhomes-vacation-rentals' ),
				'id'                => 'rvr_bulk_pricing',
				'type'              => 'group',
				'clone'             => true,
				'sort_clone'        => true,
				'tab'               => 'rvr_bulk_seasonal_prices',
				'columns'           => 12,
				'add_button'        => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
				'fields'            => array(
					array(
						'name'    => esc_html__( 'Number of Nights', 'realhomes-vacation-rentals' ),
						'id'      => 'number_of_nights',
						'type'    => 'number',
						'columns' => 6,
					),
					array(
						'name'    => esc_html__( 'Price Per Night', 'realhomes-vacation-rentals' ),
						'id'      => 'price_per_night',
						'type'    => 'number',
						'columns' => 6,
					),
				),
			),
			array(
				'type' => 'divider',
				'tab'  => 'rvr_bulk_seasonal_prices',
			),
			array(
				'name'              => esc_html__( 'Seasonal Prices', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'To charge guests as per season.', 'realhomes-vacation-rentals' ),
				'id'                => 'rvr_seasonal_pricing',
				'type'              => 'group',
				'clone'             => true,
				'sort_clone'        => true,
				'tab'               => 'rvr_bulk_seasonal_prices',
				'columns'           => 12,
				'add_button'        => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
				'fields'            => array(
					array(
						'name'    => esc_html__( 'Start Date', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_price_start_date',
						'type'    => 'date',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'End Date', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_price_end_date',
						'type'    => 'date',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Price', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_price_amount',
						'type'    => 'number',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Minimum Stay', 'realhomes-vacation-rentals' ),
						'desc'    => esc_html__( 'If left empty, the property\'s default minimum stay will apply.', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_min_stay',
						'type'    => 'number',
						'columns' => 3,
					),
				),
			),
			array(
				'name'              => esc_html__( 'Reserve Booking Dates', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'Reserve property booking dates in advance.', 'realhomes-vacation-rentals' ),
				'id'                => 'rvr_custom_reserved_dates',
				'type'              => 'group',
				'clone'             => true,
				'sort_clone'        => true,
				'tab'               => 'rvr_reserve_booking_dates',
				'columns'           => 12,
				'add_button'        => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
				'fields'            => array(
					array(
						'name'    => esc_html__( 'Reservation Note', 'realhomes-vacation-rentals' ),
						'desc'    => esc_html__( 'This is only for reference. It will not be displayed anywhere on the website.', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_reserve_note',
						'type'    => 'text',
						'columns' => 4,
					),
					array(
						'name'       => esc_html__( 'Start Date', 'realhomes-vacation-rentals' ),
						'id'         => 'rvr_reserve_start_date',
						'type'       => 'date',
						'js_options' => array(
							'minDate' => date( 'Y-m-d' )
						),
						'columns'    => 4,
					),
					array(
						'name'       => esc_html__( 'End Date', 'realhomes-vacation-rentals' ),
						'id'         => 'rvr_reserve_end_date',
						'type'       => 'date',
						'js_options' => array(
							'minDate' => date( 'Y-m-d' )
						),
						'columns'    => 4,
					),
				),
			),
			array(
				'name'              => esc_html__( 'Additional Fees', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'To charge additional fees. Such fees can be conditional.', 'realhomes-vacation-rentals' ),
				'id'                => 'rvr_additional_fees',
				'type'              => 'group',
				'clone'             => true,
				'sort_clone'        => true,
				'tab'               => 'rvr_fees_amenities',
				'columns'           => 12,
				'add_button'        => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
				'fields'            => array(
					array(
						'name'    => esc_html__( 'Fee Label', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_fee_label',
						'type'    => 'text',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Fee Amount', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_fee_amount',
						'type'    => 'number',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Fee Type', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_fee_type',
						'type'    => 'select',
						'default' => 'flat',
						'options' => array(
							'fixed'      => esc_html__( 'Fixed', 'realhomes-vacation-rentals' ),
							'percentage' => esc_html__( 'Percentage', 'realhomes-vacation-rentals' ),
						),
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Fee Calculation', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_fee_calculation',
						'default' => '',
						'type'    => 'select',
						'options' => array(
							'per_stay'        => esc_html__( 'Per Stay', 'realhomes-vacation-rentals' ),
							'per_night'       => esc_html__( 'Per Night', 'realhomes-vacation-rentals' ),
							'per_guest'       => esc_html__( 'Per Guest', 'realhomes-vacation-rentals' ),
							'per_night_guest' => esc_html__( 'Per Night Per Guest', 'realhomes-vacation-rentals' ),
						),
						'columns' => 3,
					),
				),
			),
			array(
				'type' => 'divider',
				'tab'  => 'rvr_fees_amenities',
			),
			array(
				'name'              => esc_html__( 'Additional Amenities', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'These amenities will be optional to choose from while booking a property. The chosen amenities price will be added based on the condition you set.', 'realhomes-vacation-rentals' ),
				'id'                => 'rvr_additional_amenities',
				'type'              => 'group',
				'clone'             => true,
				'sort_clone'        => true,
				'tab'               => 'rvr_fees_amenities',
				'columns'           => 12,
				'add_button'        => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
				'fields'            => array(
					array(
						'name'    => esc_html__( 'Amenity Label', 'realhomes-vacation-rentals' ),
						'id'      => 'amenity_label',
						'type'    => 'text',
						'columns' => 4,
					),
					array(
						'name'    => esc_html__( 'Amenity Price', 'realhomes-vacation-rentals' ),
						'id'      => 'amenity_price',
						'type'    => 'number',
						'columns' => 4,
					),
					array(
						'name'    => esc_html__( 'Price Calculation', 'realhomes-vacation-rentals' ),
						'id'      => 'price_calculation',
						'default' => '',
						'type'    => 'select',
						'options' => array(
							'per_stay'        => esc_html__( 'Per Stay', 'realhomes-vacation-rentals' ),
							'per_night'       => esc_html__( 'Per Night', 'realhomes-vacation-rentals' ),
							'per_guest'       => esc_html__( 'Per Guest', 'realhomes-vacation-rentals' ),
							'per_night_guest' => esc_html__( 'Per Night Per Guest', 'realhomes-vacation-rentals' ),
						),
						'columns' => 4,
					),
				),
			),
			array(
				'name'              => esc_html__( 'Guests Accommodation', 'realhomes-vacation-rentals' ),
				'label_description' => esc_html__( 'Provide accommodation details for the guests.', 'realhomes-vacation-rentals' ),
				'id'                => 'rvr_accommodation',
				'type'              => 'group',
				'clone'             => true,
				'sort_clone'        => true,
				'tab'               => 'rvr_guests_accommodation',
				'columns'           => 12,
				'add_button'        => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
				'fields'            => array(
					array(
						'name'    => esc_html__( 'Room Type', 'realhomes-vacation-rentals' ),
						'desc'    => esc_html__( 'Example: Master Room', 'realhomes-vacation-rentals' ),
						'id'      => 'room_type',
						'type'    => 'text',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Bed Type', 'realhomes-vacation-rentals' ),
						'desc'    => esc_html__( 'Example: King Bed', 'realhomes-vacation-rentals' ),
						'id'      => 'bed_type',
						'type'    => 'text',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Number of Beds', 'realhomes-vacation-rentals' ),
						'id'      => 'beds_number',
						'type'    => 'text',
						'columns' => 3,
					),
					array(
						'name'    => esc_html__( 'Number of Guests', 'realhomes-vacation-rentals' ),
						'id'      => 'guests_number',
						'type'    => 'text',
						'columns' => 3,
					),
				),
			),
			array(
				'name'       => esc_html__( 'Outdoor Features', 'realhomes-vacation-rentals' ),
				'id'         => "rvr_outdoor_features",
				'std'        => '',
				'type'       => 'text',
				'size'       => '100',
				'tab'        => 'rvr_outdoor_surroundings',
				'clone'      => true,
				'sort_clone' => true,
				'add_button' => esc_html__( '+', 'realhomes-vacation-rentals' ),
				'columns'    => 12,
			),
			array(
				'type' => 'divider',
				'tab'  => 'rvr_outdoor_surroundings',
			),
			array(
				'name'       => esc_html__( 'Property Surroundings', 'realhomes-vacation-rentals' ),
				'id'         => "rvr_surroundings",
				'type'       => 'group',
				'clone'      => true,
				'sort_clone' => true,
				'tab'        => 'rvr_outdoor_surroundings',
				'columns'    => 12,
				'add_button' => esc_html__( '+', 'realhomes-vacation-rentals' ),
				'fields'     => array(
					array(
						'name'    => esc_html__( 'Point of Interest', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_surrounding_point',
						'type'    => 'text',
						'columns' => 6,
					),
					array(
						'name'    => esc_html__( 'Distance or How to approach', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_surrounding_point_distance',
						'type'    => 'text',
						'columns' => 6,
					),
				),
			),
			array(
				'name'       => ! empty( $rvr_settings['rvr_optional_services_inc_label'] ) ? $rvr_settings['rvr_optional_services_inc_label'] : esc_html__( 'What is included', 'realhomes-vacation-rentals' ),
				'id'         => "rvr_included",
				'std'        => '',
				'type'       => 'text',
				'size'       => '100',
				'tab'        => 'rvr_included_excluded',
				'clone'      => true,
				'sort_clone' => true,
				'add_button' => esc_html__( '+', 'realhomes-vacation-rentals' ),
				'columns'    => 6,
			),
			array(
				'name'       => ! empty( $rvr_settings['rvr_optional_services_inc_label'] ) ? $rvr_settings['rvr_optional_services_not_inc_label'] : esc_html__( 'What is not included', 'realhomes-vacation-rentals' ),
				'id'         => "rvr_not_included",
				'std'        => '',
				'type'       => 'text',
				'size'       => '100',
				'tab'        => 'rvr_included_excluded',
				'clone'      => true,
				'sort_clone' => true,
				'add_button' => esc_html__( '+', 'realhomes-vacation-rentals' ),
				'columns'    => 6,
			),
			array(
				'name'       => esc_html__( 'Property Policies or Rules', 'realhomes-vacation-rentals' ),
				'desc'       => sprintf( esc_html__( '* Default check icon %s will appear if "Font Awesome Icon" field is empty. You can see the list of Font Awesome Free Icons by %s clicking here%s. %s Add "rvr-slash" class with Font Awesome classes to display it as ban %s %s For example %s rvr-slash fas fa-paw %s for no pets ', 'realhomes-vacation-rentals' ),
					'<i style="color: #ea723d;" class="fas fa-check"></i>',
					'<a style="color: #ea723d;" target="_blank" href="https://fontawesome.com/icons?d=gallery&m=free">',
					'</a>',
					'<br>',
					'<i style="color: #ea723d;" class="fas fa-ban"></i>', '<br>', '<strong>', '</strong>' ),
				'id'         => "rvr_policies",
				'type'       => 'group',
				'size'       => '100',
				'tab'        => 'rvr_property_policies',
				'clone'      => true,
				'sort_clone' => true,
				'columns'    => 12,
				'add_button' => esc_html__( '+', 'realhomes-vacation-rentals' ),
				'fields'     => array(
					array(
						'name'    => esc_html__( 'Policy Text', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_policy_detail',
						'type'    => 'text',
						'columns' => 6,
					),
					array(
						'name'    => esc_html__( 'Font Awesome Icon (i.e far fa-star)', 'realhomes-vacation-rentals' ),
						'id'      => 'rvr_policy_icon',
						'type'    => 'text',
						'columns' => 6,
					),

				),
			),

		);

		// Display property price fields in this "Vacation Rentals" section only if RVR is enabled.
		if ( rvr_is_enabled() ) {
			$property_price_fields = array(
				array(
					'id'      => "REAL_HOMES_property_price",
					'name'    => esc_html__( 'Rent Price ( Only digits )', 'easy-real-estate' ),
					'desc'    => esc_html__( 'Example: 1200', 'easy-real-estate' ),
					'type'    => 'text',
					'std'     => '',
					'columns' => 6,
					'tab'     => 'rvr',
				),
				array(
					'id'      => "REAL_HOMES_property_old_price",
					'name'    => esc_html__( 'Old Price If Any ( Only digits )', 'easy-real-estate' ),
					'desc'    => esc_html__( 'Example: 1500', 'easy-real-estate' ),
					'type'    => 'text',
					'std'     => '',
					'columns' => 6,
					'tab'     => 'rvr',
				),
				array(
					'id'      => 'REAL_HOMES_property_price_prefix',
					'name'    => esc_html__( 'Price Prefix', 'easy-real-estate' ),
					'desc'    => esc_html__( 'Example: From', 'easy-real-estate' ),
					'type'    => 'text',
					'std'     => '',
					'columns' => 6,
					'tab'     => 'rvr',
				),
				array(
					'id'      => "REAL_HOMES_property_price_postfix",
					'name'    => esc_html__( 'Price Postfix', 'easy-real-estate' ),
					'desc'    => esc_html__( 'Example: Monthly or Per Night', 'easy-real-estate' ),
					'type'    => 'text',
					'std'     => '',
					'columns' => 6,
					'tab'     => 'rvr',
				),
				array(
					'type'    => 'divider',
					'columns' => 12,
					'id'      => 'price_divider',
					'tab'     => 'rvr',
				),
			);
		} else {
			$property_price_fields = array();
		}

		// iCalendar import & export fields.
		$icalendar_export_url = rvr_get_property_icalendar_export_url( $property_id );

		if ( ! empty( $icalendar_export_url ) ) {
			$icalendar_file_url = rvr_get_property_icalendar_ics_file_url( $property_id );

			$icalendar_data = '<br><h5 style="font-size: 13px;">' . esc_html__( 'iCalendar Export', 'realhomes-vacation-rentals' ) . '</h5>';
			$icalendar_data .= '<strong>' . esc_html__( 'Feed URL', 'realhomes-vacation-rentals' ) . ':</strong> <code>' . esc_url( $icalendar_export_url ) . '</code>';
			$icalendar_data .= '<br><br>';
			$icalendar_data .= '<strong>' . esc_html__( 'File URL', 'realhomes-vacation-rentals' ) . ':</strong> <code>' . esc_url( $icalendar_file_url ) . '</code>';

			$icalendar_fields = array(
				array(
					'id'      => 'rvr_icalendar_data',
					'desc'    => $icalendar_data,
					'type'    => 'heading',
					'tab'     => 'rvr_icalendar_sync',
					'columns' => 12,
				),
				array(
					'name'       => esc_html__( 'iCalendar Import', 'realhomes-vacation-rentals' ),
					'id'         => 'rvr_import_icalendar_feed_list',
					'type'       => 'group',
					'clone'      => true,
					'sort_clone' => true,
					'tab'        => 'rvr_icalendar_sync',
					'columns'    => 12,
					'add_button' => esc_html__( 'Add more', 'realhomes-vacation-rentals' ),
					'fields'     => array(
						array(
							'name'    => esc_html__( 'Feed Name', 'realhomes-vacation-rentals' ),
							'id'      => 'feed_name',
							'type'    => 'text',
							'columns' => 6,
						),
						array(
							'name'    => esc_html__( 'Feed URL', 'realhomes-vacation-rentals' ),
							'id'      => 'feed_url',
							'type'    => 'text',
							'columns' => 6,
						),
					),
				),
			);
		} else {
			$icalendar_data   = '<p>' . sprintf( esc_html__( 'Before syncing booking calendars you need to %1$ssetup the iCalendar Feed page%2$s.', 'realhomes-vacation-rentals' ), '<a style="color: #ea723d;" target="_blank" href="https://realhomes.io/documentation/add-property/#icalendar-synchronization">', '</a>' ) . '</p>';
			$icalendar_fields = array(
				array(
					'name'    => esc_html__( 'iCalendar Sync', 'realhomes-vacation-rentals' ),
					'type'    => 'heading',
					'tab'     => 'rvr_icalendar_sync',
					'columns' => 12,
					'desc'    => $icalendar_data,
				),
			);
		}

		$rvr_metabox_fields = array_merge( $property_price_fields, $rvr_metabox_fields );
		$rvr_metabox_fields = array_merge( $rvr_metabox_fields, $icalendar_fields );

		return array_merge( $property_metabox_fields, $rvr_metabox_fields );
	}

	add_filter( 'ere_property_metabox_fields', 'rvr_add_metabox_fields', 11 );
}

/**
 * Added property availability table meta to the REST API.
 */
$property_reservations_in_rest = get_option( 'inspiry_property_reservations_in_rest', 'hide' );
if ( 'show' === $property_reservations_in_rest ) {
	add_action(
		'rest_api_init',
		function () {
			register_rest_field(
				'property',
				'rvr_property_bookings',
				array(
					'get_callback'    => function ( $post_arr ) {
						return get_post_meta( $post_arr['id'], 'rvr_property_availability_table', true );
					},
					'update_callback' => null,
					'schema'          => null,
				)
			);
		}
	);
}
