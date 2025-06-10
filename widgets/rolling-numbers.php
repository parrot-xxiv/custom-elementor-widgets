<?php

defined('ABSPATH') || exit;

class Rolling_Numbers extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rolling_numbers';
    }

    public function get_title() {
        return __('Rolling Numbers', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-counter';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['cew-gsap', 'cew-scrolltrigger', 'cew-rolling-numbers','cew-lucide'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => __('Number', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '7492',
                'placeholder' => __('Enter your number', 'textdomain'),
            ]
        );

        $this->add_control(
            'icon_before',
            [
                'label' => __('Icon Before (Lucide)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('e.g., star, heart, dollar-sign', 'textdomain'),
            ]
        );

        $this->add_control(
            'icon_after',
            [
                'label' => __('Icon After (Lucide)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('e.g., star, heart, plus', 'textdomain'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => __('Typography', 'textdomain'),
                'selector' => '{{WRAPPER}} .reel-digit',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => __('Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reel-digit' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lucide-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'digit_width',
            [
                'label' => __('Digit Width', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .reel-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $number = $settings['number'];
        $icon_before = $settings['icon_before'];
        $icon_after = $settings['icon_after'];
        ?>
        <div class="rolling-numbers-widget">
            <div class="reel-wrapper-container" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <?php if (!empty($icon_before)): ?>
                    <i data-lucide="<?php echo esc_attr($icon_before); ?>" class="lucide-icon"></i>
                <?php endif; ?>
                
                <div class="reel-wrapper" data-number="<?php echo esc_attr($number); ?>" style="display: flex;"></div>
                
                <?php if (!empty($icon_after)): ?>
                    <i data-lucide="<?php echo esc_attr($icon_after); ?>" class="lucide-icon"></i>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var number = settings.number;
        var iconBefore = settings.icon_before;
        var iconAfter = settings.icon_after;
        #>
        <div class="rolling-numbers-widget">
            <div class="reel-wrapper-container" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <# if (iconBefore) { #>
                    <i data-lucide="{{{ iconBefore }}}" class="lucide-icon"></i>
                <# } #>
                
                <div class="reel-wrapper" data-number="{{{ number }}}" style="display: flex;"></div>
                
                <# if (iconAfter) { #>
                    <i data-lucide="{{{ iconAfter }}}" class="lucide-icon"></i>
                <# } #>
            </div>
        </div>
        <?php
    }
}