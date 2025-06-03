<?php

/**
 * HTML Tag Elementor Widget for Custom Elementor Widgets plugin.
 */

defined('ABSPATH') || exit;

class HTML_Tag_V2 extends \Elementor\Widget_Base {
    public function get_categories() {
        return ['basic'];
    }

    public function get_icon() {
        return 'eicon-heading';
    }

    public function get_name() {
        return 'html_tag_widget_v2';
    }

    public function get_script_depends() {
        return ['cew-html-tag-v2']; // fix this: return [] if selected "none"
    }

    public function get_style_depends() {
        return ['cew-html-tag-v2'];
    }

    public function get_title() {
        return __('HTML Tag V2', 'custom-elementor-widgets');
    }

    protected function content_template() {
        ?>
        <# var tag=settings.html_tag || 'h2' ; var link=settings.link.url ? '<a href="' + settings.link.url + '"' : '' ; if
            (settings.link.is_external) { link +=' target="_blank"' ; } if (settings.link.nofollow) { link +=' rel="nofollow"' ;
            } link +='>' + settings.text + '</a>' ; var output=settings.link.url ? link : settings.text; #>
            <{{{ tag }}} class="html-tag-element">{{{ output }}}</{{{ tag }}}>
        <?php

    }

    protected function register_controls() {
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
                'dynamic' => [
                    'active' => true,
                ],
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
                'dynamic' => [
                    'active' => true,
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

        $this->add_control(
            'animation_trigger',
            [
                'label' => __('Animation Trigger', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'viewport',
                'options' => [
                    'viewport' => __('When in Viewport', 'custom-elementor-widgets'),
                    'page_load' => __('On Page Load', 'custom-elementor-widgets'),
                ],
                'condition' => [
                    'entrance_animation!' => 'none',
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

        $this->add_control(
            'text_hover_color',
            [
                'label' => __('Link Hover Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .html-tag-element a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'link[url]!' => '',
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
                'size_units' => ['px', '%', 'em', 'rem'],
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
                'size_units' => ['px', '%', 'em', 'rem'],
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
        $animation = $settings['entrance_animation'];

        // Validation
        if (empty($text)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' .
                __('Please enter some text content.', 'custom-elementor-widgets') .
                    '</div>';
            }
            return;
        }

        $this->add_render_attribute('html_tag', 'class', 'html-tag-element');

        // Animation classes and data attributes
        if ($animation !== 'none') {
            $this->add_render_attribute('html_tag', 'data-animation', $animation);

            if (!empty($settings['animation_duration']['size'])) {
                $this->add_render_attribute('html_tag', 'data-duration', $settings['animation_duration']['size']);
            }

            if (!empty($settings['animation_delay']['size'])) {
                $this->add_render_attribute('html_tag', 'data-delay', $settings['animation_delay']['size']);
            }

            if ($animation === 'staggered_blur_fade' && !empty($settings['stagger_delay']['size'])) {
                $this->add_render_attribute('html_tag', 'data-stagger', $settings['stagger_delay']['size']);
            }

            if ($animation === 'typewriter') {
                if (!empty($settings['typewriter_speed']['size'])) {
                    $this->add_render_attribute('html_tag', 'data-speed', $settings['typewriter_speed']['size']);
                }
                if (!empty($settings['cursor_blink_speed']['size'])) {
                    $this->add_render_attribute('html_tag', 'data-blink', $settings['cursor_blink_speed']['size']);
                }
            }

            if (!empty($settings['animation_trigger'])) {
                $this->add_render_attribute('html_tag', 'data-trigger', $settings['animation_trigger']);
            }
        }

        $output_text = $text;

        // Optional link
        if (!empty($settings['link']['url'])) {
            $this->add_render_attribute('link', 'href', $settings['link']['url']);

            if (!empty($settings['link']['is_external'])) {
                $this->add_render_attribute('link', 'target', '_blank');
            }

            if (!empty($settings['link']['nofollow'])) {
                $this->add_render_attribute('link', 'rel', 'nofollow');
            }

            $output_text = sprintf('<a %s>%s</a>', $this->get_render_attribute_string('link'), $text);
        }

        printf('<%1$s %2$s>%3$s</%1$s>', esc_html($tag), $this->get_render_attribute_string('html_tag'), $output_text);
    }
}