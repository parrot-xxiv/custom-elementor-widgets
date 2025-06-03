<?php

/**
 * Parallax Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Parallax_Image extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'parallax_widget';
    }

    public function get_title()
    {
        return __('Parallax Image', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-parallax';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_script_depends()
    {
        return ['cew-gsap','cew-scrolltrigger','cew-parallax-image'];
    }

    public function get_style_depends()
    {
        return ['cew-parallax-image'];
    }

    protected function register_controls()
    {
        // Image Section
        $this->start_controls_section(
            'image_section',
            [
                'label' => __('Background Image', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'background_image',
            [
                'label' => __('Choose Image', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_responsive_control(
            'parallax_speed',
            [
                'label' => __('Parallax Speed', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
                'tablet_default' => [
                    'size' => 0.3,
                ],
                'mobile_default' => [
                    'size' => 0.2,
                ],
            ]
        );

        $this->add_control(
            'disable_mobile',
            [
                'label' => __('Disable on Mobile', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-elementor-widgets'),
                'label_off' => __('No', 'custom-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Disable parallax effect on mobile devices for better performance', 'custom-elementor-widgets'),
            ]
        );

        $this->end_controls_section();

        // Dimensions Section
        $this->start_controls_section(
            'dimensions_section',
            [
                'label' => __('Dimensions', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
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
                        'max' => 1200,
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
                    'unit' => 'vh',
                    'size' => 60,
                ],
                'tablet_default' => [
                    'unit' => 'vh',
                    'size' => 50,
                ],
                'mobile_default' => [
                    'unit' => 'vh',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .parallax-banner' => 'height: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'fallback_background',
            [
                'label' => __('Fallback Background Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'description' => __('Background color shown if parallax image doesn\'t cover the entire area', 'custom-elementor-widgets'),
                'selectors' => [
                    '{{WRAPPER}} .parallax-banner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __('Overlay Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-banner::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __('Overlay Opacity', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .parallax-banner::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .parallax-banner',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .parallax-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $image_url = $settings['background_image']['url'];
        $widget_id = $this->get_id();

        if (empty($image_url)) {
            return;
        }

        // Get responsive parallax speeds properly
        $speed_desktop = $this->get_responsive_control_value($settings, 'parallax_speed', 'desktop') ?? 0.5;
        $speed_tablet = $this->get_responsive_control_value($settings, 'parallax_speed', 'tablet') ?? $speed_desktop;
        $speed_mobile = $this->get_responsive_control_value($settings, 'parallax_speed', 'mobile') ?? $speed_tablet;

        $disable_mobile = $settings['disable_mobile'] === 'yes';
?>
        <section class="parallax-banner" 
                 data-widget-id="<?php echo esc_attr($widget_id); ?>"
                 data-speed-desktop="<?php echo esc_attr($speed_desktop); ?>"
                 data-speed-tablet="<?php echo esc_attr($speed_tablet); ?>"
                 data-speed-mobile="<?php echo esc_attr($speed_mobile); ?>"
                 data-disable-mobile="<?php echo esc_attr($disable_mobile ? 'true' : 'false'); ?>">
            <div class="parallax-image"
                 style="background-image: url('<?php echo esc_url($image_url); ?>');"
                 role="img"
                 aria-label="<?php echo esc_attr($settings['background_image']['alt'] ?? 'Parallax background image'); ?>">
            </div>
        </section>
<?php
    }

    /**
     * Get responsive control value helper
     */
    private function get_responsive_control_value($settings, $control_name, $device = 'desktop')
    {
        if ($device === 'desktop') {
            return $settings[$control_name]['size'] ?? null;
        }
        
        $device_key = $control_name . '_' . $device;
        return $settings[$device_key]['size'] ?? null;
    }
}