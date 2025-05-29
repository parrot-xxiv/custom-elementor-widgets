<?php

/**
 * Parallax Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Parallax_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'parallax_widget';
    }

    public function get_title()
    {
        return __('Parallax Banner', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-parallax';
    }

    public function get_categories()
    {
        return ['basic'];
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

        $this->add_control(
            'parallax_speed',
            [
                'label' => __('Parallax Speed', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
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

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $image_url = $settings['background_image']['url'];
        $parallax_speed = $settings['parallax_speed']['size'];
        $widget_id = $this->get_id();

        if (empty($image_url)) {
            return;
        }
?>
        <section class="parallax-banner parallax-banner-<?php echo $widget_id; ?>">
            <div class="parallax-image"
                style="background-image: url('<?php echo esc_url($image_url); ?>');"
                data-speed="<?php echo esc_attr($parallax_speed); ?>">
            </div>
        </section>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const section = document.querySelector('.parallax-banner-<?php echo esc_js($widget_id); ?>');
                if (!section) return;

                const image = section.querySelector('.parallax-image');
                const speed = parseFloat(image.dataset.speed || 0.3);

                function updateParallax() {
                    const rect = section.getBoundingClientRect();
                    const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                    const offsetTop = rect.top + scrollY;

                    if (scrollY + window.innerHeight > offsetTop && scrollY < offsetTop + section.offsetHeight) {
                        const distance = scrollY - offsetTop;
                        image.style.transform = `translateY(${distance * speed}px)`;
                    }
                }

                window.addEventListener('scroll', updateParallax, {
                    passive: true
                });
                window.addEventListener('resize', updateParallax);
                updateParallax();
            });
        </script>

            <style>
        .parallax-banner {
            position: relative;
            height: 500px;
            width: 100%;
            overflow: hidden;
        }

        .parallax-image {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 150%;
            background-size: cover;
            background-position: center;
            transform: translateY(0);
            z-index: -1;
            will-change: transform;
        }
    </style>
    <?php
    }

}
