<?php
/**
 * Section:  `WP Login Page`
 * Panel:    `Styles`
 *
 * @package realhomes/customizer
 * @since 4.3.3
 */
if ( ! function_exists( 'realhomes_wp_login_page_customizer' ) ) {
	/**
	 * WP Login Page Styles
	 *
	 * @param WP_Customize_Manager $wp_customize - Instance of WP_Customize_Manager.
	 *
	 * @since 4.3.3
	 */
	function realhomes_wp_login_page_customizer( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_section( 'realhomes_wp_login_page_section', array(
			'title'       => esc_html__( 'WP Login Page', 'framework' ),
			'panel'       => 'inspiry_styles_panel',
			'priority'    => 10,
		) );

		$wp_customize->add_setting( 'realhomes_wp_login_page_styles', array(
			'type'              => 'option',
			'default'           => 'enable',
			'sanitize_callback' => 'inspiry_sanitize_radio',
		) );
		$wp_customize->add_control( 'realhomes_wp_login_page_styles', array(
			'label'       => esc_html__( 'WP Login Page Styles', 'framework' ),
			'type'        => 'radio',
			'choices'     => array(
				'enable'  => esc_html__( 'Enable', 'framework' ),
				'disable' => esc_html__( 'Disable', 'framework' ),
			),
			'section'     => 'realhomes_wp_login_page_section',
		) );
	}

	add_action( 'customize_register', 'realhomes_wp_login_page_customizer' );
}