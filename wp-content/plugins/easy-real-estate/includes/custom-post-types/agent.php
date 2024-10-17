<?php
/**
 * Agent Custom Post Type
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'ere_register_agent_post_type' ) ) {

	function ere_register_agent_post_type() {

		if ( post_type_exists( 'agent' ) ) {
			return;
		}

		$agent_post_type_labels = array(
			'name'               => esc_html__( 'Agents', 'easy-real-estate' ),
			'singular_name'      => esc_html__( 'Agent', 'easy-real-estate' ),
			'add_new'            => esc_html__( 'Add New', 'easy-real-estate' ),
			'add_new_item'       => esc_html__( 'Add New Agent', 'easy-real-estate' ),
			'edit_item'          => esc_html__( 'Edit Agent', 'easy-real-estate' ),
			'new_item'           => esc_html__( 'New Agent', 'easy-real-estate' ),
			'view_item'          => esc_html__( 'View Agent', 'easy-real-estate' ),
			'search_items'       => esc_html__( 'Search Agent', 'easy-real-estate' ),
			'not_found'          => esc_html__( 'No Agent found', 'easy-real-estate' ),
			'not_found_in_trash' => esc_html__( 'No Agent found in Trash', 'easy-real-estate' ),
			'parent_item_colon'  => '',
		);

		$agent_post_type_args = array(
			'labels'              => apply_filters( 'inspiry_agent_post_type_labels', $agent_post_type_labels ),
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => true,
			'query_var'           => true,
			'has_archive'         => ( defined( 'INSPIRY_DESIGN_VARIATION' ) && 'ultra' === INSPIRY_DESIGN_VARIATION ),
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_icon'           => 'dashicons-businessman',
			'menu_position'       => 5,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'comments' ),
			'rewrite'             => array(
				'slug' => apply_filters( 'inspiry_agent_slug', esc_html__( 'agent', 'easy-real-estate' ) ),
			),
			'show_in_rest'        => true,
			'rest_base'           => apply_filters( 'inspiry_agent_rest_base', esc_html__( 'agents', 'easy-real-estate' ) )
		);

		// Filter the arguments to register agent post type.
		$agent_post_type_args = apply_filters( 'inspiry_agent_post_type_args', $agent_post_type_args );
		register_post_type( 'agent', $agent_post_type_args );
	}

	add_action( 'init', 'ere_register_agent_post_type' );
}


if ( ! function_exists( 'ere_set_agent_slug' ) ) :
	/**
	 * This function set agent's url slug by hooking itself with related filter
	 *
	 * @param string $existing_slug - Existing slug.
	 *
	 * @return string
	 */
	function ere_set_agent_slug( $existing_slug ) {
		$new_slug = get_option( 'inspiry_agent_slug' );
		if ( ! empty( $new_slug ) ) {
			return $new_slug;
		}

		return $existing_slug;
	}

	add_filter( 'inspiry_agent_slug', 'ere_set_agent_slug' );
endif;

