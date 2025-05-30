<?php
/**
 * HTML Tag Elementor Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class HTML_Tag_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'html_tag_widget';
	}

	public function get_title() {
		return __( 'HTML Tag', 'custom-elementor-widgets' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	protected function register_controls() {
		// Content Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'custom-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Your text here', 'custom-elementor-widgets' ),
				'placeholder' => __( 'Enter your text', 'custom-elementor-widgets' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'custom-elementor-widgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'custom-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'custom-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .html-tag-element',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .html-tag-element' => 'color: {{VALUE}};',
					'{{WRAPPER}} .html-tag-element a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'label' => __( 'Text Shadow', 'custom-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .html-tag-element',
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __( 'Margin', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .html-tag-element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'custom-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .html-tag-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tag = $settings['html_tag'];
		$text = $settings['text'];

		$this->add_render_attribute( 'html_tag', 'class', 'html-tag-element' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			echo '<' . esc_attr( $tag ) . ' ' . $this->get_render_attribute_string( 'html_tag' ) . '>';
			echo '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . esc_html( $text ) . '</a>';
			echo '</' . esc_attr( $tag ) . '>';
		} else {
			echo '<' . esc_attr( $tag ) . ' ' . $this->get_render_attribute_string( 'html_tag' ) . '>';
			echo esc_html( $text );
			echo '</' . esc_attr( $tag ) . '>';
		}
	}
}