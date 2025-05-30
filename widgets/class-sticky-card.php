<?php


/**
 * Sticky Card Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */
if (! defined('ABSPATH')) {
    exit;
}

class Sticky_Card_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'sticky_card_widget';
    }

    public function get_title()
    {
        return __('Sticky Card', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-sticky';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Sticky Card Content', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Card Title', 'custom-elementor-widgets'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sticky Card', 'custom-elementor-widgets'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'   => __('Card Description', 'custom-elementor-widgets'),
                'type'    => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('This card stays fixed within this container while scrolling.', 'custom-elementor-widgets'),
            ]
        );

        $this->add_responsive_control(
            'wrapper_width',
            [
                'label' => __('Wrapper Width', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'default' => [
                    'unit' => 'px',
                    'size' => 1000,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sticky-card-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_height',
            [
                'label' => __('Wrapper Height', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sticky-card-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
?>
        <style>
            .sticky-card {
                position: -webkit-sticky;
                position: sticky;
                top: 20px;
                width: 300px;
                border-radius: 8px;
                height: fit-content;
            }
        </style>

        <div class="sticky-card-wrapper flex-col" >
            <div class="sticky-card">
                <h3><?php echo esc_html($settings['title']); ?></h3>
                <p><?php echo esc_html($settings['description']); ?></p>
                <button class="flex bg-black text-white px-5 py-4 mt-5">
                    <span class="mr-3">Let's talk now</span>
                    <i data-lucide="mail"></i>
                </button>
            </div>
        </div>

        
<?php
    }
}
