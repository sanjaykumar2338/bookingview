<?php
/**
 * Mortgage Calculator Elementor Widget
 *
 * @since 2.3.3
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Mortgage_Calculator extends \Elementor\Widget_Base {
	public function get_name() {
		return 'rhea-mortgage-calculator';
	}

	public function get_title() {
		return esc_html__( 'Mortgage Calculator', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-number-field';
	}

	public function get_categories() {
		return [ 'real-homes' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'settings_section',
			[
				'label' => esc_html__( 'Settings', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'tenure',
			[
				'label'       => esc_html__( 'Tenure', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Provide the comma separated numbers list. E.g: 5,10,15,20,25,30', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '5,10,15,20,25,30',
			]
		);
		$this->add_control(
			'default_tenure',
			[
				'label'       => esc_html__( 'Default Tenure', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Set the default tenure value. E.g: 5', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
			]
		);
		$this->add_control(
			'interest_rate',
			[
				'label'       => esc_html__( 'Interest Rate', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Default interest rate value. E.g: 3.5', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '3.5',
			]
		);
		$this->add_control(
			'default_price',
			[
				'label'       => esc_html__( 'Default Price', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Set the default price value.', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
			]
		);
		$this->add_control(
			'default_down_payment',
			[
				'label'       => esc_html__( 'Default Down Payment ', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Set the default down payment percentage value. ', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
			]
		);
		$this->add_control(
			'tax',
			[
				'label'       => esc_html__( 'Property Taxes', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Provide monthly property tax amount. It will be displayed in the mortgage calculator only.', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
			]
		);
		$this->add_control(
			'additional_fee',
			[
				'label'       => esc_html__( 'Additional Fee', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Provide any monthly additional fee. It will be displayed in the mortgage calculator only.', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
			]
		);
		$this->add_control(
			'graph_type',
			[
				'label'   => esc_html__( 'Graph Type', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => esc_html__( 'Circle', 'realhomes-elementor-addon' ),
					'bar'    => esc_html__( 'Bar', 'realhomes-elementor-addon' ),
				],
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'fields_labels',
			[
				'label' => esc_html__( 'Fields Labels', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'tenure_field_label',
			[
				'label'       => esc_html__( 'Tenure Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Tenure', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'interest_rate_field_label',
			[
				'label'       => esc_html__( 'Interest Rate Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Interest', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'price_field_label',
			[
				'label'       => esc_html__( 'Price Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Home Price', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'down_payment_field_label',
			[
				'label'       => esc_html__( 'Down Payment Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Down Payment', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'principle_and_interest_field_label',
			[
				'label'       => esc_html__( 'Principle and Interest Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Principle and Interest', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'tax_field_label',
			[
				'label'       => esc_html__( 'Property Taxes Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Property Taxes', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'additional_fee_field_label',
			[
				'label'       => esc_html__( 'Additional Fee Field Label', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Additional Dues', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'cost_prefix',
			[
				'label'       => esc_html__( 'Cost Prefix', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'per month', 'realhomes-elementor-addon' ),
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'features_colors_section',
			[
				'label' => esc_html__( 'Basic', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'section-border-radius',
			[
				'label'      => esc_html__( 'Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'fields-border-radius',
			[
				'label'      => esc_html__( 'Fields Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh-mc-slider-fields' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'dropdown-field-border-radius',
			[
				'label'      => esc_html__( 'Dropdown Field Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'cost-section-border-radius',
			[
				'label'      => esc_html__( 'Cost Section Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'styles_section',
			[
				'label' => esc_html__( 'Colors', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'bg-color',
			[
				'label'     => esc_html__( 'Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-label-color',
			[
				'label'     => esc_html__( 'Fields Labels Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-fields-color',
			[
				'label'     => esc_html__( 'Fields Values Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field input[type=text]'                                  => 'color: {{VALUE}};',
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field .rh_form__item .bootstrap-select .dropdown-toggle' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bootstrap-select.rh-ultra-select-dropdown .dropdown-menu li a'                                        => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-icon-color',
			[
				'label'     => esc_html__( 'Fields Icons Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh-ultra-dark'        => 'fill: {{VALUE}};',
					'{{WRAPPER}} .rh-ultra-stroke-dark' => 'stroke: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-dropdown-select-color',
			[
				'label'     => esc_html__( 'Dropdown Item Selection Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bootstrap-select.rh-ultra-select-dropdown .dropdown-menu li.selected .text'           => 'color: {{VALUE}};',
					'{{WRAPPER}} .bootstrap-select.rh-ultra-select-dropdown .dropdown-menu li.selected span.check-mark' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-dropdown-hover-color',
			[
				'label'     => esc_html__( 'Dropdown Item Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bootstrap-select.rh-ultra-select-dropdown .dropdown-menu li:hover' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-scrollbar-color',
			[
				'label'     => esc_html__( 'Dropdown Scroll Bar Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bootstrap-select .dropdown-menu .open' => 'scrollbar-color: {{VALUE}} #fff;'
				],
			]
		);
		$this->add_control(
			'calculator-slider-handle-color',
			[
				'label'     => esc_html__( 'Slider Handle Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[type=range]::-webkit-slider-thumb'                                                                          => 'background: {{VALUE}};',
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field .rh_form__item .bootstrap-select .dropdown-toggle span.caret' => 'border-top-color: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'calculator-slider-track-color',
			[
				'label'     => esc_html__( 'Slider Track Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[type=range]::-webkit-slider-runnable-track'                                                                    => 'background: {{VALUE}};',
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field .rh_form__item .bootstrap-select .dropdown-toggle span.bs-caret' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'fields_box_shadow',
				'label'    => esc_html__( 'Fields Box Shadow', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh-mc-slider-fields',
			]
		);
		$this->add_control(
			'calculator-color-one',
			[
				'label'     => esc_html__( 'Principle & Interest Graph', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul .mc_cost_interest:before, {{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph ul li.mc_graph_interest' => 'background: {{VALUE}};',
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_graph_svg .mc_graph_interest'                                                         => 'stroke: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-color-two',
			[
				'label'     => esc_html__( 'Property Taxes Graph', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul .mc_cost_tax:before, {{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph ul li.mc_graph_tax' => 'background: {{VALUE}};',
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_graph_svg .mc_graph_tax'                                                    => 'stroke: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-color-three',
			[
				'label'     => esc_html__( 'Additional Fee Graph', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul .mc_cost_hoa:before, {{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph ul li.mc_graph_hoa' => 'background: {{VALUE}};',
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_graph_svg .mc_graph_hoa'                                                    => 'stroke: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-terms-value-color',
			[
				'label'     => esc_html__( 'Tenure & Interest Value', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mc_term_interest .rh-mc-value'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .mc_term_interest .rh-mc-percent' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-terms-label-color',
			[
				'label'     => esc_html__( 'Tenure & Interest Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mc_term_interest .rh-mc-label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-cost-label-color',
			[
				'label'     => esc_html__( 'Cost Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-cost-value-color',
			[
				'label'     => esc_html__( 'Cost Value', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul li span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-graph-label-color',
			[
				'label'     => esc_html__( 'Graph Label', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_cost_over_graph' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'calculator-graph-value-color',
			[
				'label'     => esc_html__( 'Graph Value', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_cost_over_graph strong' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cost_box_shadow',
				'label'    => esc_html__( 'Cost Section Box Shadow', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul li',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'mc-typography',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_fields_label_typography',
				'label'    => esc_html__( 'Fields Label', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field label',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_fields_typography',
				'label'    => esc_html__( 'Text Fields', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .rh_mc_field input[type=text]',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_select_label_typography',
				'label'    => esc_html__( 'Select Field', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .bootstrap-select.rh-ultra-select-dropdown > .dropdown-toggle',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_dropdown_label_typography',
				'label'    => esc_html__( 'Select Dropdown', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .bootstrap-select.show-tick .dropdown-menu li a span.text',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_terms_value_typography',
				'label'    => esc_html__( 'Tenure & Interest Value', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mc_term_interest .rh-mc-value,{{WRAPPER}} .mc_term_interest .rh-mc-percent',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_terms_label_typography',
				'label'    => esc_html__( 'Tenure & Interest Label', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .mc_term_interest .rh-mc-label',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_cost_label_typography',
				'label'    => esc_html__( 'Costs Label', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul li',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_cost_value_typography',
				'label'    => esc_html__( 'Costs Value', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .mc_cost ul li span',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_graph_label_typography',
				'label'    => esc_html__( 'Graph Label', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_cost_over_graph',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mc_graph_value_typography',
				'label'    => esc_html__( 'Graph Value', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_property__mc_wrap .rh_property__mc .mc_cost_graph_circle .mc_cost_over_graph strong',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$labels                   = array();
		$labels['term']           = $settings['tenure_field_label'];
		$labels['interest']       = $settings['interest_rate_field_label'];
		$labels['price']          = $settings['price_field_label'];
		$labels['down_payment']   = $settings['down_payment_field_label'];
		$labels['principle']      = $settings['principle_and_interest_field_label'];
		$labels['tax']            = $settings['tax_field_label'];
		$labels['additional_fee'] = $settings['additional_fee_field_label'];
		$labels['cost_prefix']    = $settings['cost_prefix'];

		$mc_data                 = array();
		$mc_data['term']         = $settings['default_tenure'];
		$mc_data['interest']     = floatval( $settings['interest_rate'] );
		$mc_data['down_payment'] = intval( $settings['default_down_payment'] );
		$mc_data['price']        = intval( $settings['default_price'] );
		$mc_data['tax']          = intval( $settings['tax'] );
		$mc_data['fee']          = intval( $settings['additional_fee'] );

		if ( ! empty( $settings['tenure'] ) ) {
			$mc_data['terms'] = array_map( 'intval', explode( ',', $settings['tenure'] ) );
		} else {
			$mc_data['terms'] = array( '5', '10', '15', '20', '25', '30' );
		}
		?>
        <div class="rhea-mortgage-calculator-wrapper rh_property__mc_wrap">
            <div class="rh_property__mc clearfix">
				<?php
				// Currency conversion in case Currency Switcher is enabled.
				if ( function_exists( 'realhomes_currency_switcher_enabled' ) && realhomes_currency_switcher_enabled() ) {
					$base_currency    = realhomes_get_base_currency();
					$current_currency = realhomes_get_current_currency();

					$mc_data['price_converted'] = realhomes_convert_currency( $mc_data['price'], $base_currency, $current_currency );
					$mc_data['tax_converted']   = realhomes_convert_currency( $mc_data['tax'], $base_currency, $current_currency );
					$mc_data['fee_converted']   = realhomes_convert_currency( $mc_data['fee'], $base_currency, $current_currency );

					$currencies_data   = realhomes_get_currencies_data();
					$currency_sign     = $currencies_data[ $current_currency ]['symbol'];
					$currency_position = $currencies_data[ $current_currency ]['position'];
				} else {
					$mc_data['price_converted'] = $mc_data['price'];
					$mc_data['tax_converted']   = $mc_data['tax'];
					$mc_data['fee_converted']   = $mc_data['fee'];
					$currency_sign              = ere_get_currency_sign();
					$currency_position          = get_option( 'theme_currency_position', 'before' );
				}
				?>
                <!-- Calculator left side -->
                <div class="mc_left_side">

                    <!-- Term -->
                    <div class="rh_mc_field">
                        <label for="mc_term"><?php echo esc_html( $labels['term'] ); ?></label>
                        <div class="rh_form__item">
							<?php inspiry_safe_include_svg( '/ultra/icons/calendar.svg', '/assets/' ); ?>
                            <select id="mc_term" name="mc_term" class="rh-ultra-select-dropdown mc_term inspiry_select_picker_trigger show-tick">
								<?php
								foreach ( $mc_data['terms'] as $mc_term ) {
									printf( '<option value="%s" %s>%s %s</option>', esc_attr( $mc_term ), selected( $mc_data['term'], $mc_term, false ), esc_html( $mc_term ), esc_html__( 'Years Fixed', 'realhomes-elementor-addon' ) );
								}
								?>
                            </select>
                        </div>
                    </div>

                    <!-- Interest Rate -->
                    <div class="rh_mc_field">
                        <label for="mc_interest"><?php echo esc_html( $labels['interest'] ); ?></label>
                        <div class="rh_form__item rh-mc-slider-fields">
							<?php inspiry_safe_include_svg( '/ultra/icons/calculator.svg', '/assets/' ); ?>
                            <input id="mc_interest" class="mc_interset" type="text" value="<?php echo esc_attr( $mc_data['interest'] ); ?>%">
                            <input class="mc_interset_slider" type="range" min="0" max="50" step="0.1" value="<?php echo esc_attr( $mc_data['interest'] ); ?>">
                        </div>
                    </div>

                    <!-- Home Price -->
                    <div class="rh_mc_field">
                        <label for="mc_home_price"><?php echo esc_html( $labels['price'] ); ?></label>
                        <div class="rh_form__item rh-mc-slider-fields">
							<?php inspiry_safe_include_svg( '/ultra/icons/money.svg', '/assets/' ); ?>
                            <input id="mc_home_price" class="mc_home_price" type="text" value="<?php echo esc_html( $mc_data['price_converted'] ); ?>">
							<?php
							$price_slider_max = esc_html( $mc_data['price_converted'] * 3 );
							if ( 200000 > $price_slider_max ) {
								$price_slider_max = 200000;
							}
							?>
                            <input class="mc_home_price_slider" type="range" min="100" max="<?php echo esc_html( $price_slider_max ); ?>" step="1" value="<?php echo esc_html( $mc_data['price_converted'] ); ?>">
                            <input class="mc_currency_sign" type="hidden" value="<?php echo esc_attr( $currency_sign ); ?>">
                            <input class="mc_sign_position" type="hidden" value="<?php echo esc_attr( $currency_position ); ?>">
                        </div>
                    </div>

                    <!-- Down Payment -->
                    <div class="rh_mc_field">
                        <label for="mc_downpayment"><?php echo esc_html( $labels['down_payment'] ); ?></label>
                        <div class="rh_form__item rh-mc-slider-fields">
							<?php inspiry_safe_include_svg( '/ultra/icons/money.svg', '/assets/' ); ?>
                            <div class="rh-mc-fields-half">
                                <input id="mc_downpayment" class="mc_downpayment" type="text" value="<?php echo esc_html( ( $mc_data['price_converted'] * $mc_data['down_payment'] ) / 100 ); ?>">
                                <input class="mc_downpayment_percent" type="text" value="<?php echo esc_html( $mc_data['down_payment'] ); ?>%">
                            </div>
                            <input class="mc_downpayment_slider" type="range" min="0" max="80" step="1" value="<?php echo esc_html( $mc_data['down_payment'] ); ?>">
                        </div>
                    </div>

                </div><!-- End of the left side -->

				<?php $graph_type = $settings['graph_type']; ?>
                <div class="mc_right_side <?php echo 'graph_' . esc_attr( $graph_type ); ?>">
					<?php
					if ( 'circle' === $graph_type ) {
						?>
                        <div class="mc_term_interest">
                            <div class="rh-mc-label-box">
                                <span class="mc_term_value rh-mc-value"><?php echo esc_html( $mc_data['term'] ); ?></span>
                                <span class="rh-mc-label"><?php esc_html_e( 'Years Fixed', 'realhomes-elementor-addon' ); ?></span>
                            </div>
                            <div class="rh-mc-label-box">
                                <span class="mc_interest_value rh-mc-value test"><?php echo esc_attr( $mc_data['interest'] ); ?></span><span class="rh-mc-percent">%</span>
                                <span class="rh-mc-label"><?php echo esc_html( $labels['interest'] ); ?></span>
                            </div>
                        </div>
                        <div class="mc_cost_graph_circle">
                            <svg class="mc_graph_svg" width="220" height="220" viewPort="0 0 100 100">
                                <circle r="90" cx="110" cy="110" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                                <circle class="mc_graph_hoa" r="90" cx="110" cy="110" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                                <circle class="mc_graph_tax" r="90" cx="110" cy="110" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                                <circle class="mc_graph_interest" r="90" cx="110" cy="110" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                            </svg>
                            <div class="mc_cost_over_graph" data-cost-prefix=" <?php echo esc_html( $labels['cost_prefix'] ); ?>">
								<?php
								// Temporary Text Added for Editor Side only
								if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
									?>
                                    <strong>$9,999</strong><?php esc_html_e( 'per month', 'realhomes-elementor-addon' ); ?><?php
								}
								?>
                            </div>
                        </div>
						<?php
					} else {
						?>
                        <div class="mc_cost_total"><span></span> <?php echo esc_html( $labels['cost_prefix'] ); ?></div>
                        <div class="mc_term_interest">
                            <span class="mc_term_value"><?php echo esc_html( $mc_data['term'] ); ?></span>
							<?php esc_html_e( 'Years Fixed', 'realhomes-elementor-addon' ); ?>,
                            <span class="mc_interest_value"><?php echo esc_attr( $mc_data['interest'] ); ?></span><span>%</span>
							<?php echo esc_html( $labels['interest'] ); ?>
                        </div>
                        <div class="mc_cost_graph">
                            <ul class="clearfix">
                                <li class="mc_graph_interest"><span></span></li>
                                <li class="mc_graph_tax"><span></span></li>
                                <li class="mc_graph_hoa"><span></span></li>
                            </ul>
                        </div>
						<?php
					}
					?>
                    <div class="mc_cost">
                        <ul>
                            <li class="mc_cost_interest"><?php echo esc_html( $labels['principle'] ); ?> <span></span></li>
							<?php
							if ( ! empty( $mc_data['tax'] ) ) {
								?>
                                <li class="mc_cost_tax">
									<?php echo esc_html( $labels['tax'] ); ?>
                                    <span><?php echo esc_html( ere_format_amount( $mc_data['tax'] ) ); ?></span>
                                </li>
								<?php
							}

							if ( ! empty( $mc_data['fee'] ) ) {
								?>
                                <li class="mc_cost_hoa">
									<?php echo esc_html( $labels['additional_fee'] ); ?>
                                    <span><?php echo esc_html( ere_format_amount( $mc_data['fee'] ) ); ?></span>
                                </li>
								<?php
							}
							?>
                        </ul>
                        <input class="mc_cost_tax_value" type="hidden" value="<?php echo esc_html( $mc_data['tax_converted'] ); ?>">
                        <input class="mc_cost_hoa_value" type="hidden" value="<?php echo esc_html( $mc_data['fee_converted'] ); ?>">
                    </div>
                </div><!-- End of the right side -->
            </div>
        </div>
		<?php
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
            <script type="application/javascript">
                rheaSelectPicker( '.inspiry_select_picker_trigger' );
            </script>
			<?php
		}
	}
}