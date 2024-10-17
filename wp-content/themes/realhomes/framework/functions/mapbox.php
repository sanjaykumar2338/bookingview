<?php
/**
 * Contains Enqueue and Render functions related to MapBox
 *
 * @since 3.21.0
 */
if ( ! function_exists( 'realhomes_enqueue_mapbox' ) ) {
	/**
	 * Enqueue MapBox related JS and CSS files.
	 *
	 * @since 3.21.0
	 */
	function realhomes_enqueue_mapbox() {

		if ( inspiry_is_map_needed() || inspiry_map_needed_for_address() ) {

			// Enqueue leaflet JS
			wp_register_script(
				'leaflet-js',
				'https://unpkg.com/leaflet@1.3.4/dist/leaflet.js',
				array(),
				'1.3.4',
				true
			);

			wp_register_script(
				'mapbox-script-2-9-2',
				'https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js',
				array(),
				'2.9.2',
				true
			);

			wp_register_script(
				'mapbox-script-3-3-1',
				'https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js',
				array(),
				'3.3.1',
				true
			);

			wp_register_script(
				'leaflet-marker-cluster',
				'https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js',
				array( 'jquery', 'leaflet-js' ),
				INSPIRY_THEME_VERSION,
				true
			);

			//Enqueue leaflet CSS
			wp_register_style(
				'leaflet-css',
				'https://unpkg.com/leaflet@1.3.4/dist/leaflet.css',
				array(),
				'1.3.4'
			);

			wp_register_style(
				'mapbox-style-3-3-1',
				'https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css',
				array(),
				'3.3.1'
			);

			wp_register_style(
				'mapbox-style-2-9-2',
				'https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css',
				array(),
				'2.9.2'
			);

			wp_register_style(
				'leaflet-marker-cluster-css',
				'https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css',
				array(),
				'1.0.0'
			);

			wp_register_style(
				'leaflet-marker-cluster-default-css',
				'https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css',
				array(),
				'1.0.0'
			);

			// Now we need to load JS files and Localized data based on the page visitor is on.
			if ( is_singular( 'property' ) && ( 'true' == get_option( 'theme_display_google_map' ) ) ) {
				realhomes_render_property_mapbox();

			} else if ( realhomes_current_template( 'page', array( 'contact' ) ) && ( '1' == get_post_meta( get_the_ID(), 'theme_show_contact_map', true ) ) ) {
				realhomes_render_contact_mapbox();

			} else if ( realhomes_current_template( 'page', array( 'submit-property', 'dashboard' ) ) ) {
				realhomes_render_submit_mapbox();

			} else if ( realhomes_current_template( 'page', array( 'half-map-layout' ) ) ) {
				realhomes_render_multi_properties_mapbox();

			} else if ( realhomes_current_template( 'page', array( 'home' ) ) ) {

				$theme_homepage_module = get_post_meta( get_the_ID(), 'theme_homepage_module', true );
				if ( isset( $_GET['module'] ) ) {
					$theme_homepage_module = $_GET['module'];
				}

				if ( 'properties-map' == $theme_homepage_module ) {
					realhomes_render_multi_properties_mapbox();
				}

			} else if (
				realhomes_current_template(
					'page',
					array(
						'properties-search',
						'properties-search-half-map',
						'half-map-layout',
						'properties-search-left-sidebar',
						'properties-search-right-sidebar',
						'properties'
					)
				)
			) {
				$theme_search_module = get_option( 'theme_search_module', 'properties-map' );
				if (
					(
						'modern' === INSPIRY_DESIGN_VARIATION || 'ultra' === INSPIRY_DESIGN_VARIATION
						&& (
							inspiry_is_search_page_map_visible()
							|| realhomes_current_template( 'page', array( 'properties-search-half-map' ) )
						)
					) ||
					(
						'classic' === INSPIRY_DESIGN_VARIATION
						&& (
							'properties-map' == $theme_search_module
							|| realhomes_current_template( 'page', array( 'properties-search-half-map' ) )
						)
					)
				) {
					realhomes_render_multi_properties_mapbox();
				}

			} else if (
				realhomes_current_template(
					'page',
					array(
						'list-layout',
						'grid-layout',
						'list-layout-full-width',
						'grid-layout-full-width',
						'properties'
					)
				) || realhomes_current_template(
					'taxonomy', array(
						'property-city',
						'property-status',
						'property-type',
						'property-feature'
					)
				) || is_post_type_archive( 'property' )
			) {
				$theme_listing_module = get_option( 'theme_listing_module' );
				if ( isset( $_GET['module'] ) ) {
					$theme_listing_module = $_GET['module'];
				}
				if ( $theme_listing_module == 'properties-map' ) {
					realhomes_render_multi_properties_mapbox();
				}
			}
		}
	}
}

