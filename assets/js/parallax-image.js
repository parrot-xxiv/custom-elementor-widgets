(function($) {
    const ParallaxImageHandler = function($scope, $) {
        const $element = $scope.find('.parallax-banner');
        const $parallaxImage = $element.find('.parallax-image');

        if (!$element.length || !$parallaxImage.length) return;

        gsap.registerPlugin(ScrollTrigger);

        const disableMobile = $element.data('disable-mobile') === true || $element.data('disable-mobile') === 'true';
        const speedDesktop = parseFloat($element.data('speed-desktop')) || 0.5;
        const speedTablet = parseFloat($element.data('speed-tablet')) || speedDesktop;
        const speedMobile = parseFloat($element.data('speed-mobile')) || speedTablet;

        const getCurrentSpeed = () => {
            const width = window.innerWidth;

            if (width <= 767) {
                return disableMobile ? 0 : speedMobile;
            } else if (width <= 1024) {
                return speedTablet;
            } else {
                return speedDesktop;
            }
        };

        const createParallax = () => {
            const speed = getCurrentSpeed();

            ScrollTrigger.getById(`parallax-${$element.data('widget-id')}`)?.kill();

            if (speed === 0) {
                // Reset if disabled
                gsap.set($parallaxImage[0], { yPercent: 0 });
                return;
            }

            gsap.fromTo($parallaxImage[0],
                { yPercent: -50 * speed },
                {
                    yPercent: 50 * speed,
                    ease: "none",
                    scrollTrigger: {
                        id: `parallax-${$element.data('widget-id')}`,
                        trigger: $element[0],
                        start: "top bottom",
                        end: "bottom top",
                        scrub: true,
                        invalidateOnRefresh: true
                    }
                }
            );
        };

        createParallax();

        window.addEventListener('resize', () => {
            ScrollTrigger.refresh(true);
            createParallax();
        });
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/parallax_widget.default', ParallaxImageHandler);
    });
})(jQuery);
