<?php
/**
 * Agency Custom Post Type
 */

if ( ! function_exists( 'ere_register_agency_post_type' ) ) {
	/**
	 * Register Custom Post Type : Agency
	 */
	function ere_register_agency_post_type() {

		$labels = array(
			'name'                  => esc_html_x( 'Agencies', 'Post Type General Name', 'easy-real-estate' ),
			'singular_name'         => esc_html_x( 'Agency', 'Post Type Singular Name', 'easy-real-estate' ),
			'menu_name'             => esc_html__( 'Agencies', 'easy-real-estate' ),
			'name_admin_bar'        => esc_html__( 'Agency', 'easy-real-estate' ),
			'archives'              => esc_html__( 'Agency Archives', 'easy-real-estate' ),
			'attributes'            => esc_html__( 'Agency Attributes', 'easy-real-estate' ),
			'parent_item_colon'     => esc_html__( 'Parent Agency:', 'easy-real-estate' ),
			'all_items'             => esc_html__( 'All Agencies', 'easy-real-estate' ),
			'add_new_item'          => esc_html__( 'Add New Agency', 'easy-real-estate' ),
			'add_new'               => esc_html__( 'Add New', 'easy-real-estate' ),
			'new_item'              => esc_html__( 'New Agency', 'easy-real-estate' ),
			'edit_item'             => esc_html__( 'Edit Agency', 'easy-real-estate' ),
			'update_item'           => esc_html__( 'Update Agency', 'easy-real-estate' ),
			'view_item'             => esc_html__( 'View Agency', 'easy-real-estate' ),
			'view_items'            => esc_html__( 'View Agencies', 'easy-real-estate' ),
			'search_items'          => esc_html__( 'Search Agency', 'easy-real-estate' ),
			'not_found'             => esc_html__( 'Not found', 'easy-real-estate' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'easy-real-estate' ),
			'featured_image'        => esc_html__( 'Agency Logo Image', 'easy-real-estate' ),
			'set_featured_image'    => esc_html__( 'Set agency logo image', 'easy-real-estate' ),
			'remove_featured_image' => esc_html__( 'Remove agency logo image', 'easy-real-estate' ),
			'use_featured_image'    => esc_html__( 'Use as agency logo image', 'easy-real-estate' ),
			'insert_into_item'      => esc_html__( 'Insert into agency', 'easy-real-estate' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this agency', 'easy-real-estate' ),
			'items_list'            => esc_html__( 'Agencies list', 'easy-real-estate' ),
			'items_list_navigation' => esc_html__( 'Agencies list navigation', 'easy-real-estate' ),
			'filter_items_list'     => esc_html__( 'Filter agencies list', 'easy-real-estate' ),
		);
		$args   = array(
			'label'               => esc_html__( 'Agency', 'easy-real-estate' ),
			'description'         => esc_html__( 'An agency is a company of realtors.', 'easy-real-estate' ),
			'labels'              => $labels,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-groups',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => ( defined( 'INSPIRY_DESIGN_VARIATION' ) && 'ultra' === INSPIRY_DESIGN_VARIATION ),
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'comments' ),
			'rewrite'             => array(
				'slug' => apply_filters( 'inspiry_agency_slug', esc_html__( 'agency', 'easy-real-estate' ) ),
			),
			'show_in_rest'        => true,
			'rest_base'           => apply_filters( 'inspiry_agency_rest_base', esc_html__( 'agencies', 'easy-real-estate' ) )
		);

		// Filter the arguments to register agency post type.
		$args = apply_filters( 'inspiry_agency_post_type_args', $args );
		register_post_type( 'agency', $args );

	}

	add_action( 'init', 'ere_register_agency_post_type', 0 );

}


if ( ! function_exists( 'ere_set_agency_slug' ) ) {
	/**
	 * This function set agency's url slug by hooking itself with related filter
	 *
	 * @param string $existing_slug - Existing slug.
	 *
	 * @return string
	 */
	function ere_set_agency_slug( $existing_slug ) {
		$new_slug = get_option( 'inspiry_agency_slug' );
		if ( ! empty( $new_slug ) ) {
			return $new_slug;
		}

		return $existing_slug;
	}

	add_filter( 'inspiry_agency_slug', 'ere_set_agency_slug' );
}


