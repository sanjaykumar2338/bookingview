<?php
/**
 * Section: Seasonal Prices
 * Panel:   Property Detail page.
 *
 * @since   1.3.0
 * @package realhomes_vacation_rentals/admin/customizer
 */

if ( ! function_exists( 'inspiry_seasonal_prices_customizer' ) ) {
	/**
	 * Seasonal Prices Customizer Settings Section.
	 *
	 * @since  1.3.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Object.
	 *
	 */
	function inspiry_seasonal_prices_customizer( WP_Customize_Manager $wp_customize ) {

		// Section Title.
		$wp_customize->add_section(
			'inspiry_seasonal_prices_section',
			array(
				'title'    => esc_html__( 'Seasonal Prices', 'realhomes-vacation-rentals' ),
				'panel'    => 'inspiry_property_panel',
				'priority' => 32
			)
		);

		/* Seasonal Prices Section Display*/
		$wp_customize->add_setting(
			'inspiry_seasonal_prices_display',
			array(
				'type'              => 'option',
				'default'           => 'true',
				'sanitize_callback' => 'inspiry_sanitize_radio',
			)
		);
		$wp_customize->add_control(
			'inspiry_seasonal_prices_display',
			array(
				'label'   => esc_html__( 'Seasonal Prices', 'realhomes-vacation-rentals' ),
				'type'    => 'radio',
				'section' => 'inspiry_seasonal_prices_section',
				'choices' => array(
					'true'  => esc_html__( 'Show', 'realhomes-vacation-rentals' ),
					'false' => esc_html__( 'Hide', 'realhomes-vacation-rentals' ),
				),
			),
		);

		/* Seasonal Section Heading */
		$wp_customize->add_setting(
			'inspiry_seasonal_prices_heading',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'default'           => esc_html__( 'Seasonal Prices', 'realhomes-vacation-rentals' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'inspiry_seasonal_prices_heading',
			array(
				'label'   => esc_html__( 'Seasonal Prices Heading', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'section' => 'inspiry_seasonal_prices_section',
			)
		);

		/* Start Date Column Label */
		$wp_customize->add_setting(
			'inspiry_sp_start_date_column_label',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'default'           => esc_html__( 'Start Date', 'realhomes-vacation-rentals' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'inspiry_sp_start_date_column_label',
			array(
				'label'   => esc_html__( 'Start Date Column Label', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'section' => 'inspiry_seasonal_prices_section',
			)
		);

		/* End Date Column Label */
		$wp_customize->add_setting(
			'inspiry_sp_end_date_column_label',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'default'           => esc_html__( 'End Date', 'realhomes-vacation-rentals' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'inspiry_sp_end_date_column_label',
			array(
				'label'   => esc_html__( 'End Date Column Label', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'section' => 'inspiry_seasonal_prices_section',
			)
		);

		/* Price Column Label */
		$wp_customize->add_setting(
			'inspiry_sp_price_column_label',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'default'           => esc_html__( 'Per Night', 'realhomes-vacation-rentals' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'inspiry_sp_price_column_label',
			array(
				'label'   => esc_html__( 'Price Column Label', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'section' => 'inspiry_seasonal_prices_section',
			)
		);

		/* Minimum Stay Column Label */
		$wp_customize->add_setting(
			'rvr_sp_min_stay_column_label',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'default'           => esc_html__( 'Minimum Stay', 'realhomes-vacation-rentals' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'rvr_sp_min_stay_column_label',
			array(
				'label'   => esc_html__( 'Minimum Stay Column Label', 'realhomes-vacation-rentals' ),
				'type'    => 'text',
				'section' => 'inspiry_seasonal_prices_section',
			)
		);

		/* Seasonal Prices Heading Selective Refresh */
		if ( 'classic' === INSPIRY_DESIGN_VARIATION ) {
			$section_heading_selector = '#seasonal-prices-wrap .title';
		} else {
			$section_heading_selector = '.rvr_seasonal_prices_wrap .rh_property__heading';
		}
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'inspiry_seasonal_prices_heading',
				array(
					'selector'            => $section_heading_selector,
					'container_inclusive' => false,
					'render_callback'     => 'inspiry_seasonal_prices_heading_render',
				)
			);
		}

		/* Seasonal Prices Start Date Selective Refresh */
		if ( 'classic' === INSPIRY_DESIGN_VARIATION ) {
			$start_date_column_label = '#overview .sp-start-date-column';
		} else {
			$start_date_column_label = '.rvr_seasonal_prices_wrap .sp-start-date-column';
		}
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'inspiry_sp_start_date_column_label',
				array(
					'selector'            => $start_date_column_label,
					'container_inclusive' => false,
					'render_callback'     => 'inspiry_sp_start_date_column_label_render',
				)
			);
		}

		/* Seasonal Prices End Date Selective Refresh */
		if ( 'classic' === INSPIRY_DESIGN_VARIATION ) {
			$end_date_column_label = '#overview .sp-end-date-column';
		} else {
			$end_date_column_label = '.rvr_seasonal_prices_wrap .sp-end-date-column';
		}
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'inspiry_sp_end_date_column_label',
				array(
					'selector'            => $end_date_column_label,
					'container_inclusive' => false,
					'render_callback'     => 'inspiry_sp_end_date_column_label_render',
				)
			);
		}

		/* Seasonal Prices (Price) Selective Refresh */
		if ( 'classic' === INSPIRY_DESIGN_VARIATION ) {
			$price_column_label = '#overview .sp-price-column';
		} else {
			$price_column_label = '.rvr_seasonal_prices_wrap .sp-price-column';
		}
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'inspiry_sp_price_column_label',
				array(
					'selector'            => $price_column_label,
					'container_inclusive' => false,
					'render_callback'     => 'inspiry_sp_price_column_label_render',
				)
			);
		}

		/* Seasonal Prices Minimum Stay Selective Refresh */
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'rvr_sp_min_stay_column_label',
				array(
					'selector'            => '.rvr_seasonal_prices_wrap .sp-min-stay-column',
					'container_inclusive' => false,
					'render_callback'     => 'rvr_sp_min_stay_column_label_render',
				)
			);
		}
	}

	add_action( 'customize_register', 'inspiry_seasonal_prices_customizer' );
}

