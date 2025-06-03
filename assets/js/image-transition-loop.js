(function($) {
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/image_transition_loop.default', function($scope) {
            // Find the wrapper with the widget ID
            var $wrapper = $scope.find('.image-transition-loop-wrapper');
            var widgetId = $wrapper.data('widget-id');
            
            if (!widgetId) {
                console.error('No widget ID found for image transition loop');
                return;
            }
            
            // Find the swiper element
            var $swiperElement = $scope.find('#swiper-' + widgetId);
            
            if (!$swiperElement.length) {
                console.error('Swiper element not found for widget ID:', widgetId);
                return;
            }
            
            // Get settings from the swiper element's data attributes
            var autoplayDelay = parseInt($swiperElement.data('autoplay_delay')) || 3000;
            var transitionSpeed = parseInt($swiperElement.data('transition_speed')) || 600;
            var effect = $swiperElement.data('effect') || 'slide';
            
            // Initialize the Swiper instance for this specific widget
            var swiper = new Swiper('#swiper-' + widgetId, {
                loop: true,
                autoplay: {
                    delay: autoplayDelay,
                },
                speed: transitionSpeed,
                effect: effect,
                // Add additional effect-specific options if needed
                fadeEffect: {
                    crossFade: true
                },
                cubeEffect: {
                    shadow: true,
                    slideShadows: true,
                    shadowOffset: 20,
                    shadowScale: 0.94
                },
                coverflowEffect: {
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: true
                },
                flipEffect: {
                    slideShadows: false
                }
            });
            
            // Optional: Add click event listener to slides
            $scope.find('.swiper-slide').on('click', function() {
                console.log('Slide clicked in widget ' + widgetId);
            });
            
            // Optional: Store swiper instance for later access
            $wrapper.data('swiper-instance', swiper);
        });
    });
})(jQuery);