if ( ! function_exists( 'ere_register_agency_location_taxonomy' ) ) {
	/**
	 * Register Agency Location Taxonomy
	 *
	 * @since 2.0.0
	 * @return void
	 */
	function ere_register_agency_location_taxonomy() {
		if ( taxonomy_exists( 'agency-location' ) ) {
			return;
		}

		$city_labels = array(
			'name'                       => esc_html__( 'Agency Locations', 'easy-real-estate' ),
			'singular_name'              => esc_html__( 'Agency Location', 'easy-real-estate' ),
			'search_items'               => esc_html__( 'Search Agency Locations', 'easy-real-estate' ),
			'popular_items'              => esc_html__( 'Popular Agency Locations', 'easy-real-estate' ),
			'all_items'                  => esc_html__( 'All Agency Locations', 'easy-real-estate' ),
			'parent_item'                => esc_html__( 'Parent Agency Location', 'easy-real-estate' ),
			'parent_item_colon'          => esc_html__( 'Parent Agency Location:', 'easy-real-estate' ),
			'edit_item'                  => esc_html__( 'Edit Agency Location', 'easy-real-estate' ),
			'update_item'                => esc_html__( 'Update Agency Location', 'easy-real-estate' ),
			'add_new_item'               => esc_html__( 'Add New Agency Location', 'easy-real-estate' ),
			'new_item_name'              => esc_html__( 'New Agency Location Name', 'easy-real-estate' ),
			'separate_items_with_commas' => esc_html__( 'Separate Agency Locations with commas', 'easy-real-estate' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Agency Locations', 'easy-real-estate' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Agency Locations', 'easy-real-estate' ),
			'menu_name'                  => esc_html__( 'Agency Locations', 'easy-real-estate' ),
		);

		register_taxonomy(
			'agency-location',
			array( 'agency' ),
			array(
				'hierarchical' => true,
				'labels'       => apply_filters( 'ere_agency_location_labels', $city_labels ),
				'show_ui'      => true,
				'show_in_menu' => 'easy-real-estate',
				'query_var'    => true,
				'rewrite'      => array(
					'slug' => apply_filters( 'ere_agency_location_slug', esc_html__( 'agency-location', 'easy-real-estate' ) ),
				),
				'show_in_rest' => true,
				'rest_base'    => apply_filters( 'ere_agency_location_rest_base', esc_html__( 'agency-locations', 'easy-real-estate' ) )
			)
		);
	}

	add_action( 'init', 'ere_register_agency_location_taxonomy', 0 );
}

if ( ! function_exists( 'ere_set_agency_location_slug' ) ) {
	/**
	 * This function set agency location's URL slug by hooking itself with related filter
	 *
	 * @since 2.0.0
	 *
	 * @param string $existing_slug - Existing agency location slug.
	 *
	 * @return string
	 */
	function ere_set_agency_location_slug( $existing_slug ) {
		$new_slug = get_option( 'ere_agency_location_slug' );
		if ( ! empty( $new_slug ) ) {
			return $new_slug;
		}

		return $existing_slug;
	}

	add_filter( 'ere_agency_location_slug', 'ere_set_agency_location_slug' );
	add_filter( 'ere_agency_location_rest_base', 'ere_set_agency_location_slug' );
}

if ( ! function_exists( 'ere_agency_edit_columns' ) ) {
	/**
	 * Custom columns for agencies.
	 *
	 * @param array $columns - Columns array.
	 *
	 * @return array
	 */
	function ere_agency_edit_columns( $columns ) {

		$columns = array(
			'cb'               => '<input type="checkbox" />',
			'title'            => esc_html__( 'Agency', 'easy-real-estate' ),
			'agency-thumbnail' => esc_html__( 'Thumbnail', 'easy-real-estate' ),
			'location'         => esc_html__( 'Location', 'easy-real-estate' ),
			'total_properties' => esc_html__( 'Total Properties', 'easy-real-estate' ),
			'published'        => esc_html__( 'Published Properties', 'easy-real-estate' ),
			'others'           => esc_html__( 'Other Properties', 'easy-real-estate' ),
			'rating'           => esc_html__( 'Average Rating', 'easy-real-estate' ),
			'date'             => esc_html__( 'Created', 'easy-real-estate' ),
		);

		/**
		 * WPML Support
		 */
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;
			$wpml_columns = new WPML_Custom_Columns( $sitepress );
			$columns      = $wpml_columns->add_posts_management_column( $columns );
		}

		/**
		 * Reverse the array for RTL
		 */
		if ( is_rtl() ) {
			$columns = array_reverse( $columns );
		}

		return $columns;
	}

	add_filter( 'manage_edit-agency_columns', 'ere_agency_edit_columns' );
}


