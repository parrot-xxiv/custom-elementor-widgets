(function ($) {
    'use strict';
    $(window).on('elementor/frontend/init', function () {
        function crawlerWidget($scope) {
            const $wrapper = $scope.find('.crawler-wrapper');
            $wrapper.each(function () {
                const $this = $(this);
                const $track = $this.find('.crawler-track');
                
                const wrapperWidth = $this.outerWidth();
                const trackWidth = $track.outerWidth();
                
                // Calculate how many copies we need for seamless loop
                const copies = Math.ceil((wrapperWidth * 2) / trackWidth) + 1;
                
                // Create multiple copies for seamless scrolling
                for (let i = 0; i < copies; i++) {
                    const $clone = $track.clone();
                    $track.after($clone);
                }
                
                const duration = parseInt($this.data('duration'), 10) || 5000;
                const pauseOnHover = $this.data('pause') === true || $this.data('pause') === 'true';
                
                // Animate all tracks together
                const $allTracks = $this.find('.crawler-track');
                const tl = gsap.timeline({ repeat: -1 });
                tl.set($allTracks, { x: 0 })
                  .to($allTracks, {
                      x: -trackWidth,
                      duration: duration / 1000,
                      ease: "none"
                  });
                
                if (pauseOnHover) {
                    $this.on('mouseenter', () => tl.pause());
                    $this.on('mouseleave', () => tl.play());
                }
            });
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/crawler_text.default', crawlerWidget);
    });
})(jQuery);


