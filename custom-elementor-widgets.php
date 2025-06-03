<?php
/**
 * Plugin Name:     Custom Elementor Widgets
 * Plugin URI:      https://github.com/parrot-xxiv/custom-elementor-widgets
 * Description:     Custom Elementor Widgets
 * Author:          Eldren Par
 * Text Domain:     custom-elementor-widgets
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Custom_Elementor_Widgets
 */

defined('ABSPATH') || exit;

define('CEW_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CEW_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once CEW_PLUGIN_PATH . 'includes/plugin.php';
require_once CEW_PLUGIN_PATH . 'includes/admin.php';

CustomElementorWidgets\Plugin::get_instance();
CustomElementorWidgets\Admin::get_instance();