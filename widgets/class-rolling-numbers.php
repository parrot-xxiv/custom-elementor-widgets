<?php

/**
 * Rolling Number Reels Elementor Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Rolling_Number_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'rolling_number';
    }

    public function get_title()
    {
        return __('Rolling Number Reels', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-number-field';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_script_depends()
    {
        return ['gsap', 'gsap-scrolltrigger'];
    }

    protected function register_controls()
    {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'target_number',
            [
                'label' => __('Target Number', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 12345,
                'min' => 0,
                'max' => 999999999,
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label' => __('Animation Duration (seconds)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.5,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1.5,
                ],
            ]
        );

        $this->add_control(
            'stagger_delay',
            [
                'label' => __('Stagger Delay (seconds)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.05,
                    ],
                ],
                'default' => [
                    'size' => 0.1,
                ],
            ]
        );

        $this->add_control(
            'trigger_animation',
            [
                'label' => __('Animation Trigger', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'viewport',
                'options' => [
                    'viewport' => __('When in viewport', 'custom-elementor-widgets'),
                    'load' => __('On page load', 'custom-elementor-widgets'),
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .reel-digit',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => __('Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .reel-digit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'reel_background',
            [
                'label' => __('Reel Background', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .reel-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reel_height',
            [
                'label' => __('Reel Height', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 200,
                    ],
                    'rem' => [
                        'min' => 2,
                        'max' => 12,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .reel-container' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .reel-digit' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reel_width',
            [
                'label' => __('Reel Width', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 6,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 2.5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .reel-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reel_spacing',
            [
                'label' => __('Spacing Between Reels', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 0.5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .reel-container:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __('Alignment', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'custom-elementor-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'custom-elementor-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'custom-elementor-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .reel-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'reel_border',
                'selector' => '{{WRAPPER}} .reel-container',
            ]
        );

        $this->add_responsive_control(
            'reel_border_radius',
            [
                'label' => __('Border Radius', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .reel-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $target_number = (string) $settings['target_number'];
        $duration = isset($settings['animation_duration']['size']) ? $settings['animation_duration']['size'] : 1.5;
        $stagger = isset($settings['stagger_delay']['size']) ? $settings['stagger_delay']['size'] : 0.1;
        $trigger = $settings['trigger_animation'];
?>

        <div class="py-5 flex items-center justify-center">
            ^
            <div id="reel-wrapper-<?php echo esc_attr($widget_id); ?>"
                class="reel-wrapper flex gap-2"
                data-number="<?php echo esc_attr($target_number); ?>"
                data-duration="<?php echo esc_attr($duration); ?>"
                data-stagger="<?php echo esc_attr($stagger); ?>"
                data-trigger="<?php echo esc_attr($trigger); ?>">

                <?php
                foreach (str_split($target_number) as $digit) :
                    if (!is_numeric($digit)) continue;
                ?>
                    <div class="reel-container overflow-hidden h-16 w-10 rounded text-center">

                        <div class="reel-column flex flex-col transition-transform">
                            <?php for ($i = 0; $i <= 10; $i++): ?>
                                <div class="reel-digit h-16 flex items-center justify-center text-3xl font-mono text-gray-800">
                                    <?php echo $i % 10; ?>
                                </div>
                            <?php endfor; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
            +
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof gsap === 'undefined') {
                    console.error('GSAP is not loaded. Please enqueue it correctly.');
                    return;
                }

                gsap.registerPlugin(ScrollTrigger);

                const wrapperId = 'reel-wrapper-<?php echo esc_js($widget_id); ?>';
                const reelWrapper = document.getElementById(wrapperId);

                if (!reelWrapper) return;

                const targetNumber = reelWrapper.getAttribute('data-number') || '';
                const duration = parseFloat(reelWrapper.getAttribute('data-duration')) || 1.5;
                const stagger = parseFloat(reelWrapper.getAttribute('data-stagger')) || 0.1;
                const trigger = reelWrapper.getAttribute('data-trigger');

                const animateReels = () => {
                    const columns = reelWrapper.querySelectorAll('.reel-column');

                    columns.forEach((col, index) => {
                        const targetDigit = parseInt(targetNumber[index] ?? 0);

                        // Use rem instead of pixel values, assuming Tailwind height is h-16 = 4rem
                        const targetOffset = `-${targetDigit * 4}rem`;

                        gsap.to(col, {
                            y: targetOffset,
                            duration: duration,
                            ease: 'power2.out',
                            delay: index * stagger
                        });
                    });
                };


                if (trigger === 'viewport') {
                    ScrollTrigger.create({
                        trigger: reelWrapper,
                        start: 'top 80%',
                        once: true,
                        onEnter: animateReels
                    });
                } else {
                    animateReels();
                }
            });
        </script>

<?php
    }
}