if ( ! function_exists( 'realhomes_render_submit_mapbox' ) ) {
	/**
	 * Render open street map for submit property page
	 * For now it is open street map as MapBox Search API is not yet workable
	 *
	 * @since 3.21.0
	 */
	function realhomes_render_submit_mapbox() {
		if ( is_page_template( array( 'templates/dashboard.php' ) ) && ( realhomes_dashboard_is_submit_property_page() || ( ! is_user_logged_in() && inspiry_guest_submission_enabled() ) ) ) {

			// TODO: Open Street Map is temporary being used till mapbox search API is fully functional
			wp_enqueue_style( 'leaflet-css' );
			wp_enqueue_script( 'leaflet-js' );

			wp_enqueue_script(
				'submit-open-street-map',
				get_theme_file_uri( 'common/js/submit-open-street-map.js' ),
				array( 'jquery', 'leaflet-js' ),
				INSPIRY_THEME_VERSION,
				true
			);
		}
	}
}

if ( ! function_exists( 'realhomes_render_contact_mapbox' ) ) {
	/**
	 * Render MapBox for contact page
	 *
	 * @since 3.21.0
	 */
	function realhomes_render_contact_mapbox() {

		wp_enqueue_style( 'mapbox-style-2-9-2' );
		wp_enqueue_script( 'leaflet-js' );
		wp_enqueue_script( 'mapbox-script-2-9-2' );

		wp_register_script(
			'contact-mapbox',
			get_theme_file_uri( 'common/js/contact-mapbox.js' ),
			array( 'jquery' ),
			INSPIRY_THEME_VERSION,
			true
		);

		// Localized data for the map.
		realhomes_localize_map_Data( 'contact-mapbox' );

		$page_id                  = get_the_ID();
		$contact_map_lat          = get_post_meta( $page_id, 'theme_map_lati', true );
		$contact_map_lang         = get_post_meta( $page_id, 'theme_map_longi', true );
		$contact_marker_image_id  = get_post_meta( $page_id, 'inspiry_contact_map_icon', true );
		$contact_marker_image_url = wp_get_attachment_url( intval( $contact_marker_image_id ) );
		$contact_marker_color     = get_post_meta( $page_id, 'inspiry_contact_marker_color', true );

		if ( $contact_map_lat && $contact_map_lang ) {

			$contact_map_data = array();

			$contact_map_data['lat']         = $contact_map_lat;
			$contact_map_data['lng']         = $contact_map_lang;
			$contact_map_data['mapboxAPI']   = get_option( 'ere_mapbox_api_key' );
			$contact_map_data['mapboxStyle'] = get_option( 'ere_mapbox_style' );
			if ( ! empty( $contact_marker_image_url ) ) {
				$contact_map_data['iconURL'] = $contact_marker_image_url;
			} else {
				$contact_map_data['iconColor'] = $contact_marker_color;
			}
			$contact_map_data['zoom'] = intval( get_post_meta( $page_id, 'theme_map_zoom', true ) );

			wp_localize_script( 'contact-mapbox', 'contactMapData', $contact_map_data );
			wp_enqueue_script( 'contact-mapbox' );
		}
	}
}

