<?php

/**
 * Portfolio Projects Elementor Widget for Custom Elementor Widgets plugin.
 *
 * @package Custom_Elementor_Widgets
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Case_Study_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'case_study';
    }

    public function get_title()
    {
        return __('Case Study', 'custom-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_script_depends()
    {
        return ['portfolio-projects-script'];
    }

    protected function register_controls()
    {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label'   => __('Section Title', 'custom-elementor-widgets'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __('Featured Projects', 'custom-elementor-widgets'),
            ]
        );

        $this->add_control(
            'show_filters',
            [
                'label' => __('Show Filter Buttons', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'custom-elementor-widgets'),
                'label_off' => __('Hide', 'custom-elementor-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => __('1 Column', 'custom-elementor-widgets'),
                    '2' => __('2 Columns', 'custom-elementor-widgets'),
                    '3' => __('3 Columns', 'custom-elementor-widgets'),
                    '4' => __('4 Columns', 'custom-elementor-widgets'),
                ],
            ]
        );

        $this->end_controls_section();

        // Projects Section
        $this->start_controls_section(
            'projects_section',
            [
                'label' => __('Projects', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'project_title',
            [
                'label' => __('Project Title', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Project Title', 'custom-elementor-widgets'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'project_description',
            [
                'label' => __('Project Description', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Project description goes here.', 'custom-elementor-widgets'),
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'project_image',
            [
                'label' => __('Project Image', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'project_category',
            [
                'label' => __('Category', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'web',
                'options' => [
                    'web' => __('Web Development', 'custom-elementor-widgets'),
                    'mobile' => __('Mobile Apps', 'custom-elementor-widgets'),
                    'design' => __('UI/UX Design', 'custom-elementor-widgets'),
                ],
            ]
        );

        $repeater->add_control(
            'project_link',
            [
                'label' => __('Project Link', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'custom-elementor-widgets'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'projects_list',
            [
                'label' => __('Projects', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'project_title' => __('E-commerce Platform', 'custom-elementor-widgets'),
                        'project_description' => __('A fully responsive e-commerce website with product filtering, cart functionality, and secure checkout.', 'custom-elementor-widgets'),
                        'project_category' => 'web',
                    ],
                    [
                        'project_title' => __('Fitness Tracker', 'custom-elementor-widgets'),
                        'project_description' => __('A mobile application that helps users track workouts, set goals, and monitor their fitness progress.', 'custom-elementor-widgets'),
                        'project_category' => 'mobile',
                    ],
                    [
                        'project_title' => __('Analytics Dashboard', 'custom-elementor-widgets'),
                        'project_description' => __('A clean and intuitive dashboard design for visualizing complex data and analytics.', 'custom-elementor-widgets'),
                        'project_category' => 'design',
                    ],
                ],
                'title_field' => '{{{ project_title }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'custom-elementor-widgets'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3b82f6',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color', 'custom-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1e40af',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();

        // Get unique categories from projects
        $categories = [];
        foreach ($settings['projects_list'] as $project) {
            if (!in_array($project['project_category'], $categories)) {
                $categories[] = $project['project_category'];
            }
        }

        $category_labels = [
            'web' => __('Web Development', 'custom-elementor-widgets'),
            'mobile' => __('Mobile Apps', 'custom-elementor-widgets'),
            'design' => __('UI/UX Design', 'custom-elementor-widgets'),
        ];

        $columns_class = 'lg:grid-cols-' . $settings['columns'];
?>

        <style>
            .active {
                text-decoration: underline;
            }
        </style>
        <div class="portfolio-widget-<?php echo $widget_id; ?>">

            <?php if ($settings['show_filters'] === 'yes' && !empty($categories)) : ?>
                <!-- Filter Buttons -->
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap justify-center gap-3 mb-8" id="filter-buttons-<?php echo $widget_id; ?>">
                        <button
                            class="filter-btn active cursot-pointer"
                            data-filter="all">
                            <?php echo __('All Projects', 'custom-elementor-widgets'); ?>
                        </button>
                        <?php foreach ($categories as $category) : ?>
                            <button
                                class="filter-btn cursor-pointer"
                                data-filter="<?php echo esc_attr($category); ?>">
                                <?php echo esc_html($category_labels[$category]); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Projects Grid -->
            <section id="projects-<?php echo $widget_id; ?>" class="py-16">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl font-bold text-secondary dark:text-white text-center mb-16 relative">
                        <span class="relative z-10"><?php echo esc_html($settings['section_title']); ?></span>
                        <span class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-primary rounded-full"></span>
                    </h2>

                    <div class="projects-grid grid grid-cols-1 md:grid-cols-2 <?php echo esc_attr($columns_class); ?> gap-8">
                        <?php foreach ($settings['projects_list'] as $index => $project) : ?>
                            <div
                                data-category="<?php echo esc_attr($project['project_category']); ?>"
                                class="project-card relative h-72 rounded-lg overflow-hidden group shadow-lg bg-center bg-cover bg-no-repeat"
                                style="background-image: url('<?php echo esc_url($project['project_image']['url'] ? $project['project_image']['url'] : 'https://placehold.co/600x400'); ?>');">
                                <div
                                    class="absolute inset-0 transition-opacity duration-300 group-hover:bg-black/40"></div>

                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100 z-10">
                                    <h3
                                        class="text-2xl -translate-y-5 font-bold group-hover:translate-y-0 transition">
                                        <?php echo esc_attr($project['project_title']); ?>
                                    </h3>
                                    <p
                                        class="text-sm mt-2 px-4 text-center translate-y-5 group-hover:translate-y-0 transition">
                                        <?php echo esc_html($category_labels[$project['project_category']]); ?>
                                    </p>
                                    <?php if (!empty($project['project_link']['url'])) : ?>
                                        <a href="<?php echo esc_url($project['project_link']['url']); ?>"
                                            <?php echo $project['project_link']['is_external'] ? 'target="_blank"' : ''; ?>
                                            <?php echo $project['project_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                                            class="inline-block bg-secondary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors">
                                            <?php echo __('View Project', 'custom-elementor-widgets'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterButtons = document.querySelectorAll('#filter-buttons-<?php echo $widget_id; ?> .filter-btn');
                const projectCards = document.querySelectorAll('#projects-<?php echo $widget_id; ?> .project-card');

                filterButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const filter = this.getAttribute('data-filter');

                        // Update active button
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        // Filter projects
                        projectCards.forEach(card => {
                            if (filter === 'all' || card.getAttribute('data-category') === filter) {
                                card.classList.remove('hidden');
                            } else {
                                card.classList.add('hidden');
                            }
                        });
                    });
                });
            });
        </script>
<?php
    }
}
