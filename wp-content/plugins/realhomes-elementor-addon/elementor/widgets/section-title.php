<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Section_Title_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ere-section-title-widget';
	}

	public function get_title() {
		return esc_html__( 'Section Title', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-post-title';
	}

	public function get_categories() {
		return [ 'real-homes' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'ere_section_title',
			[
				'label' => esc_html__( 'Section Titles', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ere_top_title',
			[
				'label' => esc_html__( 'Top Title', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'ere_main_title',
			[
				'label' => esc_html__( 'Main Title', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'ere_description',
			[
				'label' => esc_html__( 'Description', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::TEXTAREA,
				'rows'  => 2,
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'ere_section_typography',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'section_top_title_typography',
				'label'    => esc_html__( 'Top Title ', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_section__subtitle',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'section_main_title_typography',
				'label'    => esc_html__( 'Main Title ', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_section__title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'section_description_typography',
				'label'    => esc_html__( 'Description ', 'realhomes-elementor-addon' ),
				'global'   => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rh_section__desc',
			]
		);

		$this->add_responsive_control(
			'ere_section_align',
			[
				'label'     => esc_html__( 'Alignment', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .re_section_head_elementor' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_section_titles_styles',
			[
				'label' => esc_html__( 'Titles Colors', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'ere_section_title_color',
			[
				'label'     => esc_html__( 'Top Title', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .re_section_head_elementor .rh_section__subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'ere_section_main_title_color',
			[
				'label'     => esc_html__( 'Main Title', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .re_section_head_elementor .rh_section__title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'ere_section_description_color',
			[
				'label'     => esc_html__( 'Description', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .re_section_head_elementor .rh_section__desc' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'ere_section_titles_margins',
			[
				'label' => esc_html__( 'Spacings', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ere_section_content_max_width',
			[
				'label'     => esc_html__( 'Content Max Width', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 2500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .re_section_head_elementor' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'disable_content_auto_margin',
			[
				'label'        => esc_html__( 'Disable Content Auto Margin', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => 'No',
				'label_off'    => 'Yes',
				'return_value' => 'yes',
				'selectors'    => [
					'{{WRAPPER}} .re_section_head_elementor' => 'margin-left: 0; margin-right: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'ere_top_title_spacing',
			[
				'label'     => esc_html__( 'Top Title Bottom Margin', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rh_section__subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ere_main_title_spacing',
			[
				'label'     => esc_html__( 'Main Title Bottom Margin', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rh_section__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ere_description_margin_bottom',
			[
				'label'           => esc_html__( 'Description Bottom Margin', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'desktop_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .re_section_head_elementor .rh_section__desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ere_section_margin_bottom',
			[
				'label'           => esc_html__( 'Section Bottom Margin', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'desktop_default' => [
					'size' => 35,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .re_section_head_elementor' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render section title.
	 *
	 * @param $settings
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( $settings['ere_top_title'] || $settings['ere_main_title'] || $settings['ere_description'] ) {
			?>
            <div class="rh_elementor_widget re_section_head_elementor">
				<?php if ( $settings['ere_top_title'] ) { ?>
                    <span class="rh_section__subtitle"><?php echo esc_html( $settings['ere_top_title'] ); ?></span>
				<?php } ?>

				<?php if ( $settings['ere_main_title'] ) { ?>
                    <h2 class="rh_section__title"><?php echo esc_html( $settings['ere_main_title'] ); ?></h2>
				<?php } ?>

				<?php if ( $settings['ere_description'] ) { ?>
                    <p class="rh_section__desc"><?php echo esc_html( $settings['ere_description'] ); ?></p>
				<?php } ?>
            </div>
			<?php
		}
	}

}