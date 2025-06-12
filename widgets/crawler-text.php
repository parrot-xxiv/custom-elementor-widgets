<?php
defined('ABSPATH') || exit;

class Crawler_Text extends \Elementor\Widget_Base {
    public function get_categories() {
        return ['basic']; // You can create a custom category
    }

    public function get_icon() {
        return 'eicon-editor-code'; // You can change this to a more fitting icon e.g., 'eicon-slider-push'
    }

    public function get_name() {
        return 'crawler_text';
    }

    public function get_script_depends() {
        return ['cew-gsap', 'cew-crawler-text'];
    }

    public function get_style_depends() {
        return ['cew-crawler-text'];
    }

    public function get_title() {
        return __('Crawler Text', 'custom-elementor-widgets');
    }

    protected function content_template() {
        ?>
    <#
    if ( ! settings.marquee_words || ! settings.marquee_words.length ) {
        return;
    }
    #>

    <div class="crawler-wrapper"
         data-duration="{{ settings.animation_duration_32 }}"
         data-pause="{{ settings.pause_on_hover ? 'true' : 'false' }}"
         style="--spacing: {{ settings.word_spacing.size }}px;">
        <div class="crawler-track">
            <# _.each( settings.marquee_words, function( item, index ) { #>
                <div class="crawler-words-container">
                    <span class="crawler-word <# if ( index % 2 !== 0 ) { #>outlined-text<# } #>">
                        {{{ item.word_text }}}
                    </span>
                </div>
            <# }); #>
        </div>
    </div>
    <?php
}

    protected function register_controls() {

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
                    ['word_text' => __('Crawler Effect', 'custom-elementor-widgets')],
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

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['marquee_words'])) {
            return;
        }

        $this->add_render_attribute('crawler-wrapper', 'class', 'crawler-wrapper');
        $this->add_render_attribute('crawler-wrapper', 'data-duration', $settings['animation_duration_32']);
        $this->add_render_attribute('crawler-wrapper', 'data-pause', $settings['pause_on_hover'] === 'true' ? 'true' : 'false');
        $this->add_render_attribute('crawler-wrapper', 'style', '--spacing: ' . $settings['word_spacing']['size'] . 'px');

        ?>
        <div <?php echo $this->get_render_attribute_string('crawler-wrapper'); ?>>
            <div class="crawler-track">
                <?php foreach ($settings['marquee_words'] as $index => $item): ?>
                    <div class="crawler-words-container">
                        <span class="crawler-word <?php echo($index % 2 !== 0) ? 'outlined-text' : ''; ?>">
                            <?php echo esc_html($item['word_text']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}