if ( ! function_exists( 'ere_register_agent_location_taxonomy' ) ) {
	/**
	 * Register Agent Location Taxonomy
	 *
	 * @since 2.0.0
	 * @return void
	 */
	function ere_register_agent_location_taxonomy() {
		if ( taxonomy_exists( 'agent-location' ) ) {
			return;
		}

		$city_labels = array(
			'name'                       => esc_html__( 'Agent Locations', 'easy-real-estate' ),
			'singular_name'              => esc_html__( 'Agent Location', 'easy-real-estate' ),
			'search_items'               => esc_html__( 'Search Agent Locations', 'easy-real-estate' ),
			'popular_items'              => esc_html__( 'Popular Agent Locations', 'easy-real-estate' ),
			'all_items'                  => esc_html__( 'All Agent Locations', 'easy-real-estate' ),
			'parent_item'                => esc_html__( 'Parent Agent Location', 'easy-real-estate' ),
			'parent_item_colon'          => esc_html__( 'Parent Agent Location:', 'easy-real-estate' ),
			'edit_item'                  => esc_html__( 'Edit Agent Location', 'easy-real-estate' ),
			'update_item'                => esc_html__( 'Update Agent Location', 'easy-real-estate' ),
			'add_new_item'               => esc_html__( 'Add New Agent Location', 'easy-real-estate' ),
			'new_item_name'              => esc_html__( 'New Agent Location Name', 'easy-real-estate' ),
			'separate_items_with_commas' => esc_html__( 'Separate Agent Locations with commas', 'easy-real-estate' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Agent Locations', 'easy-real-estate' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Agent Locations', 'easy-real-estate' ),
			'menu_name'                  => esc_html__( 'Agent Locations', 'easy-real-estate' ),
		);

		register_taxonomy(
			'agent-location',
			array( 'agent' ),
			array(
				'hierarchical' => true,
				'labels'       => apply_filters( 'ere_agent_location_labels', $city_labels ),
				'show_ui'      => true,
				'show_in_menu' => 'easy-real-estate',
				'query_var'    => true,
				'rewrite'      => array(
					'slug' => apply_filters( 'ere_agent_location_slug', esc_html__( 'agent-location', 'easy-real-estate' ) ),
				),
				'show_in_rest' => true,
				'rest_base'    => apply_filters( 'ere_agent_location_rest_base', esc_html__( 'agent-locations', 'easy-real-estate' ) )
			)
		);
	}

	add_action( 'init', 'ere_register_agent_location_taxonomy', 0 );
}

if ( ! function_exists( 'ere_set_agent_location_slug' ) ) {
	/**
	 * This function set agent location's URL slug by hooking itself with related filter
	 *
	 * @since 2.0.0
	 *
	 * @param string $existing_slug - Existing agent location slug.
	 *
	 * @return string
	 */
	function ere_set_agent_location_slug( $existing_slug ) {
		$new_slug = get_option( 'ere_agent_location_slug' );
		if ( ! empty( $new_slug ) ) {
			return $new_slug;
		}

		return $existing_slug;
	}

	add_filter( 'ere_agent_location_slug', 'ere_set_agent_location_slug' );
	add_filter( 'ere_agent_location_rest_base', 'ere_set_agent_location_slug' );
}

