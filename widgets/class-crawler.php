<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Crawler Widget.
 *
 * Elementor widget that displays a marquee/crawler effect with words.
 *
 * @since 1.0.0
 */
class Crawler_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve crawler widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'crawler_widget';
    }

    /**
     * Get widget title.
     *
     * Retrieve crawler widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Crawler Widget', 'custom-elementor-widgets');
    }

    /**
     * Get widget icon.
     *
     * Retrieve crawler widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-editor-code'; // You can change this to a more fitting icon e.g., 'eicon-slider-push'
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the crawler widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['basic']; // You can create a custom category
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['crawler', 'marquee', 'scrolling', 'text', 'animation', 'swiper'];
    }

    /**
     * Register crawler widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        // Content Tab: Words Section
        // =========================
        $this->start_controls_section(
            'section_words',
            [
                'label' => __('Words', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'word_text',
            [
                'label' => __('Word/Phrase', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your Text Here', 'custom-elementor-widgets'),
                'placeholder' => __('Enter your text', 'custom-elementor-widgets'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'marquee_words',
            [
                'label' => __('Words List', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['word_text' => __('Elementor', 'custom-elementor-widgets')],
                    ['word_text' => __('Swiper JS', 'custom-elementor-widgets')],
                    ['word_text' => __('Tailwind CSS', 'custom-elementor-widgets')],
                    ['word_text' => __('Marquee Effect', 'custom-elementor-widgets')],
                ],
                'title_field' => '{{{ word_text }}}',
            ]
        );

        $this->end_controls_section();

        // Style Tab: Word Style Section
        // =============================
        $this->start_controls_section(
            'section_word_style',
            [
                'label' => __('Word Style', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'word_typography',
                'label' => __('Typography', 'custom-elementor-widgets'),
                'selector' => '{{WRAPPER}} .crawler-word',
            ]
        );

        $this->add_control(
            'word_color',
            [
                'label' => __('Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .crawler-word' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'word_spacing',
            [
                'label' => __('Spacing Between Words', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                // This will be used by Swiper's spaceBetween option.
                // No direct selector here, it's passed to JS.
            ]
        );

        $this->add_responsive_control(
            'word_padding',
            [
                'label' => __('Padding Around Word', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '5',
                    'bottom' => '0',
                    'left' => '5',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );


        $this->end_controls_section();

        // Style Tab: Animation Settings Section
        // =====================================
        $this->start_controls_section(
            'section_animation_settings',
            [
                'label' => __('Animation Settings', 'custom-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE, // Or TAB_CONTENT if preferred
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => __('Pause on Hover', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-elementor-widgets'),
                'label_off' => __('No', 'custom-elementor-widgets'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );


        $this->add_control(
            'animation_duration_32',
            [
                'label' => __('Animation Speed (Duration)', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 500, // Minimum value
                'max' => 50000, // Maximum value
                'step' => 100, // Step increment
                'default' => 5000, // A default within min/max
                'description' => __('Controls the Swiper transition speed...', 'custom-elementor-widgets'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'test_number',
            [
                'label' => __('Test Number', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render crawler widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();

        if (empty($settings['marquee_words'])) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-crawler-widget-wrapper');
        $this->add_render_attribute('wrapper', 'class', 'overflow-hidden'); // Tailwind for hiding overflow

        // Swiper container attributes
        $swiper_container_id = 'crawler-swiper-' . $widget_id;
        $this->add_render_attribute('swiper_container', 'id', $swiper_container_id);
        $this->add_render_attribute('swiper_container', 'class', 'swiper-container');
        $this->add_render_attribute('swiper_container', 'class', 'crawler-swiper-container'); // For general styling
        $this->add_render_attribute('swiper_container', 'class', 'w-full'); // Tailwind: full width

        // Swiper wrapper attributes
        $this->add_render_attribute('swiper_wrapper', 'class', 'swiper-wrapper');
        // Tailwind: flex items-center to vertically align if slide heights differ (though less common for text marquee)
        $this->add_render_attribute('swiper_wrapper', 'class', 'flex items-center');


?>
        <style>
            .crawler-slide {
                width: auto;
                /* Allow width to be determined by content */
                display: inline-block;
                /* Or block depending on layout */
                /* optional styles */
            }

            .outlined-text {
                /* font-size: 72px; */
                /* Large font */
                color: white !important;
                /* Text color */
                -webkit-text-stroke: 1px black;
                /* Outline for WebKit browsers */
                text-stroke: 1px black;
                /* Standard (not widely supported yet) */
                /* font-weight: bold; */
                /* Optional: makes the text thicker */
            }

            .elementor-widget-crawler_widget .elementor-crawler-widget-wrapper {
                pointer-events: none;
            }
        </style>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <div <?php echo $this->get_render_attribute_string('swiper_container'); ?>>
                <div <?php echo $this->get_render_attribute_string('swiper_wrapper'); ?>>
                    <?php foreach ($settings['marquee_words'] as $index => $item) : ?>
                        <div class="swiper-slide" style="width: auto; display: inline-block;">
                            <?php // Apply Tailwind classes here for individual slide styling if needed, e.g., 'px-2 py-1'
                            // The `whitespace-nowrap` class ensures the text stays on one line.
                            ?>
                            <span class="crawler-word whitespace-nowrap <?php echo ($index % 2 !== 0) ? 'outlined-text' : ''; ?>">
                                <?php echo esc_html($item['word_text']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php // Initialize Swiper. It's better to move this to a separate JS file and enqueue it.
        // For simplicity in this example, it's inline.
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swiper === 'undefined') {
                    console.warn('Swiper JS not loaded. Crawler widget <?php echo esc_js($widget_id); ?> will not function.');
                    return;
                }

                const swiperInstance_<?php echo esc_js($widget_id); ?> = new Swiper('#<?php echo esc_js($swiper_container_id); ?>', {
                    loop: true,
                    slidesPerView: 'auto',
                    spaceBetween: <?php echo isset($settings['word_spacing']['size']) ? esc_js((int)$settings['word_spacing']['size']) : 15; ?>,
                    freeMode: {
                        enabled: true,
                        // sticky: true, // if you want it to snap to slides after drag (not typical for marquee)
                        momentum: false, // Disables the momentum effect for a more consistent speed
                    },
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: true,
                        pauseOnMouseEnter: <?php echo esc_js($settings['pause_on_hover'] === 'true' ? 'true' : 'false'); ?>,
                    },
                    speed: <?php echo esc_js((int)$settings['animation_duration_32']); ?>,
                    grabCursor: false, // Usually false for marquees
                    watchSlidesProgress: true, // Might be useful for complex effects, not strictly needed here
                    allowTouchMove: false, // Disable manual swipe for a pure marquee

                    on: {
                        init: function(swiper) {
                            // Ensure linear transition timing for the wrapper
                            if (swiper.wrapperEl) {
                                swiper.wrapperEl.style.transitionTimingFunction = 'linear';
                            }
                        },
                        beforeTransitionStart: function(swiper, speed, internal) {
                            if (swiper.wrapperEl) {
                                swiper.wrapperEl.style.transitionTimingFunction = 'linear';
                            }
                        },
                        resize: function(swiper) {
                            if (swiper.wrapperEl) {
                                swiper.wrapperEl.style.transitionTimingFunction = 'linear';
                            }
                            swiper.update(); // Ensure Swiper recalculates on resize
                        }
                    }
                });
            });

            // For Elementor Editor Live Preview
            if (window.elementorFrontend && window.elementorFrontend.isEditMode()) {
                window.elementorFrontend.hooks.addAction('frontend/element_ready/<?php echo esc_js($this->get_name()); ?>.default', function($scope) {
                    // Re-initialize Swiper for the specific widget instance in the editor
                    // This is a simplified version, more robust handling might be needed
                    // depending on how Elementor handles widget re-rendering.
                    const widgetId = $scope.data('id');
                    const swiperContainer = $scope.find('#crawler-swiper-' + widgetId)[0];

                    if (swiperContainer && typeof Swiper !== 'undefined') {
                        // Check if a Swiper instance already exists and destroy it
                        if (swiperContainer.swiper) {
                            swiperContainer.swiper.destroy(true, true);
                        }

                        const settings = <?php
                                            // Pass all relevant frontend_available settings
                                            $js_settings = [];
                                            if (isset($settings['word_spacing']['size'])) $js_settings['spaceBetween'] = (int)$settings['word_spacing']['size'];
                                            else $js_settings['spaceBetween'] = 15;
                                            if (isset($settings['animation_duration_32'])) $js_settings['speed'] = (int)$settings['animation_duration_32'];
                                            else $js_settings['speed'] = 5000;
                                            if (isset($settings['pause_on_hover'])) $js_settings['pauseOnMouseEnter'] = ($settings['pause_on_hover'] === 'true');
                                            else $js_settings['pauseOnMouseEnter'] = true;
                                            echo json_encode($js_settings);
                                            ?>;

                        new Swiper(swiperContainer, {
                            loop: true,
                            slidesPerView: 'auto',
                            spaceBetween: settings.spaceBetween,
                            freeMode: {
                                enabled: true,
                                momentum: false,
                            },
                            autoplay: {
                                delay: 0,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: settings.pauseOnMouseEnter,
                            },
                            speed: settings.speed,
                            grabCursor: false,
                            allowTouchMove: false,
                            on: {
                                init: function(swiper) {
                                    if (swiper.wrapperEl) swiper.wrapperEl.style.transitionTimingFunction = 'linear';
                                },
                                beforeTransitionStart: function(swiper, speed, internal) {
                                    if (swiper.wrapperEl) swiper.wrapperEl.style.transitionTimingFunction = 'linear';
                                },
                                resize: function(swiper) {
                                    if (swiper.wrapperEl) swiper.wrapperEl.style.transitionTimingFunction = 'linear';
                                    swiper.update();
                                }
                            }
                        });
                    }
                });
            }
        </script>
    <?php
    }

    /**
     * Render crawler widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function content_template()
    {
    ?>
        <#
            var widgetId=elementorCommon.helpers.getUniqueId();
            var swiperContainerId='crawler-swiper-' + widgetId;

            if ( !settings.marquee_words || settings.marquee_words.length===0 ) {
            return;
            }

            view.addRenderAttribute( 'wrapper' , 'class' , 'elementor-crawler-widget-wrapper overflow-hidden' );
            view.addRenderAttribute( 'swiper_container' , 'id' , swiperContainerId );
            view.addRenderAttribute( 'swiper_container' , 'class' , [ 'swiper-container' , 'crawler-swiper-container' , 'w-full' ] );
            view.addRenderAttribute( 'swiper_wrapper' , 'class' , [ 'swiper-wrapper' , 'flex' , 'items-center' ] );

            var animationDuration=settings.animation_duration_32 ? parseInt(settings.animation_duration_32) : 5000;
            var spaceBetween=settings.word_spacing && settings.word_spacing.size ? parseInt(settings.word_spacing.size) : 15;
            var pauseOnHover=settings.pause_on_hover==='true' ;

            #>
            <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
                <div {{{ view.getRenderAttributeString( 'swiper_container' ) }}}>
                    <div {{{ view.getRenderAttributeString( 'swiper_wrapper' ) }}}>
                        <# _.each( settings.marquee_words, function( item, index ) { #>
                            <div class="swiper-slide crawler-slide" style="padding: {{ settings.word_padding.top + settings.word_padding.unit }} {{ settings.word_padding.right + settings.word_padding.unit }} {{ settings.word_padding.bottom + settings.word_padding.unit }} {{ settings.word_padding.left + settings.word_padding.unit }};">
                                <span class="crawler-word whitespace-nowrap">
                                    {{{ item.word_text }}}
                                </span>
                            </div>
                            <# } ); #>
                    </div>
                </div>
            </div>

            <?php // The JS for content_template is handled by the elementorFrontend.hooks.addAction in the render() method for edit mode.
            // However, to make controls that are `frontend_available: true` update live without full re-render,
            // you would typically listen to their change events in JS and update Swiper accordingly.
            // For this example, Elementor's default refresh on control change will re-run the script from render().
            ?>
    <?php
    }

    /**
     * Get Custom Help URL
     *
     * @return string Widget help URL.
     */
    public function get_custom_help_url()
    {
        return 'https://developers.elementor.com/docs/widgets/';
    }
}