if ( ! function_exists( 'realhomes_render_multi_properties_mapbox' ) ) {
	/**
	 * Render MapBox for multiple properties
	 *
	 * @since 3.21.0
	 */
	function realhomes_render_multi_properties_mapbox() {

		wp_enqueue_style( 'leaflet-css' );
		wp_enqueue_style( 'mapbox-style-3-3-1' );
		wp_enqueue_style( 'leaflet-marker-cluster-css' );
		wp_enqueue_style( 'leaflet-marker-cluster-default-css' );
		wp_enqueue_script( 'leaflet-js' );
		wp_enqueue_script( 'mapbox-script-3-3-1' );
		wp_enqueue_script( 'leaflet-marker-cluster' );

		wp_register_script(
			'properties-mapbox',
			get_theme_file_uri( 'common/js/properties-mapbox.js' ),
			array( 'jquery', 'mapbox-script-3-3-1', 'leaflet-marker-cluster' ),
			INSPIRY_THEME_VERSION,
			true
		);

		// Localized data for the map.
		realhomes_localize_map_Data( 'properties-mapbox' );

		$map_properties_query_args = array(
			'post_type'      => 'property',
			'posts_per_page' => apply_filters( 'real_homes_properties_on_map', 500 ),
			'meta_query'     => array(
				array(
					'key'     => 'REAL_HOMES_property_address',
					'compare' => 'EXISTS',
				),
			)
		);

		if (
			realhomes_current_template( 'page',
				array(
					'properties-search',
					'properties-search-half-map',
					'properties-search-left-sidebar',
					'properties-search-right-sidebar'
				)
			)
		) {

			// Apply Search Filter
			$map_properties_query_args = apply_filters( 'real_homes_search_parameters', $map_properties_query_args );

			// Set number of properties for the search results map
			$properties_on_search_map = get_option( 'inspiry_properties_on_search_map', 'all' );
			if ( 'all' == $properties_on_search_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			}

		} else if ( realhomes_current_template( 'page', array( 'home' ) ) ) {
			// Apply Homepage Properties Filter
			$map_properties_query_args = apply_filters( 'real_homes_homepage_properties', $map_properties_query_args );

		} else if (
			realhomes_current_template(
				'page',
				array(
					'list-layout',
					'list-layout-full-width',
					'grid-layout',
					'grid-layout-full-width',
					'half-map-layout',
					'properties'
				)
			)
		) {

			// Apply properties filter settings from properties list templates.
			$map_properties_query_args = apply_filters( 'inspiry_properties_filter', $map_properties_query_args );

			// Apply sorting.
			$map_properties_query_args = sort_properties( $map_properties_query_args );

			// Set number of properties for the property listing pages map
			$properties_on_search_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );
			if ( 'all' == $properties_on_search_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			}

		} else if ( is_tax() ) {

			global $wp_query;

			// taxonomy query
			$map_properties_query_args['tax_query'] = array(
				array(
					'taxonomy' => $wp_query->query_vars['taxonomy'],
					'field'    => 'slug',
					'terms'    => $wp_query->query_vars['term'],
				),
			);

			// for pagination
			global $paged;
			$map_properties_query_args['paged'] = $paged;

			// Set number of properties for the property taxonomy pages map
			$properties_on_search_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );
			if ( 'all' == $properties_on_search_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			} else {
				$number_of_properties = intval( get_option( 'theme_number_of_properties', 6 ) );
				if ( 0 >= $number_of_properties ) {
					$number_of_properties = 6;
				}
				$map_properties_query_args['posts_per_page'] = $number_of_properties;
			}

		} else if ( is_post_type_archive( 'property' ) ) {

			global $paged;
			$map_properties_query_args['paged'] = $paged;

			// Set number of properties for the property archive page map
			$properties_on_search_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );
			if ( 'all' == $properties_on_search_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			} else {
				$number_of_properties = intval( get_option( 'theme_number_of_properties', 6 ) );
				if ( 0 >= $number_of_properties ) {
					$number_of_properties = 6;
				}
				$map_properties_query_args['posts_per_page'] = $number_of_properties;
			}
		}

		$map_properties_query                = new WP_Query( $map_properties_query_args );
		$properties_map_data                 = $propertiesMapOptions = array();
		$propertiesMapOptions['mapboxAPI']   = get_option( 'ere_mapbox_api_key' );
		$propertiesMapOptions['mapboxStyle'] = get_option( 'ere_mapbox_style' );

		if ( $map_properties_query->have_posts() ) {
			while ( $map_properties_query->have_posts() ) {
				$map_properties_query->the_post();

				$property_id                           = get_the_ID();
				$current_property_data                 = array();
				$current_property_data['title']        = get_the_title();
				$current_property_data['propertyType'] = ere_get_property_types( $property_id );

				if ( function_exists( 'ere_get_property_price' ) ) {
					$current_property_data['price'] = ere_get_property_price();
				} else {
					$current_property_data['price'] = null;
				}

				$current_property_data['url'] = get_permalink();
				$current_property_data['id']  = $property_id;

				// property location
				$property_location = get_post_meta( $property_id, 'REAL_HOMES_property_location', true );
				if ( ! empty( $property_location ) ) {
					$lat_lng                      = explode( ',', $property_location );
					$current_property_data['lat'] = $lat_lng[0];
					$current_property_data['lng'] = $lat_lng[1];
				}

				// property thumbnail
				if ( ! empty( get_the_post_thumbnail() ) ) {
					$image_id         = get_post_thumbnail_id();
					$image_attributes = wp_get_attachment_image_src( $image_id, 'property-thumb-image' );
					if ( ! empty( $image_attributes[0] ) ) {
						$current_property_data['thumb'] = $image_attributes[0];
					}
				} else {
					$current_property_data['thumb'] = get_inspiry_image_placeholder_url( 'property-thumb-image' );
				}

				// Property map icon based on Property Type
				$type_terms = get_the_terms( $property_id, 'property-type' );
				if ( $type_terms && ! is_wp_error( $type_terms ) ) {
					foreach ( $type_terms as $type_term ) {
						$icon_id = get_term_meta( $type_term->term_id, 'inspiry_property_type_icon', true );
						if ( ! empty ( $icon_id ) ) {
							$icon_url = wp_get_attachment_url( $icon_id );
							if ( $icon_url ) {
								$current_property_data['icon'] = esc_url( $icon_url );

								// Retina icon
								$retina_icon_id = get_term_meta( $type_term->term_id, 'inspiry_property_type_icon_retina', true );
								if ( ! empty ( $retina_icon_id ) ) {
									$retina_icon_url = wp_get_attachment_url( $retina_icon_id );
									if ( $retina_icon_url ) {
										$current_property_data['retinaIcon'] = esc_url( $retina_icon_url );
									}
								}
								break;
							}
						}
					}
				}

				// Set default icons if above code fails to set any
				if ( ! isset( $current_property_data['icon'] ) ) {
					$current_property_data['icon']       = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon.png';     // default icon
					$current_property_data['retinaIcon'] = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon@2x.png';  // default retina icon
				}

				$properties_map_data[] = $current_property_data;

			}
			wp_reset_postdata();
		}

		// Fallback Location
		$fallback_location = get_option( 'inspiry_properties_map_default_location', '27.664827,-81.515755' );
		if ( ! empty( $fallback_location ) ) {
			$lat_lng                                          = explode( ',', $fallback_location );
			$propertiesMapOptions['fallback_location']['lat'] = $lat_lng[0];
			$propertiesMapOptions['fallback_location']['lng'] = $lat_lng[1];
		}

		// Setting the map marker type d related data.
		$propertiesMapOptions['marker_type']  = get_option( 'inspiry_property_map_marker_type', 'pin' );
		$propertiesMapOptions['marker_color'] = get_option( 'inspiry_property_map_marker_color', '#ea723d' );

		wp_localize_script( 'properties-mapbox', 'propertiesMapData', $properties_map_data );
		wp_localize_script( 'properties-mapbox', 'propertiesMapOptions', $propertiesMapOptions );
		wp_enqueue_script( 'properties-mapbox' );

		$properties_on_the_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );
		$mapbox_api_key        = get_option( 'ere_mapbox_api_key' );

		if ( 'all' != $properties_on_the_map ) {

			$ajax_pagination_enabled = realhomes_get_ajax_pagination_status();

			if (
				(
					realhomes_current_template(
						'page',
						array(
							'list-layout',
							'list-layout-full-width',
							'grid-layout',
							'grid-layout-full-width',
							'half-map-layout',
							'properties-search',
							'properties-search-half-map',
							'properties-search-left-sidebar',
							'properties-search-right-sidebar',
							'properties'
						)
					)
					|| realhomes_current_template(
						'taxonomy', array(
							'property-city',
							'property-status',
							'property-type',
							'property-feature'
						)
					)
					|| is_post_type_archive( 'property' )
				)
				&& $ajax_pagination_enabled ) {

				wp_register_script(
					'ajax-properties-mapbox',
					get_theme_file_uri( 'assets/' . INSPIRY_DESIGN_VARIATION . '/scripts/js/ajax-properties-mapbox.js' ),
					array(
						'jquery',
						'leaflet-js',
						'properties-mapbox'
					),
					INSPIRY_THEME_VERSION,
					true
				);
				// Localizing data for the map rendering
				wp_localize_script( 'ajax-properties-mapbox', 'propertiesMapData', $properties_map_data );
				wp_localize_script( 'ajax-properties-mapbox', 'propertiesMapOptions', $propertiesMapOptions );
				wp_enqueue_script( 'ajax-properties-mapbox' );
			}
		}
	}
}

