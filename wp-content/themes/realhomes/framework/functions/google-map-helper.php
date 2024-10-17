<?php
if ( ! function_exists( 'inspiry_apply_google_maps_arguments' ) ) :
	/**
	 * This function adds google maps arguments to admins side maps displayed in meta boxes
	 *
	 * @param string $google_maps_url - Google Maps URL.
	 *
	 * @return string
	 */
	function inspiry_apply_google_maps_arguments( $google_maps_url ) {
		$google_map_arguments = array();

		return esc_url_raw(
			add_query_arg(
				apply_filters(
					'inspiry_google_map_arguments',
					$google_map_arguments
				),
				$google_maps_url
			)
		);
	}
endif;

if ( ! function_exists( 'inspiry_google_maps_api_key' ) ) :
	/**
	 * This function adds API key ( if provided in settings ) to google maps arguments
	 *
	 * @param string $google_map_arguments - Google Maps Arguments.
	 *
	 * @return string
	 */
	function inspiry_google_maps_api_key( $google_map_arguments ) {
		// Get Google Maps API Key if available
		$google_maps_api_key = get_option( 'inspiry_google_maps_api_key' );
		if ( ! empty( $google_maps_api_key ) ) {
			$google_map_arguments['key'] = urlencode( $google_maps_api_key );
		}

		return $google_map_arguments;
	}

	add_filter( 'inspiry_google_map_arguments', 'inspiry_google_maps_api_key' );
endif;

if ( ! function_exists( 'inspiry_google_maps_language' ) ) :
	/**
	 * This function add current language to google maps arguments
	 *
	 * @param string $google_map_arguments - Google Maps Arguments.
	 *
	 * @return string
	 */
	function inspiry_google_maps_language( $google_map_arguments ) {
		// Localise Google Map if related theme options is set
		if ( 'true' == get_option( 'theme_map_localization' ) ) {
			if ( function_exists( 'wpml_object_id_filter' ) ) {
				$google_map_arguments['language'] = urlencode( ICL_LANGUAGE_CODE );
			} else {
				$google_map_arguments['language'] = urlencode( get_locale() );
			}
		}

		return $google_map_arguments;
	}

	add_filter( 'inspiry_google_map_arguments', 'inspiry_google_maps_language' );
endif;

if ( ! function_exists( 'realhomes_google_maps_api_release_channel' ) ) :
	/**
	 * Adds the Google Map API release channel.
	 *
	 * @param string $google_map_arguments - Google Maps Arguments.
	 *
	 * @since 4.2.0
	 *
	 * @return array
	 */
	function realhomes_google_maps_api_release_channel( $google_map_arguments ) {
		$google_map_arguments['v'] = 'quarterly';

		return $google_map_arguments;
	}

	add_filter( 'inspiry_google_map_arguments', 'realhomes_google_maps_api_release_channel' );
endif;

if ( ! function_exists( 'realhomes_google_maps_api_callback' ) ) :
	/**
	 * Sets the callback function for the Google Maps API.
	 *
	 * @param string $google_map_arguments - Google Maps Arguments.
	 *
	 * @since 4.3.0
	 *
	 * @return array Modified Google Maps arguments with custom callback.
	 */
	function realhomes_google_maps_api_callback( $google_map_arguments ) {
		$google_map_arguments['callback'] = 'Function.prototype';

		return $google_map_arguments;
	}

	add_filter( 'inspiry_google_map_arguments', 'realhomes_google_maps_api_callback' );
endif;

if ( ! function_exists( 'realhomes_google_maps_api_loading' ) ) :
	/**
	 * Sets the loading method for the Google Maps API to asynchronous.
	 *
	 * @param string $google_map_arguments - Google Maps Arguments.
	 *
	 * @since 4.3.0
	 *
	 * @return array Modified Google Maps arguments with asynchronous loading set.
	 */
	function realhomes_google_maps_api_loading( $google_map_arguments ) {
		$google_map_arguments['loading'] = 'async';

		return $google_map_arguments;
	}

	//add_filter( 'inspiry_google_map_arguments', 'realhomes_google_maps_api_loading' );
endif;

if ( ! function_exists( 'realhomes_localize_map_Data' ) ) :
	/**
	 * Localize map data for the RealHomes theme.
	 *
	 * This function localizes the map data by providing the current design variation
	 * and boolean flags for 'classic', 'modern', and 'ultra' variations. It then uses
	 * wp_localize_script() to make this data available to the specified script handle.
	 *
	 * @since 4.3.2
	 *
	 * @param string $handle The script handle to which the localized data should be attached.
	 */
	function realhomes_localize_map_Data( $handle ) {
		// Current design variation.
		$current_variation = get_option( 'inspiry_design_variation', 'ultra' );
		$rhMapsData        = array(
			'rhVariation' => $current_variation,
			'isClassic'   => $current_variation === 'classic',
			'isModern'    => $current_variation === 'modern',
			'isUltra'     => $current_variation === 'ultra',
		);

		// Localized map data.
		wp_localize_script( $handle, 'rhMapsData', $rhMapsData );
	}
endif;
