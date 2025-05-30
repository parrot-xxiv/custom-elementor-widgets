<?php

namespace Custom;

class Widget_Loader {

    public static function register() {
        add_action( 'elementor/widgets/widgets_registered', [ __CLASS__, 'load_widgets' ] );
    }

    public static function load_widgets() {
        $widgets = [
            'class-simple.php',
            'class-image-transition.php',
            'class-sticky-card.php',
            'class-parallax.php',
            'class-case-study.php',
            'class-rolling-numbers.php',
            'class-crawler.php',
            'class-html-tag.php'
            // .. add other widgets
        ];

        foreach ( $widgets as $file ) {
            require_once CEW_PLUGIN_PATH . 'widgets/' . $file;
        }

        $manager = \Elementor\Plugin::instance()->widgets_manager;
        $manager->register( new \Simple_Widget() );
        $manager->register( new \Image_Transition_Loop_Widget() );
        $manager->register( new \Sticky_Card_Widget() );
        $manager->register( new \Parallax_Widget() );
        $manager->register( new \Case_Study_Widget() );
        $manager->register( new \Rolling_Number_Widget() );
        $manager->register( new \Crawler_Widget() );
        $manager->register( new \HTML_Tag_Widget() );

        // .. add other widgets
    }
}
