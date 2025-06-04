(function($) {
    const WidgetImageTransitionLoop = function($scope, $) {
        const $swiperEl = $scope.find('.image-transition-swiper');

        if (!$swiperEl.length) return;

        $swiperEl.each(function() {
            const $this = $(this);
            const autoplayDelay = parseInt($this.data('autoplay_delay')) || 3000;
            const transitionSpeed = parseInt($this.data('transition_speed')) || 600;
            const effect = $this.data('effect') || 'slide';

            new Swiper(this, {
                loop: true,
                effect: effect,
                speed: transitionSpeed,
                autoplay: {
                    delay: autoplayDelay,
                    disableOnInteraction: false,
                },
                fadeEffect: {
                    crossFade: true,
                },
                cubeEffect: {
                    shadow: true,
                    slideShadows: true,
                    shadowOffset: 20,
                    shadowScale: 0.94,
                },
                coverflowEffect: {
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: true,
                },
                flipEffect: {
                    slideShadows: true,
                    limitRotation: true,
                },
            });
        });
    };

    // Hook into Elementor's frontend
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/image_transition_loop.default', WidgetImageTransitionLoop);
    });
})(jQuery);



