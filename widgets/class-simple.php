<?php
/**
 * Simple Elementor Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Simple_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'simple_widget';
    }

    public function get_title() {
        return __( 'Simple Widget', 'custom-elementor-widgets' );
    }

    public function get_icon() {
        return 'eicon-editor-code';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'custom-elementor-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __( 'Title', 'custom-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Default Title', 'custom-elementor-widgets' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'   => __( 'Description', 'custom-elementor-widgets' ),
                'type'    => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Default description', 'custom-elementor-widgets' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="custom-simple-widget">
            <h2><?php echo esc_html( $settings['title'] ); ?></h2>
            <p><?php echo esc_html( $settings['description'] ); ?></p>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
        var title = settings.title;
        var description = settings.description;
        #>
        <div class="custom-simple-widget">
            <h2>{{{ title }}}</h2>
            <p>{{{ description }}}</p>
        </div>
        <?php
    }
}