<?php

/**
 * Image Transition Loop Widget for Custom Elementor Widgets plugin.
 */

defined('ABSPATH') || exit;

class Image_Transition_Loop extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'image_transition_loop';
    }

    public function get_title()
    {
        return __('Image Transition Loop', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-slideshow';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_script_depends()
    {
        return ['cew-swiper', 'cew-image-transition-loop'];
    }

    public function get_style_depends()
    {
        return ['cew-swiper'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Images', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => __('Add Images', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
                'show_label' => false,
            ]
        );

        $this->end_controls_section();

        // Settings Section
        $this->start_controls_section(
            'settings_section',
            [
                'label' => __('Settings', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => __('Autoplay Delay (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => __('Transition Speed (ms)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 600,
                'min' => 300,
                'max' => 2000,
                'step' => 100,
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => __('Transition Effect', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => __('Slide', 'custom-elementor-widgets'),
                    'fade' => __('Fade', 'custom-elementor-widgets'),
                    'cube' => __('Cube', 'custom-elementor-widgets'),
                    'coverflow' => __('Coverflow', 'custom-elementor-widgets'),
                    'flip' => __('Flip', 'custom-elementor-widgets'),
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

        $this->add_responsive_control(
            'height',
            [
                'label' => __('Height', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .image-transition-swiper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .image-transition-swiper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $widget_id = $this->get_id(); // Get the unique widget ID
        $settings = $this->get_settings_for_display();
        $images = $settings['images'];

        if (empty($images)) {
            return;
        }
?>
        <div class="image-transition-loop-wrapper" data-widget-id="<?php echo esc_attr($widget_id); ?>">
            <div class="swiper image-transition-swiper" id="swiper-<?php echo esc_attr($widget_id); ?>"
                data-autoplay_delay="<?php echo esc_attr($settings['autoplay_delay']); ?>"
                data-transition_speed="<?php echo esc_attr($settings['transition_speed']); ?>"
                data-effect="<?php echo esc_attr($settings['effect']); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image) : ?>
                        <div class="swiper-slide">
                            <?php echo wp_get_attachment_image($image['id'], 'full', false, [
                                'style' => 'width: 100%; height: 100%; object-fit: cover;'
                            ]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
<?php
    }
}
