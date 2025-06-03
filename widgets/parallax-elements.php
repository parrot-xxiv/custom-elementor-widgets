<?php
defined('ABSPATH') || exit;

class Parallax_Elements extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'parallax_controller';
    }
    
    public function get_title() {
        return __('Parallax Controller', 'your-plugin');
    }
    
    public function get_icon() {
        return 'eicon-animation';
    }
    
    public function get_categories() {
        return ['basic'];
    }
    
    public function get_script_depends() {
        return ['parallax-js', 'cew-parallax'];
    }
    
    public function get_keywords() {
        return ['parallax', 'animation', 'scroll', 'motion'];
    }
    
    protected function register_controls() {
        // Parallax Settings Section
        $this->start_controls_section('parallax_settings', [
            'label' => __('Parallax Settings', 'your-plugin'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('scene_id', [
            'label' => __('Scene Container ID', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'my-scene-id',
            'description' => __('Enter the ID without the # symbol', 'your-plugin'),
            'ai' => [
                'active' => false,
            ],
        ]);
        
        // Parallax Configuration
        $this->add_control('parallax_config_heading', [
            'label' => __('Parallax Configuration', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        
        $this->add_control('relative_input', [
            'label' => __('Relative Input', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __('Enable relative input for mouse movement', 'your-plugin'),
        ]);
        
        $this->add_control('hover_only', [
            'label' => __('Hover Only', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Only animate on hover', 'your-plugin'),
        ]);
        
        $this->add_control('clip_relative_input', [
            'label' => __('Clip Relative Input', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Clip relative input to element bounds', 'your-plugin'),
        ]);
        
        // Layers Repeater
        $this->add_control('layers_heading', [
            'label' => __('Parallax Layers', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control('layer_id', [
            'label' => __('Layer Element ID', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'layer-1',
            'description' => __('Enter the ID without the # symbol', 'your-plugin'),
        ]);
        
        $repeater->add_control('depth', [
            'label' => __('Depth', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0.3,
            'min' => -1,
            'max' => 1,
            'step' => 0.1,
            'description' => __('Negative values invert the parallax direction', 'your-plugin'),
        ]);
        
        $repeater->add_control('layer_description', [
            'label' => __('Description', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __('Layer description (optional)', 'your-plugin'),
        ]);
        
        $this->add_control('layers', [
            'label' => __('Layers', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ layer_id }}} - Depth: {{{ depth }}}',
            'prevent_empty' => false,
        ]);
        
        $this->end_controls_section();
        
        // Advanced Settings Section
        $this->start_controls_section('advanced_settings', [
            'label' => __('Advanced Settings', 'your-plugin'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]);
        
        $this->add_control('custom_css_class', [
            'label' => __('Custom CSS Class', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => __('Add custom CSS class to the widget', 'your-plugin'),
        ]);
        
        $this->add_control('debug_mode', [
            'label' => __('Debug Mode', 'your-plugin'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Enable console logging for debugging', 'your-plugin'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $scene_id = sanitize_html_class($settings['scene_id']);
        $widget_id = $this->get_id();
        
        // Validation
        if (empty($scene_id)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . 
                     __('Please enter a Scene Container ID in the widget settings.', 'your-plugin') . 
                     '</div>';
            }
            return;
        }
        
        if (empty($settings['layers'])) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . 
                     __('Add some parallax layers to see the effect.', 'your-plugin') . 
                     '</div>';
            }
            return;
        }
        
        // Prepare configuration
        $config = [
            'relativeInput' => $settings['relative_input'] === 'yes',
            'hoverOnly' => $settings['hover_only'] === 'yes',
            'clipRelativeInput' => $settings['clip_relative_input'] === 'yes',
            'selector' => '.parallax-layer-' . $widget_id,
        ];
        
        // Prepare layers data
        $layers_data = [];
        foreach ($settings['layers'] as $layer) {
            if (!empty($layer['layer_id'])) {
                $layers_data[] = [
                    'id' => sanitize_html_class($layer['layer_id']),
                    'depth' => floatval($layer['depth']),
                ];
            }
        }
        
        if (empty($layers_data)) {
            return;
        }
        
        // Output widget container
        $css_classes = ['parallax-controller-widget'];
        if (!empty($settings['custom_css_class'])) {
            $css_classes[] = sanitize_html_class($settings['custom_css_class']);
        }
        
        echo '<div class="' . esc_attr(implode(' ', $css_classes)) . '" data-widget-id="' . esc_attr($widget_id) . '">';
        
        // Add inline script
        $this->render_script($scene_id, $widget_id, $config, $layers_data, $settings['debug_mode'] === 'yes');
        
        echo '</div>';
    }
    
    protected function render_script($scene_id, $widget_id, $config, $layers_data, $debug = false) {
        static $parallax_loaded = false;
        
        // Load Parallax.js only once
        if (!$parallax_loaded) {
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js" defer></script>';
            $parallax_loaded = true;
        }
        ?>
        
        <script>
        (function() {
            'use strict';
            
            const initParallax_<?php echo esc_js($widget_id); ?> = function() {
                const scene = document.getElementById('<?php echo esc_js($scene_id); ?>');
                const debug = <?php echo $debug ? 'true' : 'false'; ?>;
                
                if (debug) console.log('Parallax Controller: Initializing for scene:', '<?php echo esc_js($scene_id); ?>');
                
                if (!scene) {
                    if (debug) console.warn('Parallax Controller: Scene element not found:', '<?php echo esc_js($scene_id); ?>');
                    return;
                }
                
                // Check if Parallax library is loaded
                if (typeof Parallax === 'undefined') {
                    if (debug) console.error('Parallax Controller: Parallax.js library not loaded');
                    return;
                }
                
                // Setup layers
                const layers = <?php echo wp_json_encode($layers_data); ?>;
                let layersFound = 0;
                
                layers.forEach(function(layer) {
                    const element = document.getElementById(layer.id);
                    if (element) {
                        element.setAttribute('data-depth', layer.depth.toString());
                        element.classList.add('parallax-layer-<?php echo esc_js($widget_id); ?>');
                        layersFound++;
                        if (debug) console.log('Parallax Controller: Layer configured:', layer.id, 'depth:', layer.depth);
                    } else {
                        if (debug) console.warn('Parallax Controller: Layer element not found:', layer.id);
                    }
                });
                
                if (layersFound === 0) {
                    if (debug) console.warn('Parallax Controller: No layer elements found');
                    return;
                }
                
                // Initialize Parallax
                try {
                    const config = <?php echo wp_json_encode($config); ?>;
                    const parallaxInstance = new Parallax(scene, config);
                    
                    if (debug) {
                        console.log('Parallax Controller: Initialized successfully');
                        console.log('Config:', config);
                        console.log('Layers found:', layersFound);
                    }
                    
                    // Store instance for potential cleanup
                    scene.parallaxInstance = parallaxInstance;
                    
                } catch (error) {
                    if (debug) console.error('Parallax Controller: Initialization failed:', error);
                }
            };
            
            // Initialize when DOM is ready and Parallax is loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    // Small delay to ensure all elements are rendered
                    setTimeout(initParallax_<?php echo esc_js($widget_id); ?>, 100);
                });
            } else {
                setTimeout(initParallax_<?php echo esc_js($widget_id); ?>, 100);
            }
            
        })();
        </script>
        <?php
    }
    
    protected function content_template() {
        ?>
        <# 
        if (!settings.scene_id) { 
        #>
            <div class="elementor-alert elementor-alert-warning">
                <?php echo __('Please enter a Scene Container ID in the widget settings.', 'your-plugin'); ?>
            </div>
        <# 
        } else if (!settings.layers || settings.layers.length === 0) { 
        #>
            <div class="elementor-alert elementor-alert-info">
                <?php echo __('Add some parallax layers to see the effect.', 'your-plugin'); ?>
            </div>
        <# 
        } else { 
        #>
            <div class="parallax-controller-widget" data-scene-id="{{ settings.scene_id }}">
                <div class="elementor-alert elementor-alert-success">
                    <?php echo __('Parallax Controller configured for scene:', 'your-plugin'); ?> <strong>{{ settings.scene_id }}</strong>
                    <br><?php echo __('Layers:', 'your-plugin'); ?> {{ settings.layers.length }}
                </div>
            </div>
        <# } #>
        <?php
    }
}
