<?php

/**
 * HTML Tag Elementor Widget for Custom Elementor Widgets plugin.
 */

defined('ABSPATH') || exit;

class HTML_Tag extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'html_tag_widget';
    }

    public function get_title()
    {
        return __('HTML Tag', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-heading';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __('HTML Tag', 'custom-elementor-widgets'),
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
                'label' => __('Text', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Your text here', 'custom-elementor-widgets'),
                'placeholder' => __('Enter your text', 'custom-elementor-widgets'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'custom-elementor-widgets'),
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
                'label' => __('Alignment', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'custom-elementor-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'custom-elementor-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'custom-elementor-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'custom-elementor-widgets'),
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

        // Animation Section
        $this->start_controls_section(
            'animation_section',
            [
                'label' => __('Entrance Animation', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'entrance_animation',
            [
                'label' => __('Animation Type', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'custom-elementor-widgets'),
                    'staggered_blur_fade' => __('Staggered Blur & Fade-In Letters', 'custom-elementor-widgets'),
                    'typewriter' => __('Typewriter Effect with Blinking Cursor', 'custom-elementor-widgets'),
                ],
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label' => __('Animation Duration (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2000,
                ],
                'range' => [
                    'px' => [
                        'min' => 500,
                        'max' => 10000,
                        'step' => 100,
                    ],
                ],
                'condition' => [
                    'entrance_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'animation_delay',
            [
                'label' => __('Animation Delay (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 100,
                    ],
                ],
                'condition' => [
                    'entrance_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'stagger_delay',
            [
                'label' => __('Letter Stagger Delay (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                        'step' => 10,
                    ],
                ],
                'condition' => [
                    'entrance_animation' => 'staggered_blur_fade',
                ],
            ]
        );

        $this->add_control(
            'typewriter_speed',
            [
                'label' => __('Typing Speed (ms per character)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 300,
                        'step' => 10,
                    ],
                ],
                'condition' => [
                    'entrance_animation' => 'typewriter',
                ],
            ]
        );

        $this->add_control(
            'cursor_blink_speed',
            [
                'label' => __('Cursor Blink Speed (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 2000,
                        'step' => 100,
                    ],
                ],
                'condition' => [
                    'entrance_animation' => 'typewriter',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __('Typography', 'custom-elementor-widgets'),
                'selector' => '{{WRAPPER}} .html-tag-element',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'custom-elementor-widgets'),
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
                'label' => __('Text Shadow', 'custom-elementor-widgets'),
                'selector' => '{{WRAPPER}} .html-tag-element',
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label' => __('Margin', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .html-tag-element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .html-tag-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];
        $text = $settings['text'];
        $animation = $settings['entrance_animation'];

        $this->add_render_attribute('html_tag', 'class', 'html-tag-element');

        // Add animation classes and data attributes
        if ($animation !== 'none') {
            $this->add_render_attribute('html_tag', 'class', 'has-entrance-animation');
            $this->add_render_attribute('html_tag', 'class', 'animation-' . $animation);
            $this->add_render_attribute('html_tag', 'data-animation', $animation);

            // Safe array access with fallback values
            $duration = isset($settings['animation_duration']['size']) ? $settings['animation_duration']['size'] : 2000;
            $delay = isset($settings['animation_delay']['size']) ? $settings['animation_delay']['size'] : 0;

            $this->add_render_attribute('html_tag', 'data-duration', $duration);
            $this->add_render_attribute('html_tag', 'data-delay', $delay);

            if ($animation === 'staggered_blur_fade') {
                $stagger_delay = isset($settings['stagger_delay']['size']) ? $settings['stagger_delay']['size'] : 50;
                $this->add_render_attribute('html_tag', 'data-stagger-delay', $stagger_delay);
            }

            if ($animation === 'typewriter') {
                $typing_speed = isset($settings['typewriter_speed']['size']) ? $settings['typewriter_speed']['size'] : 100;
                $cursor_blink = isset($settings['cursor_blink_speed']['size']) ? $settings['cursor_blink_speed']['size'] : 500;
                $this->add_render_attribute('html_tag', 'data-typing-speed', $typing_speed);
                $this->add_render_attribute('html_tag', 'data-cursor-blink', $cursor_blink);
            }
        }

        // Render the element
        if (! empty($settings['link']['url'])) {
            $this->add_link_attributes('link', $settings['link']);
            echo '<' . esc_attr($tag) . ' ' . $this->get_render_attribute_string('html_tag') . '>';
            echo '<a ' . $this->get_render_attribute_string('link') . '>' . esc_html($text) . '</a>';
            echo '</' . esc_attr($tag) . '>';
        } else {
            echo '<' . esc_attr($tag) . ' ' . $this->get_render_attribute_string('html_tag') . '>';
            echo esc_html($text);
            echo '</' . esc_attr($tag) . '>';
        }

    }

    public function get_style_depends()
    {
        return ['cew-html-tag'];
    }

    public function get_script_depends()
    {
        return ['cew-html-tag'];  // fix this: return [] if selected "none"
    }
}
