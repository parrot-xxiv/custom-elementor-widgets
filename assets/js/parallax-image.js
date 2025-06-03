/**
 * Parallax Image Widget JavaScript
 * Handles GSAP-based parallax scrolling effect for Elementor widget
 */

(function($) {
    'use strict';

    class ParallaxImageWidget {
        constructor() {
            this.widgets = [];
            this.init();
        }

        init() {
            // Initialize when DOM is ready
            $(document).ready(() => {
                this.initializeParallaxWidgets();
            });

            // Reinitialize on Elementor frontend events
            $(window).on('elementor/frontend/init', () => {
                this.initializeParallaxWidgets();
            });
        }

        initializeParallaxWidgets() {
            $('.parallax-banner').each((index, element) => {
                const $element = $(element);
                const widgetId = $element.data('widget-id');
                
                // Skip if already initialized
                if (this.widgets.find(w => w.id === widgetId)) {
                    return;
                }

                this.createParallaxInstance($element, widgetId);
            });
        }

        createParallaxInstance($element, widgetId) {
            const $parallaxImage = $element.find('.parallax-image');
            
            // Get settings from data attributes
            const speedDesktop = parseFloat($element.data('speed-desktop')) || 0.5;
            const speedTablet = parseFloat($element.data('speed-tablet')) || speedDesktop;
            const speedMobile = parseFloat($element.data('speed-mobile')) || speedTablet;
            const disableMobile = $element.data('disable-mobile') === 'true';

            // Check if GSAP and ScrollTrigger are available
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                console.warn('GSAP or ScrollTrigger not loaded for parallax widget');
                return;
            }

            // Register ScrollTrigger plugin
            gsap.registerPlugin(ScrollTrigger);

            // Get current speed based on viewport
            const getCurrentSpeed = () => {
                const width = window.innerWidth;
                
                if (width <= 767 && disableMobile) {
                    return 0; // Disable parallax on mobile
                } else if (width <= 767) {
                    return speedMobile;
                } else if (width <= 1024) {
                    return speedTablet;
                } else {
                    return speedDesktop;
                }
            };

            // Create parallax animation
            const createAnimation = () => {
                const speed = getCurrentSpeed();
                
                if (speed === 0) {
                    // Reset transform if parallax is disabled
                    gsap.set($parallaxImage[0], { yPercent: 0 });
                    return null;
                }

                return gsap.fromTo($parallaxImage[0], 
                    {
                        yPercent: -50 * speed
                    },
                    {
                        yPercent: 50 * speed,
                        ease: "none",
                        scrollTrigger: {
                            trigger: $element[0],
                            start: "top bottom",
                            end: "bottom top",
                            scrub: true,
                            invalidateOnRefresh: true
                        }
                    }
                );
            };

            // Initial animation
            let animation = createAnimation();

            // Store widget instance
            const widgetInstance = {
                id: widgetId,
                element: $element[0],
                animation: animation,
                refresh: () => {
                    // Kill existing animation
                    if (animation) {
                        animation.kill();
                    }
                    // Create new animation with current settings
                    animation = createAnimation();
                    widgetInstance.animation = animation;
                }
            };

            this.widgets.push(widgetInstance);

            // Refresh on resize with debouncing
            let resizeTimeout;
            $(window).on('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    widgetInstance.refresh();
                }, 250);
            });
        }

        // Public method to refresh all instances
        refreshAll() {
            this.widgets.forEach(widget => {
                if (widget.refresh) {
                    widget.refresh();
                }
            });
        }

        // Public method to destroy all instances
        destroyAll() {
            this.widgets.forEach(widget => {
                if (widget.animation) {
                    widget.animation.kill();
                }
            });
            this.widgets = [];
        }
    }

    // Initialize the parallax system
    const parallaxSystem = new ParallaxImageWidget();

    // Expose to global scope for external access
    window.ParallaxImageWidget = parallaxSystem;

    // Handle Elementor editor mode
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/parallax_widget.default', function($scope) {
            // Reinitialize for this specific widget in editor
            setTimeout(() => {
                parallaxSystem.initializeParallaxWidgets();
            }, 100);
        });
    }

})(jQuery);