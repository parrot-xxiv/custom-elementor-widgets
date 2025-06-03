<?php

namespace CustomElementorWidgets;

defined('ABSPATH') || exit;

class Admin {
    private static $instance = null;

    public static function add_admin_menu() {
        add_menu_page(
            'Elementor Widgets', // Page title
            'Manage Widgets', // Menu title
            'manage_options', // Capability
            'cew-widgets', // Menu slug
            [__CLASS__, 'render_admin_page'], // Function to render the page
            'dashicons-admin-generic', // Icon
            99// Position
        );
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
    }

    public static function render_admin_page() {
        $available_widgets = Plugin::get_widgets();
        $enabled_widgets = get_option('cew_enabled_widgets', []);

        if (isset($_POST['cew_save_widgets'])) {
            check_admin_referer('cew_save_widgets');
            // Sanitize and validate input
            $enabled_widgets = isset($_POST['cew_widgets']) ? array_map('sanitize_text_field', $_POST['cew_widgets']) : [];
            update_option('cew_enabled_widgets', $enabled_widgets);
            echo '<div class="updated"><p>Settings saved.</p></div>';
        }

        echo '<div class="wrap"><h1>Select Widgets from Custom Elementor Widgets</h1>';
        echo '<form method="post">';
        wp_nonce_field('cew_save_widgets');
        echo '<ul>';
        foreach ($available_widgets as $widget_class) {
            $checked = in_array($widget_class, $enabled_widgets) ? 'checked' : '';
            echo "<li><label><input type='checkbox' name='cew_widgets[]' value='$widget_class' $checked> $widget_class</label></li>";
        }
        echo '</ul>';
        echo '<input type="submit" name="cew_save_widgets" value="Save" class="button-primary">';
        echo '</form>';
        if (!did_action('elementor/loaded')) {
            echo '<div class="notice notice-error"><p>Elementor is not active.</p></div>';
            return;
        }

        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
        $widgets = $widgets_manager->get_widget_types();

        echo '<div class="wrap"><h1>Registered Elementor Widget IDs</h1>';
        echo '<textarea readonly style="width:100%;height:400px;font-family:monospace;">';

        foreach ($widgets as $widget) {
            echo esc_html($widget->get_name()) . "\n";
        }

        echo '</textarea></div>';
        echo '</div>';
    }

    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }
}