if ( ! function_exists( 'ere_agency_custom_columns' ) ) {

	/**
	 * Custom column values for agency post type.
	 *
	 * @param string $column    - Name of the column.
	 * @param int    $agency_id - ID of the current agency.
	 *
	 */
	function ere_agency_custom_columns( $column, $agency_id ) {

		// Switch cases against column names.
		switch ( $column ) {
			case 'agency-thumbnail':
				if ( has_post_thumbnail( $agency_id ) ) {
					?>
                    <a href="<?php the_permalink(); ?>" target="_blank">
						<?php the_post_thumbnail( array( 130, 130 ) ); ?>
                    </a>
					<?php
				} else {
					esc_html_e( 'No Thumbnail', 'easy-real-estate' );
				}
				break;
			// Agency Location
			case 'location':
				$agency_locations = ere_admin_taxonomy_terms( $agency_id, 'agency-location', 'agency' );
				echo ! $agency_locations ? esc_html__( 'None', 'easy-real-estate' ) : $agency_locations;
				break;
			// Total properties.
			case 'total_properties':
				$listed_properties = ere_get_agency_properties_count( $agency_id );
				echo ( ! empty( $listed_properties ) ) ? esc_html( $listed_properties ) : 0;
				break;
			// Total published properties.
			case 'published':
				$published_properties = ere_get_agency_properties_count( $agency_id, 'publish' );
				echo ( ! empty( $published_properties ) ) ? esc_html( $published_properties ) : 0;
				break;
			// Other properties.
			case 'others':
				$property_status   = array( 'pending', 'draft', 'private', 'future' );
				$others_properties = ere_get_agency_properties_count( $agency_id, $property_status );
				echo ( ! empty( $others_properties ) ) ? esc_html( $others_properties ) : 0;
				break;
			// Average Rating
			case 'rating':
				$average_rating = get_post_meta( $agency_id, 'realhomes_post_average_rating', true );
				echo ( ! empty( $average_rating ) ) ? esc_html( $average_rating ) : 0;
				break;
			default:
				break;
		}
	}

	add_action( 'manage_agency_posts_custom_column', 'ere_agency_custom_columns', 10, 2 );
}

if ( ! function_exists( 'ere_agency_filter_fields_admin' ) ) {
	/**
	 * Add custom filter fields for agency on admin
	 *
	 * @since 2.2.2
	 */
	function ere_agency_filter_fields_admin() {

		global $post_type;
		if ( $post_type == 'agency' ) {

			// Agency Location Dropdown Option
			$agency_location_args = array(
				'show_option_all' => esc_html__( 'All Agency Locations', 'easy-real-estate' ),
				'orderby'         => 'NAME',
				'order'           => 'ASC',
				'name'            => 'agency_location',
				'taxonomy'        => 'agency-location'
			);
			if ( isset( $_GET['agency_location'] ) ) {
				$agency_location_args['selected'] = sanitize_text_field( $_GET['agency_location'] );
			}
			wp_dropdown_categories( $agency_location_args );

			// Ratings Dropdown Option
			$selected_rating = 0;
			if ( isset( $_GET['agency_rating'] ) ) {
				$selected_rating = sanitize_text_field( $_GET['agency_rating'] );
			}
			?>
            <select name="agency_rating" id="agency-rating-drowdown">
                <option value="0" <?php selected( 0, $selected_rating ); ?>><?php esc_html_e( 'All Ratings', 'easy-real-estate' ); ?></option>
                <option value="1" <?php selected( 1, $selected_rating ); ?>><?php esc_html_e( '1 Star', 'easy-real-estate' ); ?></option>
                <option value="2" <?php selected( 2, $selected_rating ); ?>><?php esc_html_e( '2 Stars', 'easy-real-estate' ); ?></option>
                <option value="3" <?php selected( 3, $selected_rating ); ?>><?php esc_html_e( '3 Stars', 'easy-real-estate' ); ?></option>
                <option value="4" <?php selected( 4, $selected_rating ); ?>><?php esc_html_e( '4 Stars', 'easy-real-estate' ); ?></option>
                <option value="5" <?php selected( 5, $selected_rating ); ?>><?php esc_html_e( '5 Stars', 'easy-real-estate' ); ?></option>
            </select>
			<?php
		}
	}

	add_action( 'restrict_manage_posts', 'ere_agency_filter_fields_admin' );
}

if ( ! function_exists( 'ere_agency_filter_admin' ) ) {
	/**
	 * Restrict the agency by the chosen filters
	 *
	 * @since 2.2.2
	 *
	 * @param $query
	 */
	function ere_agency_filter_admin( $query ) {

		global $post_type, $pagenow;

		// If we are currently on the edit screen of the agency post-type listings
		if ( $pagenow == 'edit.php' && $post_type == 'agency' ) {

			$tax_query  = array();
			$meta_query = array();

			// Agent Location Filter
			if ( isset( $_GET['agency_location'] ) && ! empty( $_GET['agency_location'] ) ) {

				// Get the desired property location
				$agent_location = sanitize_text_field( $_GET['agency_location'] );

				// If the agency location is not 0 (which means all)
				if ( $agent_location != 0 ) {
					$tax_query[] = array(
						'taxonomy' => 'agency-location',
						'field'    => 'ID',
						'terms'    => array( $agent_location )
					);

				}
			}

			// Agency Ratings Filter
			if ( isset( $_GET['agency_rating'] ) && ! empty( $_GET['agency_rating'] ) ) {

				$meta_query[] = array(
					'key'     => 'realhomes_post_average_rating',
					'value'   => sanitize_text_field( $_GET['agency_rating'] ),
					'compare' => '=',
				);

			}

			if ( ! empty( $meta_query ) ) {
				$query->query_vars['meta_query'] = $meta_query;
			}

			if ( ! empty( $tax_query ) ) {
				$query->query_vars['tax_query'] = $tax_query;
			}
		}
	}

	add_action( 'pre_get_posts', 'ere_agency_filter_admin' );
}