<?php
/**
 * Plugin Name:     Custom Elementor Widgets
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     custom-elementor-widgets
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Custom_Elementor_Widgets
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'CEW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CEW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Autoload core classes
require_once CEW_PLUGIN_PATH . 'includes/class-cew-plugin.php';
require_once CEW_PLUGIN_PATH . 'includes/shortcode-sticky.php';
require_once CEW_PLUGIN_PATH . 'includes/shortcode-theme-toggle.php';
require_once CEW_PLUGIN_PATH . 'includes/shortcode-number-reel.php';
require_once CEW_PLUGIN_PATH . 'includes/shortcode-footer.php';

// Run the plugin
add_action( 'plugins_loaded', [ 'Custom\Plugin', 'init' ] );