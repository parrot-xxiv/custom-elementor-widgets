<?php
namespace CustomElementorWidgets;

defined('ABSPATH') || exit;

class Admin {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'handle_form_submission']);
        
        // Hook early to manage widgets and categories
        add_action('elementor/widgets/widgets_registered', [$this, 'manage_elementor_widgets'], 5);
        add_action('elementor/widgets/widgets_registered', [$this, 'manage_elementor_categories'], 10);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Elementor Widgets Manager', // Page title
            'Widget Manager', // Menu title
            'manage_options', // Capability
            'cew-widgets', // Menu slug
            [$this, 'render_admin_page'], // Function to render the page
            'dashicons-admin-generic', // Icon
            99 // Position
        );
    }
    
    public function handle_form_submission() {
        if (isset($_POST['cew_save_widgets']) && check_admin_referer('cew_save_widgets')) {
            // Handle custom widgets
            $enabled_custom_widgets = isset($_POST['cew_custom_widgets']) ? 
                array_map('sanitize_text_field', $_POST['cew_custom_widgets']) : [];
            update_option('cew_enabled_custom_widgets', $enabled_custom_widgets);
            
            // Handle all Elementor widgets
            $disabled_elementor_widgets = isset($_POST['cew_disabled_elementor_widgets']) ? 
                array_map('sanitize_text_field', $_POST['cew_disabled_elementor_widgets']) : [];
            update_option('cew_disabled_elementor_widgets', $disabled_elementor_widgets);
            
            // Handle widget categories
            $disabled_categories = isset($_POST['cew_disabled_categories']) ? 
                array_map('sanitize_text_field', $_POST['cew_disabled_categories']) : [];
            update_option('cew_disabled_categories', $disabled_categories);
            
            // Clear Elementor cache when settings change
            $this->clear_elementor_cache();
            
            add_action('admin_notices', [$this, 'show_success_notice']);
        }
    }
    
    public function show_success_notice() {
        echo '<div class="notice notice-success is-dismissible"><p>Widget settings saved successfully!</p></div>';
    }
    
    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            echo '<div class="wrap">';
            echo '<h1>Elementor Widget Manager</h1>';
            echo '<div class="notice notice-error"><p><strong>Error:</strong> Elementor is not active. Please activate Elementor to use this feature.</p></div>';
            echo '</div>';
            return;
        }
        
        $this->render_page_content();
    }
    
    private function render_page_content() {
        $custom_widgets = $this->get_custom_widgets();
        $elementor_widgets = $this->get_all_elementor_widgets();
        $elementor_categories = $this->get_all_elementor_categories();
        
        $enabled_custom_widgets = get_option('cew_enabled_custom_widgets', []);
        $disabled_elementor_widgets = get_option('cew_disabled_elementor_widgets', []);
        $disabled_categories = get_option('cew_disabled_categories', []);
        
        ?>
        <div class="wrap">
            <h1>Elementor Widget Manager</h1>
            
            <div class="nav-tab-wrapper" style="margin-bottom: 20px;">
                <a href="#widgets-tab" class="nav-tab nav-tab-active" onclick="switchTab(event, 'widgets-tab')">Widgets</a>
                <a href="#categories-tab" class="nav-tab" onclick="switchTab(event, 'categories-tab')">Categories</a>
                <a href="#debug-tab" class="nav-tab" onclick="switchTab(event, 'debug-tab')">Debug</a>
            </div>
            
            <form method="post" action="">
                <?php wp_nonce_field('cew_save_widgets'); ?>
                
                <!-- Widgets Tab -->
                <div id="widgets-tab" class="tab-content" style="display: block;">
                    <div style="display: flex; gap: 20px;">
                        <!-- Custom Widgets Section -->
                        <div style="flex: 1;">
                            <h2>Custom Widgets</h2>
                            <p>Select which custom widgets to enable:</p>
                            
                            <?php if (empty($custom_widgets)): ?>
                                <p><em>No custom widgets found. Make sure your custom widgets are properly registered.</em></p>
                            <?php else: ?>
                                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                                    <p style="margin-top: 0;"><button type="button" onclick="toggleAll('cew_custom_widgets', true)" class="button button-small">Select All</button>
                                    <button type="button" onclick="toggleAll('cew_custom_widgets', false)" class="button button-small">Deselect All</button></p>
                                    <?php foreach ($custom_widgets as $widget_class => $widget_name): ?>
                                        <label style="display: block; margin-bottom: 5px;">
                                            <input type="checkbox" 
                                                   name="cew_custom_widgets[]" 
                                                   value="<?php echo esc_attr($widget_class); ?>"
                                                   <?php checked(in_array($widget_class, $enabled_custom_widgets)); ?>>
                                            <strong><?php echo esc_html($widget_name); ?></strong>
                                            <br><small style="color: #666; margin-left: 20px;"><?php echo esc_html($widget_class); ?></small>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- All Elementor Widgets Section -->
                        <div style="flex: 1;">
                            <h2>Elementor Widgets</h2>
                            <p>Select widgets to <strong style="color: #d63384;">disable</strong> (checked = disabled):</p>
                            
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                                <p style="margin-top: 0;"><button type="button" onclick="toggleAll('cew_disabled_elementor_widgets', true)" class="button button-small">Disable All</button>
                                <button type="button" onclick="toggleAll('cew_disabled_elementor_widgets', false)" class="button button-small">Enable All</button></p>
                                <?php 
                                $grouped_widgets = $this->group_widgets_by_category($elementor_widgets);
                                foreach ($grouped_widgets as $category => $widgets): ?>
                                    <h4 style="margin: 15px 0 5px 0; color: #0073aa; border-bottom: 1px solid #ddd;"><?php echo esc_html($category); ?></h4>
                                    <?php foreach ($widgets as $widget_id => $widget_title): ?>
                                        <label style="display: block; margin-bottom: 3px; margin-left: 10px;">
                                            <input type="checkbox" 
                                                   name="cew_disabled_elementor_widgets[]" 
                                                   value="<?php echo esc_attr($widget_id); ?>"
                                                   <?php checked(in_array($widget_id, $disabled_elementor_widgets)); ?>>
                                            <?php echo esc_html($widget_title); ?>
                                            <small style="color: #666;">(<?php echo esc_html($widget_id); ?>)</small>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Categories Tab -->
                <div id="categories-tab" class="tab-content" style="display: none;">
                    <h2>Widget Categories Management</h2>
                    <p>Select categories to <strong style="color: #d63384;">disable</strong> (this will hide entire widget categories):</p>
                    
                    <div style="max-width: 600px;">
                        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; background: #f9f9f9;">
                            <p style="margin-top: 0;"><button type="button" onclick="toggleAll('cew_disabled_categories', true)" class="button button-small">Disable All</button>
                            <button type="button" onclick="toggleAll('cew_disabled_categories', false)" class="button button-small">Enable All</button></p>
                            
                            <?php foreach ($elementor_categories as $category_id => $category_data): ?>
                                <label style="display: block; margin-bottom: 8px; padding: 5px; background: white; border-radius: 3px;">
                                    <input type="checkbox" 
                                           name="cew_disabled_categories[]" 
                                           value="<?php echo esc_attr($category_id); ?>"
                                           <?php checked(in_array($category_id, $disabled_categories)); ?>>
                                    <strong><?php echo esc_html($category_data['title']); ?></strong>
                                    <br><small style="color: #666; margin-left: 20px;">ID: <?php echo esc_html($category_id); ?></small>
                                    <?php if (!empty($category_data['widgets'])): ?>
                                        <br><small style="color: #666; margin-left: 20px;">Widgets: <?php echo implode(', ', array_slice($category_data['widgets'], 0, 5)); ?><?php echo count($category_data['widgets']) > 5 ? '... +' . (count($category_data['widgets']) - 5) . ' more' : ''; ?></small>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Debug Tab -->
                <div id="debug-tab" class="tab-content" style="display: none;">
                    <h2>Debug Information</h2>
                    
                    <h3>All Registered Widgets</h3>
                    <textarea readonly style="width:100%;height:200px;font-family:monospace;"><?php
                        foreach ($elementor_widgets as $widget_id => $widget_data) {
                            echo esc_html($widget_id) . " - " . esc_html($widget_data['title']) . " [" . esc_html($widget_data['category']) . "]\n";
                        }
                    ?></textarea>
                    
                    <h3>All Categories</h3>
                    <textarea readonly style="width:100%;height:150px;font-family:monospace;"><?php
                        foreach ($elementor_categories as $category_id => $category_data) {
                            echo esc_html($category_id) . " - " . esc_html($category_data['title']) . " (" . count($category_data['widgets']) . " widgets)\n";
                        }
                    ?></textarea>
                    
                    <h3>Current Settings</h3>
                    <p><strong>Disabled Widgets:</strong> <?php echo empty($disabled_elementor_widgets) ? 'None' : implode(', ', $disabled_elementor_widgets); ?></p>
                    <p><strong>Disabled Categories:</strong> <?php echo empty($disabled_categories) ? 'None' : implode(', ', $disabled_categories); ?></p>
                    <p><strong>Enabled Custom Widgets:</strong> <?php echo empty($enabled_custom_widgets) ? 'None' : implode(', ', $enabled_custom_widgets); ?></p>
                </div>
                
                <p style="margin-top: 20px;">
                    <input type="submit" name="cew_save_widgets" value="Save Settings" class="button-primary">
                    <button type="button" onclick="location.reload()" class="button">Refresh Page</button>
                </p>
            </form>
        </div>
        
        <style>
        .tab-content { display: none; }
        .nav-tab-active { background: #fff !important; border-bottom: 1px solid #fff !important; }
        </style>
        
        <script>
        function switchTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("nav-tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("nav-tab-active");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.classList.add("nav-tab-active");
            evt.preventDefault();
        }
        
        function toggleAll(name, check) {
            var checkboxes = document.querySelectorAll('input[name="' + name + '[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = check;
            });
        }
        </script>
        <?php
    }
    
    private function get_custom_widgets() {
        $custom_widgets = [];
        
        // Try to get custom widgets from your Plugin class if it exists
        if (class_exists(__NAMESPACE__ . '\Plugin')) {
            $widgets = Plugin::get_widgets();
            foreach ($widgets as $widget_class) {
                $custom_widgets[$widget_class] = $this->get_widget_display_name($widget_class);
            }
        }
        
        // Alternative: scan for custom widget classes in your plugin
        $plugin_dir = dirname(__FILE__, 2);
        $widget_dirs = [$plugin_dir . '/widgets/', $plugin_dir . '/includes/widgets/'];
        
        foreach ($widget_dirs as $dir) {
            if (is_dir($dir)) {
                $custom_widget_files = glob($dir . '*.php');
                foreach ($custom_widget_files as $file) {
                    $widget_class = $this->extract_widget_class_from_file($file);
                    if ($widget_class && !isset($custom_widgets[$widget_class])) {
                        $custom_widgets[$widget_class] = $this->get_widget_display_name($widget_class);
                    }
                }
            }
        }
        
        return $custom_widgets;
    }
    
    private function get_all_elementor_widgets() {
        $widgets = [];
        
        try {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            $registered_widgets = $widgets_manager->get_widget_types();
            
            foreach ($registered_widgets as $widget) {
                $widgets[$widget->get_name()] = [
                    'title' => $widget->get_title(),
                    'category' => $this->get_widget_category_title($widget->get_categories()[0] ?? 'general')
                ];
            }
            
        } catch (Exception $e) {
            error_log('Error getting Elementor widgets: ' . $e->getMessage());
        }
        
        return $widgets;
    }
    
    private function get_all_elementor_categories() {
        $categories = [];
        
        try {
            $elements_manager = \Elementor\Plugin::instance()->elements_manager;
            $registered_categories = $elements_manager->get_categories();
            $widgets = $this->get_all_elementor_widgets();
            
            foreach ($registered_categories as $category_id => $category_data) {
                $category_widgets = [];
                foreach ($widgets as $widget_id => $widget_data) {
                    if ($widget_data['category'] === $category_data['title']) {
                        $category_widgets[] = $widget_data['title'];
                    }
                }
                
                $categories[$category_id] = [
                    'title' => $category_data['title'],
                    'widgets' => $category_widgets
                ];
            }
            
        } catch (Exception $e) {
            error_log('Error getting Elementor categories: ' . $e->getMessage());
        }
        
        return $categories;
    }
    
    private function get_widget_category_title($category_id) {
        try {
            $elements_manager = \Elementor\Plugin::instance()->elements_manager;
            $categories = $elements_manager->get_categories();
            return isset($categories[$category_id]) ? $categories[$category_id]['title'] : ucfirst(str_replace(['-', '_'], ' ', $category_id));
        } catch (Exception $e) {
            return ucfirst(str_replace(['-', '_'], ' ', $category_id));
        }
    }
    
    private function group_widgets_by_category($widgets) {
        $grouped = [];
        foreach ($widgets as $widget_id => $widget_data) {
            $category = $widget_data['category'];
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][$widget_id] = $widget_data['title'];
        }
        ksort($grouped);
        return $grouped;
    }
    
    private function get_widget_display_name($widget_class) {
        $class_name = basename(str_replace('\\', '/', $widget_class));
        return ucwords(str_replace(['_', '-'], ' ', $class_name));
    }
    
    private function extract_widget_class_from_file($file) {
        $content = file_get_contents($file);
        if (preg_match('/class\s+(\w+)\s+extends.*Widget_Base/', $content, $matches)) {
            return __NAMESPACE__ . '\\' . $matches[1];
        }
        return null;
    }
    
    /**
     * Manage Elementor widgets - RUNS EARLY to properly unregister
     */
    public function manage_elementor_widgets() {
        $disabled_widgets = get_option('cew_disabled_elementor_widgets', []);
        
        if (!empty($disabled_widgets)) {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            foreach ($disabled_widgets as $widget_id) {
                $widgets_manager->unregister_widget_type($widget_id);
            }
        }
        
        // Register enabled custom widgets
        $this->register_custom_widgets();
    }
    
    /**
     * Manage Elementor categories - Hide widgets from disabled categories
     */
    public function manage_elementor_categories() {
        $disabled_categories = get_option('cew_disabled_categories', []);
        
        if (!empty($disabled_categories)) {
            // Get all widgets and disable those in disabled categories
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            $all_widgets = $widgets_manager->get_widget_types();
            
            foreach ($all_widgets as $widget) {
                $widget_categories = $widget->get_categories();
                // If any of the widget's categories are disabled, unregister the widget
                if (!empty(array_intersect($widget_categories, $disabled_categories))) {
                    $widgets_manager->unregister_widget_type($widget->get_name());
                }
            }
        }
    }
    
    /**
     * Register enabled custom widgets
     */
    private function register_custom_widgets() {
        $enabled_widgets = get_option('cew_enabled_custom_widgets', []);
        
        if (!empty($enabled_widgets)) {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            foreach ($enabled_widgets as $widget_class) {
                if (class_exists($widget_class)) {
                    $widgets_manager->register_widget_type(new $widget_class());
                }
            }
        }
    }
    
    /**
     * Force refresh Elementor cache after settings change
     */
    public function clear_elementor_cache() {
        if (class_exists('\Elementor\Plugin')) {
            try {
                // Clear files cache
                \Elementor\Plugin::instance()->files_manager->clear_cache();
                
                // Clear widgets cache
                if (method_exists(\Elementor\Plugin::instance()->widgets_manager, 'clear_cache')) {
                    \Elementor\Plugin::instance()->widgets_manager->clear_cache();
                }
                
                // Force refresh by updating a transient
                delete_transient('elementor_remote_info_api_data');
                
            } catch (Exception $e) {
                error_log('Error clearing Elementor cache: ' . $e->getMessage());
            }
        }
    }
}