if ( ! function_exists( 'ere_agent_edit_columns' ) ) {
	/**
	 * Custom columns for agents.
	 *
	 * @param array $columns - Columns array.
	 *
	 * @return array
	 */
	function ere_agent_edit_columns( $columns ) {

		$columns = array(
			'cb'               => '<input type="checkbox" />',
			'title'            => esc_html__( 'Agent', 'easy-real-estate' ),
			'agent-thumbnail'  => esc_html__( 'Thumbnail', 'easy-real-estate' ),
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

	add_filter( 'manage_edit-agent_columns', 'ere_agent_edit_columns' );
}


if ( ! function_exists( 'ere_agent_custom_columns' ) ) {

	/**
	 * Custom column values for agent post type.
	 *
	 * @param string $column - Name of the column.
	 * @param int $agent_id  - ID of the current agent.
	 *
	 * @since 3.3.0
	 */
	function ere_agent_custom_columns( $column, $agent_id ) {

		// Switch cases against column names.
		switch ( $column ) {
			case 'agent-thumbnail':
				if ( has_post_thumbnail( $agent_id ) ) {
					?>
					<a href="<?php the_permalink(); ?>" target="_blank">
						<?php the_post_thumbnail( array( 130, 130 ) ); ?>
					</a>
					<?php
				} else {
					esc_html_e( 'No Thumbnail', 'easy-real-estate' );
				}
				break;
            // Agent Location
			case 'location':
                $agent_locations = ere_admin_taxonomy_terms( $agent_id, 'agent-location', 'agent' );
				echo ! $agent_locations ? esc_html__( 'None', 'easy-real-estate' ) : $agent_locations;
				break;
			// Total properties.
			case 'total_properties':
				$listed_properties = ere_get_agent_properties_count( $agent_id );
				echo ( ! empty( $listed_properties ) ) ? esc_html( $listed_properties ) : 0;
				break;
			// Total properties.
			case 'published':
				$published_properties = ere_get_agent_properties_count( $agent_id, 'publish' );
				echo ( ! empty( $published_properties ) ) ? esc_html( $published_properties ) : 0;
				break;
			// Published properties.
			case 'others':
				$property_status   = array( 'pending', 'draft', 'private', 'future' );
				$others_properties = ere_get_agent_properties_count( $agent_id, $property_status );
				echo ( ! empty( $others_properties ) ) ? esc_html( $others_properties ) : 0;
				break;
			// Average Rating
			case 'rating':
                $average_rating = get_post_meta( $agent_id, 'realhomes_post_average_rating', true );
				echo ( ! empty( $average_rating ) ) ? esc_html( $average_rating ) : 0;
				break;
			// Other properties.
			default:
				break;
		}
	}

	add_action( 'manage_agent_posts_custom_column', 'ere_agent_custom_columns', 10, 2 );
}

if ( ! function_exists( 'ere_agents_filter_fields_admin' ) ) {
	/**
	 * Add custom filter fields for agents on admin
	 *
	 * @since 2.2.2
	 */
	function ere_agents_filter_fields_admin() {

		global $post_type;
		if ( $post_type == 'agent' ) {

			// Agent Location Dropdown Option
			$agent_location_args = array(
				'show_option_all' => esc_html__( 'All Agent Locations', 'easy-real-estate' ),
				'orderby'         => 'NAME',
				'order'           => 'ASC',
				'name'            => 'agent_location',
				'taxonomy'        => 'agent-location'
			);
			if ( isset( $_GET['agent_location'] ) ) {
				$agent_location_args['selected'] = sanitize_text_field( $_GET['agent_location'] );
			}
			wp_dropdown_categories( $agent_location_args );

			// Ratings Dropdown Option
			$selected_rating = 0;
			if ( isset( $_GET['agent_rating'] ) ) {
				$selected_rating = sanitize_text_field( $_GET['agent_rating'] );
			}
			?>
            <select name="agent_rating" id="agent-rating-drowdown">
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

	add_action( 'restrict_manage_posts', 'ere_agents_filter_fields_admin' );
}

if ( ! function_exists( 'ere_agent_filter_admin' ) ) {
	/**
	 * Restrict the agents by the chosen filters
	 *
	 * @since 2.2.2
	 *
	 * @param $query
	 */
	function ere_agent_filter_admin( $query ) {

		global $post_type, $pagenow;

		// If we are currently on the edit screen of the agent post-type listings
		if ( $pagenow == 'edit.php' && $post_type == 'agent' ) {

			$tax_query  = array();
			$meta_query = array();

			// Agent Location Filter
			if ( isset( $_GET['agent_location'] ) && ! empty( $_GET['agent_location'] ) ) {

				// Get the desired property location
				$agent_location = sanitize_text_field( $_GET['agent_location'] );

				// If the agent location is not 0 (which means all)
				if ( $agent_location != 0 ) {
					$tax_query[] = array(
						'taxonomy' => 'agent-location',
						'field'    => 'ID',
						'terms'    => array( $agent_location )
					);
				}
			}

			// Agent Ratings Filter
			if ( isset( $_GET['agent_rating'] ) && ! empty( $_GET['agent_rating'] ) ) {

				$meta_query[] = array(
					'key'     => 'realhomes_post_average_rating',
					'value'   => sanitize_text_field( $_GET['agent_rating'] ),
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

	add_action( 'pre_get_posts', 'ere_agent_filter_admin' );
}