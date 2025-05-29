<?php

/**
 * Image Transition Loop Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Image_Transition_Loop_Widget extends \Elementor\Widget_Base
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
        return ['swiper'];
    }

    public function get_style_depends()
    {
        return ['swiper'];
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
        $settings = $this->get_settings_for_display();
        $images = $settings['images'];

        if (empty($images)) {
            return;
        }

        $widget_id = $this->get_id();
?>
        <div class="image-transition-loop-wrapper">
            <div class="swiper image-transition-swiper" id="swiper-<?php echo esc_attr($widget_id); ?>">
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

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swiper !== 'undefined') {
                    new Swiper('#swiper-<?php echo esc_js($widget_id); ?>', {
                        effect: '<?php echo esc_js($settings['effect']); ?>',
                        loop: true,
                        autoplay: {
                            delay: <?php echo intval($settings['autoplay_delay']); ?>,
                            disableOnInteraction: false,
                        },
                        speed: <?php echo intval($settings['transition_speed']); ?>,
                        <?php if ($settings['effect'] === 'cube') : ?>
                            cubeEffect: {
                                shadow: true,
                                slideShadows: true,
                                shadowOffset: 20,
                                shadowScale: 0.94,
                            },
                        <?php elseif ($settings['effect'] === 'coverflow') : ?>
                            coverflowEffect: {
                                rotate: 50,
                                stretch: 0,
                                depth: 100,
                                modifier: 1,
                                slideShadows: true,
                            },
                        <?php endif; ?>
                    });
                }
            });
        </script>

        <style>
            .image-transition-loop-wrapper {
                width: 100%;
                height: 100%;
            }

            .image-transition-swiper {
                width: 100%;
                overflow: hidden;
            }

            .image-transition-swiper .swiper-slide {
                display: flex;
                justify-content: center;
                align-items: center;
                background: #f0f0f0;
            }

            .image-transition-swiper .swiper-slide img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        </style>
    <?php
    }

    protected function content_template()
    {
    ?>
        <#
            var images=settings.images;
            var widgetId=Math.random().toString(36).substr(2, 9);

            if ( images.length===0 ) {
            return;
            }
            #>
            <div class="image-transition-loop-wrapper">
                <div class="swiper image-transition-swiper" id="swiper-{{{ widgetId }}}">
                    <div class="swiper-wrapper">
                        <# _.each( images, function( image ) { #>
                            <div class="swiper-slide">
                                <img src="{{{ image.url }}}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <# }); #>
                    </div>
                </div>
            </div>

            <style>
                .image-transition-loop-wrapper {
                    width: 100%;
                }

                .image-transition-swiper {
                    width: 100%;
                    overflow: hidden;

                    height: {
                            {
                                {
                                settings.height.size+settings.height.unit
                            }
                        }
                    }

                    ;
                }

                .image-transition-swiper .swiper-slide {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background: #f0f0f0;
                }

                .image-transition-swiper .swiper-slide img {
                    display: block;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            </style>
    <?php
    }
}