if ( ! function_exists( 'inspiry_seasonal_prices_heading_render' ) ) {
	/**
	 * Seasonal prices heading selective refresh rendering.
	 */
	function inspiry_seasonal_prices_heading_render() {
		if ( get_option( 'inspiry_seasonal_prices_heading' ) ) {
			echo esc_html( get_option( 'inspiry_seasonal_prices_heading' ) );
		}
	}
}

if ( ! function_exists( 'inspiry_sp_start_date_column_label_render' ) ) {
	/**
	 * Seasonal prices start date column label selective refresh rendering.
	 */
	function inspiry_sp_start_date_column_label_render() {
		if ( get_option( 'inspiry_sp_start_date_column_label' ) ) {
			echo esc_html( get_option( 'inspiry_sp_start_date_column_label' ) );
		}
	}
}

if ( ! function_exists( 'inspiry_sp_end_date_column_label_render' ) ) {
	/**
	 * Seasonal prices end date column label selective refresh rendering.
	 */
	function inspiry_sp_end_date_column_label_render() {
		if ( get_option( 'inspiry_sp_end_date_column_label' ) ) {
			echo esc_html( get_option( 'inspiry_sp_end_date_column_label' ) );
		}
	}
}

if ( ! function_exists( 'inspiry_sp_price_column_label_render' ) ) {
	/**
	 * Seasonal prices (price) column label selective refresh rendering.
	 */
	function inspiry_sp_price_column_label_render() {
		if ( get_option( 'inspiry_sp_price_column_label' ) ) {
			echo esc_html( get_option( 'inspiry_sp_price_column_label' ) );
		}
	}
}

if ( ! function_exists( 'rvr_sp_min_stay_column_label_render' ) ) {
	/**
	 * Seasonal prices minimum stay column label selective refresh rendering.
	 */
	function rvr_sp_min_stay_column_label_render() {
		if ( get_option( 'rvr_sp_min_stay_column_label' ) ) {
			echo esc_html( get_option( 'rvr_sp_min_stay_column_label' ) );
		}
	}
}

