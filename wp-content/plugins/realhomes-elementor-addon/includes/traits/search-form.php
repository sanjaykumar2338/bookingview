<?php

trait RHEASearchFormTrait {
	public function query_parameter_locations() {
		$parameter_locations = array();

		if ( function_exists( 'inspiry_get_location_select_names' ) ) {
			$location_names = inspiry_get_location_select_names();
			if ( 0 < count( $location_names ) ) {
				foreach ( $location_names as $location ) {
					if ( isset( $_GET[ $location ] ) ) {
						$parameter_locations[ $location ] = $_GET[ $location ];
					}
				}
			}
		}

		return $parameter_locations;
	}

	public function search_template_options() {
		if ( function_exists( 'inspiry_pages' ) ) {
			$search_pages_args = array(
				'meta_query' => array(
					'relation' => 'or',
					array(
						'key'   => '_wp_page_template',
						'value' => 'templates/properties-search.php',
					),
				),
			);

			return inspiry_pages( $search_pages_args );
		}

		return '';
	}

	public function Basic_Common_Settings() {
		$this->start_controls_section(
			'rhea_search_basic_settings',
			[
				'label' => esc_html__( 'Basic Settings', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'rhea_select_search_template',
			[
				'label'       => esc_html__( 'Select Search Template', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'If no search template is selected, "RealHomes > Customize Settings > Property Search > Properties Search Page" settings will be applied by default   ', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $this->search_template_options(),
			]
		);

		$this->add_control(
			'rhea_top_field_count',
			[
				'label'       => esc_html__( 'Top Fields To Display', 'realhomes-elementor-addon' ),
				'description' => esc_html__( 'Select number of fields to display in top bar' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '3',
				'options'     => array(
					'1' => esc_html__( 'One', 'realhomes-elementor-addon' ),
					'2' => esc_html__( 'Two', 'realhomes-elementor-addon' ),
					'3' => esc_html__( 'Three', 'realhomes-elementor-addon' ),
					'4' => esc_html__( 'Four', 'realhomes-elementor-addon' ),
					'5' => esc_html__( 'Five', 'realhomes-elementor-addon' ),
					'6' => esc_html__( 'Six', 'realhomes-elementor-addon' ),
				),
			]
		);

		$this->add_control(
			'rhea_default_advance_state',
			[
				'label'   => esc_html__( 'Advance Fields Default State', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'collapsed',
				'options' => array(
					'collapsed' => esc_html__( 'Collapsed', 'realhomes-elementor-addon' ),
					'open'      => esc_html__( 'Open', 'realhomes-elementor-addon' ),
				),
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'        => esc_html__( 'Show Labels', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'show_advance_fields',
			[
				'label'        => esc_html__( 'Show Advance Fields Button', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'search_button_label',
			[
				'label'   => esc_html__( 'Search Button', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Search', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'rhea_search_button_position',
			[
				'label'        => esc_html__( 'Search Button AT Bottom? ', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'rhea_button_animate',
			[
				'label'        => esc_html__( 'Animate Search Buttons? ', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'rhea_advance_button_animate',
			[
				'label'        => esc_html__( 'Animate Advance Search Buttons? ', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_advance_fields' => 'yes',
				],
			]
		);


		$this->add_control(
			'show_advance_features',
			[
				'label'        => esc_html__( 'Show More Features ', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'advance_features_text',
			[
				'label'   => esc_html__( 'Advance Features Text', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Looking for certain features', 'realhomes-elementor-addon' ),
			]
		);
		$this->end_controls_section();
	}
}

trait RHEASearchFormSettings {
	public function SF_fields_icons( $show_icon_key, $icon_key, $field_selector = '' ) {
		$get_field_selector = ! empty( $field_selector ) ? '.' . $field_selector : '';
		$this->add_control(
			$show_icon_key,
			[
				'label'        => esc_html__( 'Show Icon', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			$icon_key,
			[
				'label'     => esc_html__( 'Icon', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					$show_icon_key => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			$icon_key . '_icon_size',
			[
				'label'      => esc_html__( 'Icon Size (%)', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} ' . $get_field_selector . ' .enable-icon i.field-icon'    => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $get_field_selector . ' .enable-icon span.field-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					$show_icon_key => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			$icon_key . '_icon_color',
			[
				'label'     => esc_html__( 'FontAwesome Icon Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ' . $get_field_selector . ' .enable-icon i.field-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					$show_icon_key => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			$icon_key . '_icon_fill_color',
			[
				'label'     => esc_html__( 'SVG Icon Fill Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ' . $get_field_selector . ' .enable-icon .field-icon svg path' => 'fill: {{VALUE}}',
				],
				'condition' => [
					$show_icon_key => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			$icon_key . '_icon_stroke_color',
			[
				'label'     => esc_html__( 'SVG Icon Stroke Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ' . $get_field_selector . ' .enable-icon .field-icon svg path' => 'stroke: {{VALUE}}',
				],
				'condition' => [
					$show_icon_key => 'yes',
				],
			]
		);

	}
}

/**
 * Trait RHEASearchFormRadiusSettings
 *
 * Trait for search forms radius field settings.
 *
 * @since 2.3.2
 */
trait RHEASearchFormRadiusSettings {
	public function SF_radius_fields() {

		if ( function_exists( 'inspiry_get_maps_type' ) && 'google-maps' === inspiry_get_maps_type() ) {

			$this->start_controls_section(
				'rhea_property_radius_search_section',
				[
					'label'     => esc_html__( 'Radius Search Slider', 'realhomes-elementor-addon' ),
					'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'location_radius_search' => 'yes',
					]
				]
			);

			$this->add_control(
				'radius_label',
				[
					'label'   => esc_html__( 'Radius Label', 'realhomes-elementor-addon' ),
					'type'    => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Radius', 'realhomes-elementor-addon' ),
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'      => 'radius_label_typo',
					'label'     => esc_html__( 'Radius Label Typography', 'realhomes-elementor-addon' ),
					'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
					'selector'  => '{{WRAPPER}} .geolocation-radius-info',
					'condition' => [
						'guests_field_type' => 'guest-types',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'      => 'radius_area_label_typo',
					'label'     => esc_html__( 'Radius Area Label Typography', 'realhomes-elementor-addon' ),
					'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
					'selector'  => '{{WRAPPER}} .geolocation-radius-info strong',
					'condition' => [
						'guests_field_type' => 'guest-types',
					],
				]
			);

			$this->add_responsive_control(
				'label-position',
				[
					'label'       => esc_html__( 'Label Position', 'realhomes-elementor-addon' ),
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'default'     => 'initial',
					'label_block' => true,
					'options'     => array(
						'initial' => esc_html__( 'Inline', 'realhomes-elementor-addon' ),
						'column'  => esc_html__( 'Label Top', 'realhomes-elementor-addon' ),
					),
					'selectors'   => [
						'{{WRAPPER}} .rhea-geolocation-radius-slider-wrapper' => 'flex-direction: {{VALUE}};',
					],
				]
			);

			if ( 'rhea-search-form-widget' === $this->get_name() ) {
				$this->add_responsive_control(
					'radius-slider-position',
					[
						'label'       => esc_html__( 'Radius Slider Position', 'realhomes-elementor-addon' ),
						'description' => esc_html__( 'Only when radius slider is in top fields', 'realhomes-elementor-addon' ),
						'type'        => \Elementor\Controls_Manager::SELECT2,
						'default'     => 'initial',
						'label_block' => true,
						'options'     => array(
							'initial'  => esc_html__( 'Default', 'realhomes-elementor-addon' ),
							'absolute' => esc_html__( 'Stick At Bottom (Top fields only)', 'realhomes-elementor-addon' ),
						),
						'condition'   => [
							'rhea_search_button_position' => '',
						],
					]
				);
			}

			$this->add_responsive_control(
				'slider_radius_field_size',
				[
					'label'           => esc_html__( 'Radius Slider Width (%)', 'realhomes-elementor-addon' ),
					'type'            => \Elementor\Controls_Manager::SLIDER,
					'size_units'      => [ '%', 'px' ],
					'range'           => [
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
						'px' => [
							'min' => 0,
							'max' => 1500,
						],
					],
					'desktop_default' => [
						'size' => 100,
						'unit' => '%',
					],
					'selectors'       => [
						'{{WRAPPER}} .rhea_radius_slider_field' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'slider_radius_field_height',
				[
					'label'     => esc_html__( 'Radius Slider Height (px)', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 0,
							'max' => 200,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .rhea_radius_slider_field_inner' => 'height: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_responsive_control(
				'slider_radius_top_position',
				[
					'label'      => esc_html__( 'Slider Top Position', 'realhomes-elementor-addon' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'conditions' => [
						'relation' => 'and',
						'terms'    => [
							[
								'name'  => 'radius-slider-position',
								'value' => 'absolute',
							],
							[
								'name'  => 'rhea_search_button_position',
								'value' => '',
							],
						],
					],
					'size_units' => [ '%', 'px' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 52,
					],
					'selectors'  => [
						'{{WRAPPER}} .rhea-radius-position-absolute .rhea_top_search_fields .rhea_radius_slider_field' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'collapsed-wrapper-top-padding',
				[
					'label'      => esc_html__( 'Collapsed (Advance) Wrapper Top Position', 'realhomes-elementor-addon' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'conditions' => [
						'relation' => 'and',
						'terms'    => [
							[
								'name'  => 'radius-slider-position',
								'value' => 'absolute',
							],
							[
								'name'  => 'rhea_search_button_position',
								'value' => '',
							],
						],
					],
					'size_units' => [ '%', 'px' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 50,
					],
					'selectors'  => [
						'{{WRAPPER}} .rhea_collapsed_search_fields' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'rhea_radius_slider_padding',
				[
					'label'      => esc_html__( 'Radius Slider Padding', 'realhomes-elementor-addon' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .rhea_radius_slider_field_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'      => 8,
						'right'    => 15,
						'bottom'   => 8,
						'left'     => 15,
						'unit'     => 'px',
						'isLinked' => false,
					],
				]
			);

			$this->add_responsive_control(
				'rhea_radius_slider_margin',
				[
					'label'      => esc_html__( 'Radius Slider Margin', 'realhomes-elementor-addon' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .rhea_radius_slider_field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'radius_label_color',
				[
					'label'     => esc_html__( 'Radius Label Color', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .geolocation-radius-info'              => 'color: {{VALUE}}',
						'{{WRAPPER}} .geolocation-radius-info :not(strong)' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'radius_value_color',
				[
					'label'     => esc_html__( 'Radius Area Label Color', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .geolocation-radius-info strong' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'radius_slider_bar_color',
				[
					'label'     => esc_html__( 'Slider Background', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .rhea_radius_slider_field .ui-widget.ui-widget-content' => 'background: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'radius_slider_control_color',
				[
					'label'     => esc_html__( 'Slider Grab Background', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .rhea_radius_slider_field .ui-widget-content .ui-state-default' => 'background: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'slider_range_color',
				[
					'label'     => esc_html__( 'Slider Range Background', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .rhea_radius_slider_field .ui-widget-header' => 'background: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name'     => 'radius-search-border',
					'label'    => esc_html__( 'Border', 'realhomes-elementor-addon' ),
					'selector' => '{{WRAPPER}} .rhea_radius_slider_field_inner',
				]
			);


			$this->end_controls_section();
		}
	}
}

/**
 * Trait RHEASortingControlTrait
 *
 * Trait for sorting search form fields for modern.
 *
 * @since 2.3.0
 */
trait RHEASortingControlTrait {
	/**
	 * Enqueue control scripts and styles.
	 *
	 * Enqueue stylesheets and scripts necessary for the control.
	 *
	 * @since 2.3.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'rhea-search-sorting-control', RHEA_PLUGIN_URL . 'elementor/css/search-sorting-control.css', array(), RHEA_VERSION, 'all' );
		wp_enqueue_script( 'rhea-sortable', RHEA_PLUGIN_URL . 'elementor/js/rhea-sortable.js', array( 'jquery' ), RHEA_VERSION );
	}

	/**
	 * Render content template.
	 *
	 * Render the content template for the control.
	 *
	 * @since 2.3.0
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
        <div class="rhea_sorting_control_wrapper">
            <ul class="rhea-sorting-list-<?php echo esc_attr( $control_uid ); ?>">
                <#
                function arrayFlip( fields ) {
                let ret = {};

                for ( let key in fields ) {
                ret[fields[key]] = key;
                }

                return ret;
                }

                let searchFields = data.search_fields,
                currentFields = data.controlValue;

                if( _.isString(currentFields) && ! _.isEmpty(currentFields) ){
                currentFields = currentFields.split( ',' );
                searchFields = _.extend( arrayFlip( currentFields ), searchFields );
                }

                _.each( searchFields, function( field_label, field_value ) {
                let attribute = '';

                if ( -1 !== currentFields.indexOf( field_value ) ) {
                attribute = 'checked=checked';
                }
                #>
                <li><label for="<?php echo esc_attr( $control_uid ); ?>-{{ field_value }}"><input id="<?php echo esc_attr( $control_uid ); ?>-{{ field_value }}" type="checkbox" value="{{ field_value }}" {{ attribute }}>{{{ field_label }}}</label></li>
                <# } ); #>
            </ul>
            <input type="hidden" id="rhea-sorting-<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}">
        </div>
		<?php
	}
}
