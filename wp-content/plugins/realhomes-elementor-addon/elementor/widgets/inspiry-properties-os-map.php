<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Properties_OS_Map_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rhea-properties-os-map-widget';
	}

	public function get_title() {
		return esc_html__( 'Properties Open Street Map', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		// More classes for icons can be found at https://pojome.github.io/elementor-icons/
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return [ 'real-homes' ];
	}

	public function get_script_depends() {

		wp_register_script(
			'leaflet',
			'https://unpkg.com/leaflet@1.3.4/dist/leaflet.js',
			array(),
			'1.3.4',
			true
		);

		wp_register_script(
			'rhea-open-street-map',
			RHEA_PLUGIN_URL . 'elementor/js/open-street-map.js',
			[ 'leaflet', 'elementor-frontend' ],
			'1.3.4',
			true
		);

		return [
			'jquery',
			'leaflet',
			'rhea-open-street-map'
		];
	}

	public function get_style_depends() {

		wp_register_style(
			'leaflet-style',
			'https://unpkg.com/leaflet@1.3.4/dist/leaflet.css',
			array(),
			'1.3.4'
		);

		return [ 'leaflet-style' ];

	}

	protected function register_controls() {
		$this->start_controls_section(
			'ere_properties_section',
			[
				'label' => esc_html__( 'Properties', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'map_sync_properties',
			[
				'label'              => esc_html__( 'Sync Properties Widget Data', 'realhomes-elementor-addon' ),
				'description'        => esc_html__( 'Use this map to sync properties from Ultra Properties Widget.', 'realhomes-elementor-addon' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'          => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value'       => 'yes',
				'default'            => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'     => esc_html__( 'Number of Properties', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 1000,
				'step'      => 1,
				'default'   => 20,
				'condition' => [
					'map_sync_properties' => [ 'no', '' ]
				],
			]
		);

		$property_taxonomies = get_object_taxonomies( 'property', 'objects' );
		if ( ! empty( $property_taxonomies ) && ! is_wp_error( $property_taxonomies ) ) {
			foreach ( $property_taxonomies as $single_tax ) {
				$options = [];
				$terms   = get_terms( $single_tax->name );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$options[ $term->slug ] = $term->name;
					}
				}

				$this->add_control(
					$single_tax->name,
					[
						'label'       => $single_tax->label,
						'type'        => \Elementor\Controls_Manager::SELECT2,
						'multiple'    => true,
						'label_block' => true,
						'options'     => $options,
						'condition'   => [
							'map_sync_properties' => [ 'no', '' ]
						],
					]
				);
			}
		}

		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'date'       => esc_html__( 'Date', 'realhomes-elementor-addon' ),
					'price'      => esc_html__( 'Price', 'realhomes-elementor-addon' ),
					'title'      => esc_html__( 'Title', 'realhomes-elementor-addon' ),
					'menu_order' => esc_html__( 'Menu Order', 'realhomes-elementor-addon' ),
					'rand'       => esc_html__( 'Random', 'realhomes-elementor-addon' ),
				],
				'default'   => 'date',
				'condition' => [
					'map_sync_properties' => [ 'no', '' ]
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'asc'  => esc_html__( 'Ascending', 'realhomes-elementor-addon' ),
					'desc' => esc_html__( 'Descending', 'realhomes-elementor-addon' ),
				],
				'default'   => 'desc',
				'condition' => [
					'map_sync_properties' => [ 'no', '' ]
				],
			]
		);

		$this->add_control(
			'show_only_featured',
			[
				'label'        => esc_html__( 'Show Only Featured Properties', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'map_sync_properties' => [ 'no', '' ]
				],
			]
		);

		$this->add_control(
			'skip_sticky_properties',
			[
				'label'        => esc_html__( 'Skip Sticky Properties', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'map_sync_properties' => [ 'no', '' ]
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'     => esc_html__( 'Offset or Skip From Start', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => '0',
				'condition' => [
					'map_sync_properties' => [ 'no', '' ]
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_property_typo_section',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_heading_typography',
				'label'    => esc_html__( 'Popup Title', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .leaflet-popup-content .osm-popup-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_price_typography',
				'label'    => esc_html__( 'Popup Price', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .osm-popup-price',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_property_style_section',
			[
				'label' => esc_html__( 'Spacings And Sizes', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ere_property_map_height',
			[
				'label'           => esc_html__( 'Map Height (px)', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'desktop_default' => [
					'size' => 600,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea-listing-map' => 'height: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'ere_property_popup_padding_bottom',
			[
				'label'     => esc_html__( 'Popup Content Padding Bottom (px)', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .leaflet-popup-content-wrapper' => 'padding-bottom: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'ere_property_title_margin_top',
			[
				'label'     => esc_html__( 'Popup Title Margin Top (px)', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .osm-popup-title' => 'margin-top: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'ere_property_price_margin_top',
			[
				'label'     => esc_html__( 'Popup Price Margin Top (px)', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .leaflet-popup-content p' => 'margin-top: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_property_colors_section',
			[
				'label' => esc_html__( 'Colors', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'rhea_popup_content_bg_color',
			[
				'label'     => esc_html__( 'Popup Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .leaflet-popup-content-wrapper' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_popup_title_color',
			[
				'label'     => esc_html__( 'Popup Title Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .osm-popup-title a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'rhea_popup_title_hover_color',
			[
				'label'     => esc_html__( 'Popup Title Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .osm-popup-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'rhea_popup_price_color',
			[
				'label'     => esc_html__( 'Popup Price Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .osm-popup-price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_popup_wrapper_border_color',
			[
				'label'     => esc_html__( 'Popup Border and Tip Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .leaflet-popup-content-wrapper' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .leaflet-popup-tip'             => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function process_properties() {
		$settings = $this->get_settings_for_display();
		$map_data = array();

		// Remove sticky properties filter.
		if ( $settings['skip_sticky_properties'] == 'yes' ) {
			remove_filter( 'the_posts', 'inspiry_make_properties_stick_at_top', 10 );
		}

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) { // if is static front page
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		if ( $settings['offset'] ) {
			$offset = $settings['offset'] + ( $paged - 1 ) * $settings['posts_per_page'];
		} else {
			$offset = '';
		}

		// Basic Query
		$properties_args = array(
			'post_type'      => 'property',
			'posts_per_page' => $settings['posts_per_page'],
			'order'          => $settings['order'],
			'offset'         => $offset,
			'post_status'    => 'publish',
			'paged'          => $paged,
		);

		// Sorting
		if ( 'price' === $settings['orderby'] ) {
			$properties_args['orderby']  = 'meta_value_num';
			$properties_args['meta_key'] = 'REAL_HOMES_property_price';
		} else {
			// for date, title, menu_order and rand
			$properties_args['orderby'] = $settings['orderby'];
		}

		// Filter based on custom taxonomies
		$property_taxonomies = get_object_taxonomies( 'property', 'objects' );
		if ( ! empty( $property_taxonomies ) && ! is_wp_error( $property_taxonomies ) ) {
			foreach ( $property_taxonomies as $single_tax ) {
				$setting_key = $single_tax->name;
				if ( ! empty( $settings[ $setting_key ] ) ) {
					$properties_args['tax_query'][] = [
						'taxonomy' => $setting_key,
						'field'    => 'slug',
						'terms'    => $settings[ $setting_key ],
					];
				}
			}

			if ( isset( $properties_args['tax_query'] ) && count( $properties_args['tax_query'] ) > 1 ) {
				$properties_args['tax_query']['relation'] = 'AND';
			}
		}

		$meta_query = array();
		if ( 'yes' === $settings['show_only_featured'] ) {
			$meta_query[] = array(
				'key'     => 'REAL_HOMES_featured',
				'value'   => 1,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);

			$properties_args['meta_query'] = $meta_query;
		}

		$map_properties_query = new WP_Query( apply_filters( 'rhea_openstreetmap_properties_widget', $properties_args ) );
		$properties_map_data = array();

		if ( $map_properties_query->have_posts() ) {
			while ( $map_properties_query->have_posts() ) {
				$map_properties_query->the_post();

				$property_id           = get_the_ID();
				$current_property_data = array();
				$get_post_taxonomies   = get_post_taxonomies( $property_id );

				foreach ( $get_post_taxonomies as $taxonomy ) {

					$get_the_terms = get_the_terms( $property_id, $taxonomy );

					if ( is_array( $get_the_terms ) || is_object( $get_the_terms ) ) {
						foreach ( $get_the_terms as $term ) {
							$current_property_data['classes'][] = 'rhea-' . $term->slug;
						}
					}
				}

				$current_property_data['title'] = get_the_title();

				if ( function_exists( 'ere_get_property_price' ) ) {
					$current_property_data['price'] = ere_get_property_price();
				} else {
					$current_property_data['price'] = null;
				}

				$current_property_data['url'] = get_permalink();

				// property location
				$property_location = get_post_meta( $property_id, 'REAL_HOMES_property_location', true );
				if ( ! empty( $property_location ) ) {
					$lat_lng                      = explode( ',', $property_location );
					$current_property_data['lat'] = $lat_lng[0];
					$current_property_data['lng'] = $lat_lng[1];
				}

				// property thumbnail
				if ( has_post_thumbnail() ) {
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

				// Set default icons if above code fails to sets any
				if ( ! isset( $current_property_data['icon'] ) ) {
					$current_property_data['icon']       = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon.png';           // default icon
					$current_property_data['retinaIcon'] = INSPIRY_DIR_URI . '/images/map/single-family-home-map-icon@2x.png';  // default retina icon
				}

				$properties_map_data[] = $current_property_data;
			}

			wp_reset_postdata();
		}

		$map_data['data'] = $properties_map_data;
		$map_data['id']   = $this->get_id();
		$map_data         = base64_encode( json_encode( $map_data ) );
		?>
        <div class="rhea-osm-map-data" data-osm-properties-map="<?php echo esc_attr( $map_data ); ?>"></div>
        <div id="rhea-<?php echo $this->get_id(); ?>" class="rhea-listing-map"></div>
		<?php
	}

	public function render() {
		?>
        <div class="rhea-map-head">
			<?php
			$this->process_properties();
			?>
        </div>
		<?php
	}
}