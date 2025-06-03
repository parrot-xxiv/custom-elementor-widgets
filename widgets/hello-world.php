<?php

defined('ABSPATH') || exit;

class Hello_World extends \Elementor\Widget_Base {
    public function get_name() {
        return 'hello_world';
    }

    public function get_title() {
        return 'Hello World';
    }

    public function get_icon() {
        return 'eicon-editor-code';
    }

    public function get_categories() {
        return ['basic'];
    }

    public function get_script_depends() {
        return ['cew-hello-world'];
    }

    public function get_style_depends() {
        return ['cew-hello-world'];
    }

    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => 'Content',
        ]);

        $this->add_control('text', [
            'label' => 'Text',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Hello, world!',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        echo '<div>' . $this->get_settings('text') . '</div>';
    }
}