if ( ! function_exists( 'realhomes_render_property_mapbox' ) ) {
	/**
	 * Render mapbox for single property
	 *
	 * @since 3.21.0
	 */
	function realhomes_render_property_mapbox() {

		wp_enqueue_script( 'mapbox-script-2-9-2' );
		wp_enqueue_style( 'mapbox-style-2-9-2' );

		wp_register_script(
			'property-mapbox',
			get_theme_file_uri( 'common/js/property-mapbox.js' ),
			array( 'jquery', 'mapbox-script-2-9-2', 'leaflet-js' ),
			INSPIRY_THEME_VERSION,
			true
		);

		// Localized data for the map.
		realhomes_localize_map_Data( 'property-mapbox' );

		$property_id       = get_the_ID();
		$property_location = get_post_meta( $property_id, 'REAL_HOMES_property_location', true );
		$property_address  = get_post_meta( $property_id, 'REAL_HOMES_property_address', true );
		$property_map      = get_post_meta( $property_id, 'REAL_HOMES_property_map', true );

		if ( $property_address && ! empty( $property_location ) && ( 1 != $property_map ) ) {

			$property_map_data                 = array();
			$property_map_data['mapboxAPI']    = get_option( 'ere_mapbox_api_key' );
			$property_map_data['mapboxStyle']  = get_option( 'ere_mapbox_style' );
			$property_map_data['title']        = get_the_title();
			$property_map_data['propertyType'] = ere_get_property_types( $property_id );

			if ( function_exists( 'ere_get_property_price' ) ) {
				$property_map_data['price'] = ere_get_property_price();
			} else {
				$property_map_data['price'] = null;
			}

			// Property Latitude and Longitude
			$lat_lng                  = explode( ',', $property_location );
			$property_map_data['lat'] = $lat_lng[0];
			$property_map_data['lng'] = $lat_lng[1];

			// Property thumbnail
			if ( ! empty( get_the_post_thumbnail() ) ) {
				$image_id         = get_post_thumbnail_id();
				$image_attributes = wp_get_attachment_image_src( $image_id, 'property-thumb-image' );
				if ( ! empty( $image_attributes[0] ) ) {
					$property_map_data['thumb'] = $image_attributes[0];
				}
			} else {
				$property_map_data['thumb'] = get_inspiry_image_placeholder_url( 'property-thumb-image' );
			}

			// Property map icon based on Property Type
			$type_terms = get_the_terms( $property_id, 'property-type' );
			if ( $type_terms && ! is_wp_error( $type_terms ) ) {
				foreach ( $type_terms as $type_term ) {
					$icon_id = get_term_meta( $type_term->term_id, 'inspiry_property_type_icon', true );
					if ( ! empty ( $icon_id ) ) {
						$icon_url = wp_get_attachment_url( $icon_id );
						if ( $icon_url ) {
							$property_map_data['icon'] = esc_url( $icon_url );

							// Retina icon
							$retina_icon_id = get_term_meta( $type_term->term_id, 'inspiry_property_type_icon_retina', true );
							if ( ! empty ( $retina_icon_id ) ) {
								$retina_icon_url = wp_get_attachment_url( $retina_icon_id );
								if ( $retina_icon_url ) {
									$property_map_data['retinaIcon'] = esc_url( $retina_icon_url );
								}
							}
							break;
						}
					}
				}
			}

			// Set default icons if above code fails to set any
			if ( ! isset( $property_map_data['icon'] ) ) {
				$property_map_data['icon']       = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon.png'; // default icon
				$property_map_data['retinaIcon'] = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon@2x.png'; // default retina icon
			}

			// Set Google Map Type & Zoom Level
			$property_map_data['zoom']         = get_option( 'inspiry_property_map_zoom', '15' );
			$property_map_data['marker_type']  = get_option( 'inspiry_property_map_marker_type', 'pin' );
			$property_map_data['marker_color'] = get_option( 'inspiry_property_map_marker_color', '#ea723d' );

			wp_localize_script( 'property-mapbox', 'propertyMapData', $property_map_data );
			wp_enqueue_script( 'property-mapbox' );
		}
	}
}

