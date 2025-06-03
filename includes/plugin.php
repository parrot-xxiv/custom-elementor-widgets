<?php

namespace CustomElementorWidgets;

defined('ABSPATH') || exit;

class Plugin {
    private static $instance = null;

    public static function get_file_name_from_class($class) {
        // Convert to lowercase-hyphen format e.g., Hello_World_Widget => hello-world-widget.php
        return strtolower(str_replace('_', '-', $class)) . '.php';
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function get_widgets() {
        $files = glob(CEW_PLUGIN_PATH . 'widgets/*.php');
        $widgets = [];

        foreach ($files as $file) {
            $contents = file_get_contents($file);
            if (preg_match('/class\s+(\w+)\s+extends\s+\\\?Elementor\\\Widget_Base/', $contents, $matches)) {
                $widgets[] = $matches[1];
            }
        }

        return $widgets;
    }

    public function init() {
        // add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        add_action('elementor/widgets/register', [__CLASS__, 'register_widgets']);
        add_action('elementor/frontend/after_register_scripts', [__CLASS__, 'register_js_files']);
        add_action('elementor/frontend/after_register_styles', [__CLASS__, 'register_css_files']);
    }

    public static function register_css_files() {
        $css_dir = CEW_PLUGIN_PATH . 'assets/css/';
        $css_url = CEW_PLUGIN_URL . 'assets/css/';

        wp_register_style("cew-swiper", "https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css");

        // Check if the folder exists
        if (is_dir($css_dir)) {
            $css_files = glob($css_dir . '*.css');
            foreach ($css_files as $css_file) {
                $handle = 'cew-' . basename($css_file, '.css');
                wp_register_style($handle, $css_url . basename($css_file), [], filemtime($css_file));
            }
        }
    }

    public static function register_js_files() {
        $js_dir = CEW_PLUGIN_PATH . 'assets/js/';
        $js_url = CEW_PLUGIN_URL . 'assets/js/';

        wp_register_script('cew-gsap', "https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js", [], false, ['strategy' => 'defer']);
        wp_register_script('cew-scrolltrigger', "https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js", [], false, ['strategy' => 'defer']);
        wp_register_script('cew-swiper', "https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js", [], false, ['strategy' => 'defer']);
        wp_register_script('cew-parallax', "https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js", [], false, ['strategy' => 'defer']);

        $dependencies = [
            // 'image-transition-loop.js' => ['cew-swiper'],
            // Add more as needed
        ];

        if (is_dir($js_dir)) {
            foreach (glob($js_dir . '*.js') as $js_file) {
                $filename = basename($js_file);
                $handle = 'cew-' . basename($filename, '.js');
                $deps = $dependencies[$filename] ?? [];

                wp_register_script(
                    $handle,
                    $js_url . $filename,
                    $deps,
                    filemtime($js_file),
                    ['strategy' => 'defer']
                );
            }
        }
    }

    public static function register_widgets($widgets_manager) {
        $enabled_widgets = get_option('cew_enabled_widgets', []);

        foreach (Plugin::get_widgets() as $widget_class) {
            if (in_array($widget_class, $enabled_widgets)) {
                $widget_file = CEW_PLUGIN_PATH . 'widgets/' . Plugin::get_file_name_from_class($widget_class);
                if (file_exists($widget_file)) {
                    require_once $widget_file;
                    $widgets_manager->register(new $widget_class());
                } else {
                    error_log("Widget file not found: $widget_file");
                }
            }
        }
    }

    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }
}
