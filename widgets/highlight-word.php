<?php

defined('ABSPATH') || exit;

class Highlight_Word extends \Elementor\Widget_Base {

    public function get_name() {
        return 'highlight_word';
    }

    public function get_title() {
        return 'Highlight Word';
    }

    public function get_icon() {
        return 'eicon-text-field';
    }

    public function get_categories() {
        return ['basic'];
    }

    public function get_script_depends() {
        return ['cew-gsap', 'cew-splittext', 'cew-scrolltrigger','cew-highlight-word'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label' => 'Heading Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'This is a sample heading',
                'placeholder' => 'Enter your heading text',
            ]
        );

        $this->add_control(
            'highlight_word',
            [
                'label' => 'Word to Highlight',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'sample',
                'placeholder' => 'Enter word to highlight',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .highlight-heading',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Highlight Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'highlight_color',
            [
                'label' => 'Highlight Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffeb3b',
            ]
        );

        $this->add_control(
            'stroke_angle',
            [
                'label' => 'Stroke Angle',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => -45,
                        'max' => 45,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => -5,
                ],
            ]
        );

        $this->add_control(
            'brush_style',
            [
                'label' => 'Brush Style',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'underline' => 'Underline',
                    'circle' => 'Circle',
                    'highlight' => 'Highlight',
                    'strikethrough' => 'Strikethrough',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function content_template() {
        ?>
        <#
        var headingText = settings.heading_text;
        var highlightWord = settings.highlight_word;
        var highlightColor = settings.highlight_color;
        var strokeAngle = settings.stroke_angle.size + settings.stroke_angle.unit;
        var brushStyle = settings.brush_style;
        #>
        <div class="highlight-word-widget" 
             data-highlight-word="{{{ highlightWord }}}"
             data-highlight-color="{{{ highlightColor }}}"
             data-stroke-angle="{{{ strokeAngle }}}"
             data-brush-style="{{{ brushStyle }}}">
            <h2 class="highlight-heading">{{{ headingText }}}</h2>
        </div>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $heading_text = $settings['heading_text'];
        $highlight_word = $settings['highlight_word'];
        $highlight_color = $settings['highlight_color'];
        $stroke_angle = $settings['stroke_angle']['size'] . $settings['stroke_angle']['unit'];
        $brush_style = $settings['brush_style'];
        ?>
        <div class="highlight-word-widget" 
             data-highlight-word="<?php echo esc_attr($highlight_word); ?>"
             data-highlight-color="<?php echo esc_attr($highlight_color); ?>"
             data-stroke-angle="<?php echo esc_attr($stroke_angle); ?>"
             data-brush-style="<?php echo esc_attr($brush_style); ?>">
            <h2 class="highlight-heading"><?php echo esc_html($heading_text); ?></h2>
        </div>
        <?php
    }
}