if ( ! function_exists( 'realhomes_render_properties_on_mapbox' ) ) {
	/**
	 * Sending properties data to the AJAX call
	 *
	 * @since 3.21.0
	 */
	function realhomes_render_properties_on_mapbox() {

		// Override number of properties for search results map
		$properties_on_search_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );

		$map_properties_query_args = array(
			'post_type'  => 'property',
			'meta_query' => array(
				array(
					'key'     => 'REAL_HOMES_property_address',
					'compare' => 'EXISTS',
				),
			)
		);

		$paged                 = $_POST['page_number'] ?? '';
		$page_id               = $_POST['page_id'] ?? '';
		$is_taxonomy           = $_POST['is_taxonomy'] ?? '';
		$current_page_template = get_post_meta( $page_id, '_wp_page_template', true );

		if ( ! empty( $paged ) ) {
			$map_properties_query_args['paged'] = $paged;
		}

		if ( in_array( $current_page_template, array( 'templates/properties-search.php' ) ) ) {

			// Apply Search Filter
			$map_properties_query_args = apply_filters( 'real_homes_search_parameters', $map_properties_query_args );

			// Set number of properties for the search results map
			$properties_on_search_map = get_option( 'inspiry_properties_on_search_map', 'all' );
			if ( 'all' == $properties_on_search_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			}

		} else if ( in_array( $current_page_template, array( 'templates/properties.php' ) ) ) {

			// Apply properties filter settings from properties list templates.
			$map_properties_query_args = apply_filters( 'inspiry_properties_filter', $map_properties_query_args );

			// Apply sorting.
			$map_properties_query_args = sort_properties( $map_properties_query_args );

			// Number of properties on each page
			$theme_number_of_properties = get_option( 'theme_number_of_properties', 6 );
			$number_of_properties       = get_post_meta( $page_id, 'inspiry_posts_per_page', true );

			if ( ! empty( $number_of_properties ) ) {
				$map_properties_query_args['posts_per_page'] = $number_of_properties;
			} else if ( ! empty( $theme_number_of_properties ) ) {
				$map_properties_query_args['posts_per_page'] = $theme_number_of_properties;
			} else {
				$map_properties_query_args['posts_per_page'] = 6;
			}

			// Set number of properties for the property listing pages map
			$properties_on_search_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );
			if ( 'all' == $properties_on_search_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			}

			if ( ! empty( $paged ) && $properties_on_search_map !== 'all' ) {
				$map_properties_query_args['paged'] = intval( $paged );
			}

		} else if ( ! empty( $is_taxonomy ) && 'true' === $is_taxonomy ) {

			$term = get_term( $page_id );

			// Taxonomy Query
			$map_properties_query_args['tax_query'] = array(
				array(
					'taxonomy' => $term->taxonomy,
					'field'    => 'slug',
					'terms'    => $term->slug,
				),
			);

			// Set number of properties for the property taxonomy pages map
			$properties_on_tax_map = get_option( 'inspiry_properties_list_tax_on_map', 'all' );
			if ( 'all' == $properties_on_tax_map ) {
				$map_properties_query_args['posts_per_page'] = -1;
			} else {
				$number_of_properties = intval( get_option( 'theme_number_of_properties', 6 ) );
				if ( 0 >= $number_of_properties ) {
					$number_of_properties = 6;
				}
				$map_properties_query_args['posts_per_page'] = $number_of_properties;
				if ( ! empty( $paged ) ) {
					$map_properties_query_args['paged'] = intval( $paged );
				}
			}

		}

		$map_properties_query = new WP_Query( $map_properties_query_args );
		$properties_map_data  = array();
		$propertiesMapOptions = array();

		if ( $map_properties_query->have_posts() ) {
			while ( $map_properties_query->have_posts() ) {
				$map_properties_query->the_post();

				$current_property_data                 = array();
				$current_property_data['title']        = get_the_title();
				$current_property_data['propertyType'] = ere_get_property_types( get_the_ID() );

				if ( function_exists( 'ere_get_property_price' ) ) {
					$current_property_data['price'] = ere_get_property_price();
				} else {
					$current_property_data['price'] = null;
				}

				$current_property_data['url'] = get_permalink();
				$current_property_data['id']  = get_the_ID();

				// property location
				$property_location = get_post_meta( get_the_ID(), 'REAL_HOMES_property_location', true );
				if ( ! empty( $property_location ) ) {
					$lat_lng                      = explode( ',', $property_location );
					$current_property_data['lat'] = $lat_lng[0];
					$current_property_data['lng'] = $lat_lng[1];
				}

				// property thumbnail
				if ( ! empty( get_the_post_thumbnail() ) ) {
					$image_id         = get_post_thumbnail_id();
					$image_attributes = wp_get_attachment_image_src( $image_id, 'property-thumb-image' );
					if ( ! empty( $image_attributes[0] ) ) {
						$current_property_data['thumb'] = $image_attributes[0];
					}
				} else {
					$current_property_data['thumb'] = get_inspiry_image_placeholder_url( 'property-thumb-image' );
				}

				// Property map icon based on Property Type
				$type_terms = get_the_terms( get_the_ID(), 'property-type' );
				if ( $type_terms && ! is_wp_error( $type_terms ) ) {
					foreach ( $type_terms as $type_term ) {
						$icon_id = get_term_meta( $type_term->term_id, 'inspiry_property_type_icon', true );
						if ( ! empty ( $icon_id ) ) {
							$icon_url = wp_get_attachment_url( $icon_id );
							if ( $icon_url ) {
								$current_property_data['icon'] = esc_url( $icon_url );

								// Retina icon
								$retina_icon_id = get_term_meta( $type_term->term_id, 'inspiry_property_type_icon_retina', true );
								if ( ! empty ( $retina_icon_id ) ) {
									$retina_icon_url = wp_get_attachment_url( $retina_icon_id );
									if ( $retina_icon_url ) {
										$current_property_data['retinaIcon'] = esc_url( $retina_icon_url );
									}
								}
								break;
							}
						}
					}
				}

				// Set default icons if above code fails to sets any
				if ( ! isset( $current_property_data['icon'] ) ) {
					$current_property_data['icon']       = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon.png';     // default icon
					$current_property_data['retinaIcon'] = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon@2x.png';  // default retina icon
				}

				$properties_map_data[] = $current_property_data;

			}
			wp_reset_postdata();
		}

		wp_send_json_success(
			array(
				'propertiesData' => $properties_map_data,
			)
		);

		die;
	}

	add_action( 'wp_ajax_nopriv_realhomes_render_properties_on_mapbox', 'realhomes_render_properties_on_mapbox' );
	add_action( 'wp_ajax_realhomes_render_properties_on_mapbox', 'realhomes_render_properties_on_mapbox' );
}

