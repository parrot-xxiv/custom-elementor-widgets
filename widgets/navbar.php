<?php

defined('ABSPATH') || exit;

class Navbar extends \Elementor\Widget_Base {
    public function get_categories() {
        return ['basic'];
    }
    
    public function get_icon() {
        return 'eicon-nav-menu';
    }
    
    public function get_name() {
        return 'navbar';
    }
    
    public function get_script_depends() {
        return ['cew-gsap','cew-navbar' ];
    }
    
    public function get_title() {
        return 'Navbar';
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo_type',
            [
                'label' => 'Logo Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => 'Brand Text',
                    'image' => 'Logo Image',
                ],
            ]
        );

        $this->add_control(
            'brand_text',
            [
                'label' => 'Brand Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Brand',
                'condition' => ['logo_type' => 'text'],
            ]
        );

        $this->add_control(
            'logo_image',
            [
                'label' => 'Logo Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => ['logo_type' => 'image'],
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => 'Menu Items',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'menu_text',
                        'label' => 'Menu Text',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Menu Item',
                    ],
                    [
                        'name' => 'menu_href',
                        'label' => 'Link',
                        'type' => \Elementor\Controls_Manager::URL,
                        'default' => ['url' => '#'],
                    ],
                ],
                'default' => [
                    ['menu_text' => 'Home', 'menu_href' => ['url' => '#home']],
                    ['menu_text' => 'About', 'menu_href' => ['url' => '#about']],
                    ['menu_text' => 'Services', 'menu_href' => ['url' => '#services']],
                    ['menu_text' => 'Contact', 'menu_href' => ['url' => '#contact']],
                ],
                'title_field' => '{{{ menu_text }}}',
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => 'Layout',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'position_type',
            [
                'label' => 'Position Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => $this->get_navbar_options(),
            ]
        );

        $this->end_controls_section();

        // Style controls...
        $this->add_style_controls();
    }

    protected function get_navbar_options() {
        return [
            'default' => 'Default',
            'navbar-1' => 'Fixed Header (Auto Hide)',
        ];
    }

    protected function add_style_controls() {
        // Background
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Background',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'navbar_background',
                'selector' => '{{WRAPPER}} .navbar',
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'brand_typography_section',
            [
                'label' => 'Brand Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'brand_typography',
                'selector' => '{{WRAPPER}} .navbar-brand',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'menu_typography_section',
            [
                'label' => 'Menu Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .navbar-menu a',
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label' => 'Menu Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .navbar-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_active_color',
            [
                'label' => 'Active Menu Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .navbar-menu a.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function content_template() {
        ?>
        <#
        var positionType = settings.position_type;
        var logoType = settings.logo_type;
        var brandText = settings.brand_text;
        var logoImage = settings.logo_image;
        var menuItems = settings.menu_items;
        #>
        <nav class="navbar navbar-{{{ positionType }}}">
            <div class="navbar-container">
                <div class="navbar-brand">
                    <# if (logoType === 'text') { #>
                        {{{ brandText }}}
                    <# } else if (logoImage.url) { #>
                        <img src="{{{ logoImage.url }}}" alt="Logo">
                    <# } #>
                </div>
                <div class="navbar-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul class="navbar-menu">
                    <# _.each(menuItems, function(item, index) { #>
                        <li><a href="{{{ item.menu_href.url }}}">{{{ item.menu_text }}}</a></li>
                    <# }); #>
                </ul>
            </div>
        </nav>
        <?php
    }

    protected function render() {
    $settings = $this->get_settings_for_display();
    $position_type = $settings['position_type'];
    $logo_type = $settings['logo_type'];
    $brand_text = $settings['brand_text'];
    $logo_image = $settings['logo_image'];
    $menu_items = $settings['menu_items'];
    ?>
    <nav class="navbar navbar-<?php echo esc_attr($position_type); ?>">
        <div class="navbar-container">
            <div class="navbar-brand">
                <?php if ($logo_type === 'text'): ?>
                    <?php echo esc_html($brand_text); ?>
                <?php elseif ($logo_type === 'image' && !empty($logo_image['url'])): ?>
                    <img src="<?php echo esc_url($logo_image['url']); ?>" alt="Logo">
                <?php endif; ?>
            </div>
            <div class="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul class="navbar-menu">
                <?php foreach ($menu_items as $item): ?>
                    <li><a href="<?php echo esc_url($item['menu_href']['url']); ?>"><?php echo esc_html($item['menu_text']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <style>
    .navbar {
        width: 100%;
        z-index: 1000;
        transition: all 0.3s ease;
        background: #000;
        color: #fff;
    }

    .navbar a {
        color: #fff;
    }

    .navbar-default {
        position: relative;
    }

    .navbar-navbar-1 {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
    }

    .navbar-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        height: 80px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .navbar-brand {
        font-weight: bold;
    }

    .navbar-brand img {
        height: 40px;
    }

    .navbar-menu {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 30px;
    }

    .navbar-menu a {
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        color: #fff;
    }

    .navbar-menu a.active {
        font-weight: bold;
    }

    .navbar-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        gap: 4px;
    }

    .navbar-toggle span {
        width: 25px;
        height: 3px;
        background: #fff;
        transition: 0.3s;
    }

    @media (max-width: 768px) {
        .navbar-menu {
            position: fixed;
            inset: 0;
            height: 100vh; 
            background: #000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 40px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 999;
        }

        .navbar-menu.active {
            transform: translateX(0);
        }

        .navbar-menu li {
            font-size: 1.5rem;
        }

        .navbar-toggle {
            display: flex;
            z-index: 1001;
        }
    }
    </style>

    <?php
}

}