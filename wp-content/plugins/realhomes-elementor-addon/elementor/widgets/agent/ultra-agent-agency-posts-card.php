<?php
/**
 * Ultra Agent/Agency Posts Card Widget
 *
 * @since 2.3.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Ultra_Agent_Agency_Card_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'rhea-ultra-agent-card-widget';
	}

	public function get_title() {
		return esc_html__( 'Ultra:Agent/Agency Posts Card', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'ultra-real-homes' ];
	}

	public function get_script_depends() {

		wp_register_script(
			'rhea-agent-agency-posts-js',
			RHEA_PLUGIN_URL . '/elementor/js/agent-agency-posts.js',
			array( 'elementor-frontend' ),
			RHEA_VERSION,
			true
		);

		return [ 'rhea-agent-agency-posts-js' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'basic_settings',
			[
				'label' => esc_html__( 'Basic', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'all_agent_agency_posts',
			[
				'label'        => esc_html__( 'Display All Posts For Agent/Agency', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'select-post-type',
			[
				'label'       => esc_html__( 'Select Post Type', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'default'     => 'agent',
				'label_block' => true,
				'options'     => array(
					'agent'  => esc_html__( 'Agent', 'realhomes-elementor-addon' ),
					'agency' => esc_html__( 'Agency', 'realhomes-elementor-addon' ),
				),
				'condition'   => [
					'all_agent_agency_posts' => 'yes',
				],
			]
		);

		$this->add_control(
			'agent_id',
			[
				'label'     => esc_html__( 'Agent/Agency ID To Show Single Agent Details', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'all_agent_agency_posts' => '',
				],
			]
		);

		$this->add_control(
			'show_posts_filter',
			[
				'label'        => esc_html__( 'Show Agents/Agencies Filters', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'   => [
					'all_agent_agency_posts' => 'yes',
				],
				'separator' =>  'before'
			]
		);

		$this->add_control(
			'filters_form_title',
			[
				'label'     => esc_html__( 'Filters Form Title', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Filter Agents', 'realhomes-elementor-addon' ),
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_posts_filter_property_count',
			[
				'label'        => esc_html__( 'Show Properties Count Filter', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => [
					'show_posts_filter' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_posts_filter_location',
			[
				'label'        => esc_html__( 'Show Location Filter', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => [
					'show_posts_filter' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_posts_sorting_options',
			[
				'label'        => esc_html__( 'Show Sorting Filter', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition' => [
					'show_posts_filter' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_verified_posts_filter',
			[
				'label'        => esc_html__( 'Show Verified Checkbox', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => [
					'show_posts_filter' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_ratings_filter',
			[
				'label'        => esc_html__( 'Show Ratings Filter', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => [
					'show_posts_filter' => 'yes'
				],
				'separator' =>  'after'
			]
		);

		$this->add_control(
			'agency_prefix',
			[
				'label'   => esc_html__( 'Related Agency Prefix', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Company Agent at The', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'show_property_counter',
			[
				'label'        => esc_html__( 'Show Property Counter', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_company_name',
			[
				'label'        => esc_html__( 'Show Company Name', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'show_description',
			[
				'label'        => esc_html__( 'Show Description', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'description_heading',
			[
				'label'     => esc_html__( 'Description Heading', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'About', 'realhomes-elementor-addon' ),
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_progress_stats',
			[
				'label'        => esc_html__( 'Show Progress & Stats ', 'realhomes-elementor-addon' ),
				'description'  => esc_html__( 'To be displayed only on Agent/Agency single page', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition' => [
					'all_agent_agency_posts' => '',
				],
			]
		);
		$this->add_control(
			'progress_stats_heading',
			[
				'label'     => esc_html__( 'Progress & Stats Heading', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Progress & Stats', 'realhomes-elementor-addon' ),
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_control(
			'property_locations_label',
			[
				'label'     => esc_html__( 'Property Locations Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Property Location', 'realhomes-elementor-addon' ),
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_control(
			'property_types_label',
			[
				'label'     => esc_html__( 'Property Types Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Property Types', 'realhomes-elementor-addon' ),
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_control(
			'property_status_label',
			[
				'label'     => esc_html__( 'Property Status Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Property Status', 'realhomes-elementor-addon' ),
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sizes_spaces',
			[
				'label' => esc_html__( 'Sizes & Spaces', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'filter_form_wrapper_height',
			[
				'label'      => esc_html__( 'Filter Form Wrapper Height', 'realhomes-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 170,
				],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'filter_form_margin',
			[
				'label'      => esc_html__( 'Filter Form Wrapper Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'filter_form_padding',
			[
				'label'      => esc_html__( 'Filter Form Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'filter_form_title_margin',
			[
				'label'      => esc_html__( 'Filter Form Title Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'filter_form_label_margin',
			[
				'label'      => esc_html__( 'Filter Form Label Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field > label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'filter_form_input_field_padding',
			[
				'label'      => esc_html__( 'Filter Form Input Field Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field .dropdown .dropdown-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'filter_form_input_field_margin',
			[
				'label'      => esc_html__( 'Filter Form Input Field Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field input[type=text]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field input[type=number]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field .dropdown .dropdown-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_posts_filter' => 'yes'
				],
                'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Wrapper Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-single-agent-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card-border-radius',
			[
				'label'      => esc_html__( 'Wrapper Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-single-agent-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image-card-border-radius',
			[
				'label'      => esc_html__( 'Thumb Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .agent-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'colors',
			[
				'label' => esc_html__( 'Colors', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'filter_form_background',
			[
				'label'     => esc_html__( 'Filter Form Title', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form > h4' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_title',
			[
				'label'     => esc_html__( 'Filter Form background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_labels_color',
			[
				'label'     => esc_html__( 'Filter Form Labels', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form form label' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_field_background_color',
			[
				'label'     => esc_html__( 'Filter Form Fields Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form form input[type=text]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-agents-search-form form input[type=number]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field .dropdown .dropdown-toggle' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_field_text_color',
			[
				'label'     => esc_html__( 'Filter Form Fields Text Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form form input[type=text]' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-agents-search-form form input[type=number]' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field .dropdown .dropdown-toggle' => 'color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_basic_color',
			[
				'label'     => esc_html__( 'Filter Form Icons Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .main-field .more-filters svg .rh-ultra-dark' => 'stroke: {{VALUE}}',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.ratings-filter .rating-options-wrap .checkbox-switch .rating-stars' => 'border-color: {{VALUE}}; color: {{VALUE}}',
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.ratings-filter .rating-options-wrap .checkbox-switch input:checked + .rating-stars' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_rating_active_color',
			[
				'label'     => esc_html__( 'Filter Form Icons Active Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.ratings-filter .rating-options-wrap .checkbox-switch input:checked + .rating-stars' => 'color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_verification_switch_bg',
			[
				'label'     => esc_html__( 'Verification Switch Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.verified-agents .button-switch .button-check' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_verification_switch_color',
			[
				'label'     => esc_html__( 'Verification Switch Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.verified-agents .button-switch .button-check span' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_verification_switch_active_bg',
			[
				'label'     => esc_html__( 'Verification Switch Active Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.verified-agents .button-switch input:checked+.button-check' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_verification_switch_active_color',
			[
				'label'     => esc_html__( 'Verification Switch Active Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field.verified-agents .button-switch input:checked+.button-check span' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				]
			]
		);

		$this->add_control(
			'filter_buttons_border_color',
			[
				'label'     => esc_html__( 'Buttons Top Border Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-buttons' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				]
			]
		);

        $this->add_control(
			'filter_form_button_background_color',
			[
				'label'     => esc_html__( 'Filter Button Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-posts-button' => 'border-color: {{VALUE}}; background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

        $this->add_control(
			'filter_form_button_color',
			[
				'label'     => esc_html__( 'Filter Button Text Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-posts-button' => 'color: {{VALUE}};'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_form_button_hover_background_color',
			[
				'label'     => esc_html__( 'Filter Button Hover Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-posts-button:hover' => 'border-color: {{VALUE}}; background-color: {{VALUE}}'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

        $this->add_control(
			'filter_form_button_hover_color',
			[
				'label'     => esc_html__( 'Filter Button Hover Text Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-posts-button:hover' => 'color: {{VALUE}};'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

        $this->add_control(
			'filter_form_reset_button_color',
			[
				'label'     => esc_html__( 'Clear Filters Button Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-buttons input.clear-filters-button' => 'color: {{VALUE}};'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
			]
		);

        $this->add_control(
			'filter_form_reset_button_bg_color',
			[
				'label'     => esc_html__( 'Clear Filters Button Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .filter-buttons input.clear-filters-button:hover' => 'color: {{VALUE}};'
				],
				'condition' => [
					'show_posts_filter' => 'yes',
				],
                'separator' => 'after'
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Agent Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'agency_description_prefix',
			[
				'label'     => esc_html__( 'Related Agency Prefix', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-description span' => 'color: {{VALUE}}',
				],
				'condition' => [
					'select-post-type' => 'agent',
				],
			]
		);
		$this->add_control(
			'agency_description_name',
			[
				'label'     => esc_html__( 'Agency Name', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-description a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'select-post-type' => 'agent',
				],
			]
		);
		$this->add_control(
			'agency_listed_properties',
			[
				'label'     => esc_html__( 'Listed Properties', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-listing-count' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_social_colors',
			[
				'label'     => esc_html__( 'Agent/Agency Social Links', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-agent-card .agent-social-links'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .single-agency-card .agency-social-links' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_social_colors_hover',
			[
				'label'     => esc_html__( 'Agent/Agency Social Links Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-agent-card .agent-social-links:hover'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .single-agency-card .agency-social-links:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_meta_icon',
			[
				'label'     => esc_html__( 'Agent/Agency Meta Icon', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh-ultra-dark' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_meta_label',
			[
				'label'     => esc_html__( 'Agent/Agency Meta Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-contact-item-label' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_meta_value',
			[
				'label'     => esc_html__( 'Agent/Agency Meta Value', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-contact-item-inner a'    => 'color: {{VALUE}}',
					'{{WRAPPER}} .agent-contact-item-inner span' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_meta_value_hover',
			[
				'label'     => esc_html__( 'Agent/Agency Meta Value Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-contact-item-inner a:hover'  => 'color: {{VALUE}}',
					'{{WRAPPER}} .agency-contact-item-inner a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_desc_heading',
			[
				'label'     => esc_html__( 'Agent/Agency Description Heading', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-content-heading' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'agent_description',
			[
				'label'     => esc_html__( 'Agent/Agency Description', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-content p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'area_box_shadow',
				'label'    => esc_html__( 'Area Box Shadow', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-single-agent-card',
			]
		);

		$this->add_control(
			'progress_stats_heading_color',
			[
				'label'     => esc_html__( 'Progress & Stats Heading', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .stats-charts-wrap > h3' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_control(
			'progress_stats_labels_color',
			[
				'label'     => esc_html__( 'Progress & Stats Labels', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .stats-charts-wrap .stats-wrapper .tax-stats > h3' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_control(
			'progress_stats_details_color',
			[
				'label'     => esc_html__( 'Progress & Stats Details', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .stats-charts-wrap .stats-wrapper .tax-stats ul li' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_control(
			'progress_stats_details_strong_color',
			[
				'label'     => esc_html__( 'Progress & Stats Details Strong', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .stats-charts-wrap .stats-wrapper .tax-stats ul li strong' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'typography_section',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_form_title',
				'label'    => esc_html__( 'Filter Form Title', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-agents-search-form > h4',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_form_label',
				'label'    => esc_html__( 'Filter Form Label', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field > label',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_form_input_field',
				'label'    => esc_html__( 'Filter Form Input Fields', 'realhomes-elementor-addon' ),
				'selector' => '
				    {{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field input[type=text],
				    {{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field input[type=number],
				    {{WRAPPER}} .rhea-agents-search-form .rhea-agent-filters-form .rhea-filter-field .dropdown .dropdown-toggle
				'
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Agent Label', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agency_prefix_typography',
				'label'    => esc_html__( 'Agency Prefix', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-description span',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agency_name_typography',
				'label'    => esc_html__( 'Agency Name', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-description a',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agency_listed_properties_typography',
				'label'    => esc_html__( 'Agent/Agency Listed Properties', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-listing-count',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agent_meta_label_typography',
				'label'    => esc_html__( 'Agent/Agency Meta Label', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-contact-item-label',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agent_meta_value_typography',
				'label'    => esc_html__( 'Agent/Agency Meta Value', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-contact-item-inner a, {{WRAPPER}} .agent-contact-item-inner span',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agent_desc_heading_typography',
				'label'    => esc_html__( 'Agent/Agency Description Heading', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-content-heading',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agent_description_typography',
				'label'    => esc_html__( 'Agent/Agency Description', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .agent-content p',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'progress_stats_typography',
				'label'     => esc_html__( 'Progress & Stats', 'realhomes-elementor-addon' ),
				'selector'  => '{{WRAPPER}} .stats-charts-wrap > h3',
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'progress_stats_labels_typography',
				'label'     => esc_html__( 'Progress & Stats Labels', 'realhomes-elementor-addon' ),
				'selector'  => '{{WRAPPER}} .stats-charts-wrap .stats-wrapper .tax-stats > h3',
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'progress_stats_details_typography',
				'label'     => esc_html__( 'Progress & Stats Details', 'realhomes-elementor-addon' ),
				'selector'  => '{{WRAPPER}} .stats-charts-wrap .stats-wrapper .tax-stats ul li',
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'progress_stats_details_strong_typography',
				'label'     => esc_html__( 'Progress & Stats Details Strong', 'realhomes-elementor-addon' ),
				'selector'  => '{{WRAPPER}} .stats-charts-wrap .stats-wrapper .tax-stats ul li strong',
				'condition' => [
					'show_progress_stats' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		global $settings;
		$settings                                 = $this->get_settings_for_display();
		$widget_id                                = $this->get_id();
		$card_options['select-post-type']                = $settings['select-post-type'];
		$card_options['all_agent_agency_posts']   = $settings['all_agent_agency_posts'];
		$card_options['agent_id']                 = $settings['agent_id'];
		$card_options['show_company_name']        = $settings['show_company_name'];
		$card_options['agency_prefix']            = $settings['agency_prefix'];
		$card_options['show_property_counter']    = $settings['show_property_counter'];
		$card_options['show_description']         = $settings['show_description'];
		$card_options['description_heading']      = $settings['description_heading'];
		$card_options['show_progress_stats']      = $settings['show_progress_stats'];
		$card_options['progress_stats_heading']   = $settings['progress_stats_heading'];
		$card_options['property_locations_label'] = $settings['property_locations_label'];
		$card_options['property_types_label']     = $settings['property_types_label'];
		$card_options['property_status_label']    = $settings['property_status_label'];

		if ( 'yes' === $settings['all_agent_agency_posts'] ) {

			if ( 'yes' == $settings['show_posts_filter'] ) {
				?>
                <div class="rhea-agents-search-form">
                    <?php
                    if ( ! empty( $settings['filters_form_title'] ) ) {
                        ?>
                        <h4><?php echo esc_html( $settings['filters_form_title'] ); ?></h4>
                        <?php
                    }
                    ?>
                    <form id="agent-filters-<?php echo esc_attr( $widget_id ); ?>" class="rhea-agent-filters-form">
						<div class="main-field">
                            <div class="rhea-filter-field post-title-filter">
                                <label for="rhea-filter-name-<?php echo esc_attr( $widget_id ); ?>"><?php esc_html_e( 'Name/Title', 'realhomes-elementor-addon' ); ?></label>
                                <input type="text" id="rhea-filter-name-<?php echo esc_attr( $widget_id ); ?>" class="posts-filter-name" name="filter-name" placeholder="<?php esc_html_e( 'Enter Name', 'realhomes-elementor-addon' ); ?>">
                            </div>
                            <?php
                            $necessary_keys = array(
	                            'show_posts_filter_property_count',
	                            'show_posts_filter_location',
	                            'show_posts_sorting_options',
	                            'show_verified_posts_filter',
	                            'show_ratings_filter'
                            );
                            if ( rhea_bulk_settings_check( $settings, $necessary_keys, 'yes', 'or' ) ) {
                                ?>
                                <div class="more-filters">
		                            <?php rhea_safe_include_svg( '/icons/filters-icon.svg' ); ?>
                                    <span class="tool-tip"><?php esc_html_e( 'More Filters', 'realhomes-elementor-addon' ); ?></span>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="filter-button">
                                <input class="submit-button rh-btn rh-btn-primary filter-posts-button" type="submit" value="Filter">
                            </div>
                        </div>

                        <div class="dropdown-fields-wrap">
                            <div class="dropdown-fields">
	                            <?php
	                            if ( 'yes' == $settings['show_posts_filter_property_count'] ) {
		                            ?>
                                    <div class="rhea-filter-field">
                                        <label for="rhea-property-count-<?php echo esc_attr( $widget_id ); ?>"><?php esc_html_e( 'Number of properties', 'realhomes-elementor-addon' ); ?></label>
                                        <input type="number" id="rhea-property-count-<?php echo esc_attr( $widget_id ); ?>" class="posts-filter-property-count" name="property-count" placeholder="<?php esc_html_e( 'Select a number', 'realhomes-elementor-addon' ); ?>">
                                    </div>
		                            <?php
	                            }

	                            if ( 'yes' == $settings['show_posts_filter_location'] ) {

		                            $property_locations = get_terms( array( 'taxonomy' => $card_options['select-post-type'] . '-location' ) );

		                            if ( ! is_wp_error( $property_locations ) && is_array( $property_locations ) && 0 < count( $property_locations ) ) {
			                            ?>
                                        <div class="rhea-filter-field rh_prop_search__selectwrap">
                                            <label for="rhea-filter-location-<?php echo esc_attr( $widget_id ); ?>"><?php esc_html_e( 'Location', 'realhomes-elementor-addon' ); ?></label>
                                            <select name="location" id="rhea-filter-location-<?php echo esc_attr( $widget_id ); ?>" class="post-filter-location inspiry_select_picker_trigger" data-size="5">
                                                <option value=""><?php esc_html_e( 'Select Location', 'realhomes-elementor-addon' ); ?></option>
					                            <?php
					                            foreach ( $property_locations as $location ) {
						                            ?>
                                                    <option value="<?php echo esc_attr( $location->slug ); ?>"><?php echo esc_html( $location->name ); ?></option>
						                            <?php
					                            }
					                            ?>
                                            </select>
                                        </div>
			                            <?php
		                            }
	                            }

	                            if ( 'yes' == $settings['show_posts_sorting_options'] ) {
		                            ?>
                                    <div class="rhea-filter-field rh_prop_search__selectwrap">
                                        <label for="rhea-sort-posts-<?php echo esc_attr( $widget_id ); ?>"><?php esc_html_e( 'Sort Listings', 'realhomes-elementor-addon' ); ?></label>
                                        <select name="sort-posts" id="rhea-sort-posts-<?php echo esc_attr( $widget_id ); ?>" class="posts-sort-by inspiry_select_picker_trigger">
				                            <?php
				                            if ( $card_options['select-post-type'] === 'agent' ) {
					                            inspiry_agent_sort_options();
				                            } else {
					                            inspiry_agency_sort_options();
				                            }
				                            ?>
                                        </select>
                                    </div>
		                            <?php
	                            }

	                            if ( 'yes' == $settings['show_verified_posts_filter'] ) {
		                            ?>
                                    <div class="rhea-filter-field verified-agents">
                                        <label for="post-verification-<?php echo esc_attr( $widget_id ); ?>"><?php esc_html_e( 'Verified Only', 'realhomes-elementor-addon' ); ?></label>
                                        <label class="button-switch" for="post-verification-<?php echo esc_attr( $widget_id ); ?>">
                                            <input type="checkbox" id="post-verification-<?php echo esc_attr( $widget_id ); ?>" class="post-verification" name="verified-agents" value="true">
                                            <div class="button-check">
                                                <span></span>
                                            </div>
                                        </label>
                                    </div>
		                            <?php
	                            }

	                            if ( 'yes' == $settings['show_ratings_filter'] ) {
		                            ?>
                                    <div class="rhea-filter-field ratings-filter">
                                        <label><?php esc_html_e( 'Ratings', 'realhomes-elementor-addon' ); ?></label>
                                        <div class="rating-options-wrap">
                                            <label class="checkbox-switch" for="rating-1s-<?php echo esc_attr( $widget_id ); ?>">
                                                <input type="radio" id="rating-1s-<?php echo esc_attr( $widget_id ); ?>" class="post-ratings rating-1s" name="ratings-filter-<?php echo esc_attr( $widget_id ); ?>" value="1">
                                                <span class="rating-stars">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            </label>
                                            <label class="checkbox-switch" for="rating-2s-<?php echo esc_attr( $widget_id ); ?>">
                                                <input type="radio" id="rating-2s-<?php echo esc_attr( $widget_id ); ?>" class="post-ratings rating-2s" name="ratings-filter-<?php echo esc_attr( $widget_id ); ?>" value="2">
                                                <span class="rating-stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            </label>
                                            <label class="checkbox-switch" for="rating-3s-<?php echo esc_attr( $widget_id ); ?>">
                                                <input type="radio" id="rating-3s-<?php echo esc_attr( $widget_id ); ?>" class="post-ratings rating-3s" name="ratings-filter-<?php echo esc_attr( $widget_id ); ?>" value="3">
                                                <span class="rating-stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            </label>
                                            <label class="checkbox-switch" for="rating-4s-<?php echo esc_attr( $widget_id ); ?>">
                                                <input type="radio" id="rating-4s-<?php echo esc_attr( $widget_id ); ?>" class="post-ratings rating-4s" name="ratings-filter-<?php echo esc_attr( $widget_id ); ?>" value="4">
                                                <span class="rating-stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            </label>
                                            <label class="checkbox-switch" for="rating-5s-<?php echo esc_attr( $widget_id ); ?>">
                                                <input type="radio" id="rating-5s-<?php echo esc_attr( $widget_id ); ?>" class="post-ratings rating-5s" name="ratings-filter-<?php echo esc_attr( $widget_id ); ?>" value="5">
                                                <span class="rating-stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
		                            <?php
	                            }
	                            ?>
                            </div>
                            <div class="filter-buttons">
                                <input class="submit-button rh-btn rh-btn-primary clear-filters-button" type="reset" value="<?php esc_html_e( 'Clear Filters', 'realhomes-elementor-addon' ); ?>">
                                <input class="submit-button rh-btn rh-btn-primary filter-posts-button" type="submit" value="<?php esc_html_e( 'Filter', 'realhomes-elementor-addon' ); ?>">
                            </div>
                        </div>
                        <input type="hidden" name="post_type" class="rhea-post-type" value="<?php echo esc_attr( $settings['select-post-type'] ); ?>">
                    </form>
                </div>
				<?php
			}
			?>
            <div class="rhea-agent-agency-posts-wrap" data-card-options="<?php echo esc_attr( wp_json_encode( $card_options ) ); ?>">
                <?php
				rhea_get_template_part( 'elementor/widgets/agent/partials/agent-agency-loop' );
				?>
            </div>
			<?php
		} else {
			rhea_get_template_part( 'elementor/widgets/agent/partials/agent-agency-card' );
		}
	}
}