if ( ! function_exists( 'realhomes_current_template' ) ) {
	/**
	 * Checking current templates status
	 *
	 * @since 3.20.1
	 *
	 * @param array              $templates
	 *
	 * @param string|array|mixed $type options: page|taxonomy|archive
	 *
	 * @return boolean
	 */
	function realhomes_current_template( $type = 'page', $templates = array() ) {

		if ( is_array( $type ) ) {

			foreach ( $type as $type_single ) {
				realhomes_current_template( $type_single, $templates );
			}

		} else {

			if ( $type == 'taxonomy' ) {
				if ( 0 < count( $templates ) ) {
					foreach ( $templates as $template ) {
						if ( is_tax( $template ) ) {
							return true;
						}
					}

					return false;
				} else if ( is_tax() ) {
					return true;
				}

				return false;

			} else if ( $type == 'page' ) {

				if ( is_array( $templates ) ) {
					if ( 0 < count( $templates ) ) {
						foreach ( $templates as $template ) {
							if ( is_page_template( 'templates/' . $template . '.php' ) ) {
								return true;
							}
						}

						return false;
					}

					return false;
				}

			} else if ( $type == 'archive' ) {

				if ( 0 < count( $templates ) ) {
					foreach ( $templates as $template ) {
						if ( is_post_type_archive( $template ) ) {
							return true;
						}
					}

					return false;
				}
			}
		}

		return false;
	}
}