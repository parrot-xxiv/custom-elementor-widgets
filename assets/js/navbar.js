

(function ($) {
    'use strict';
    $(document).ready(function () {
        function navbarWidget($scope, $) {
            const $navbar = $scope.find('.navbar');
            const $toggle = $navbar.find('.navbar-toggle');
            const $menu = $navbar.find('.navbar-menu');
            const $menuLinks = $menu.find('a');
            const positionType = $navbar.attr('class').match(/navbar-(\S+)/)[1];
            let isMenuOpen = false;

            // Mobile toggle
            $toggle.on('click', function () {
                isMenuOpen = !$menu.hasClass('active');
                $menu.toggleClass('active');
                $(this).toggleClass('active');

                const spans = $(this).find('span');
                if ($(this).hasClass('active')) {
                    gsap.to(spans.eq(0), { rotation: 45, y: 7, duration: 0.3 });
                    gsap.to(spans.eq(1), { opacity: 0, duration: 0.3 });
                    gsap.to(spans.eq(2), { rotation: -45, y: -7, duration: 0.3 });
                } else {
                    gsap.to(spans, { rotation: 0, y: 0, opacity: 1, duration: 0.3 });
                }
            });

            // Close menu on link click
            $menuLinks.on('click', function () {
                $menu.removeClass('active');
                $toggle.removeClass('active');
                isMenuOpen = false;
                const spans = $toggle.find('span');
                gsap.to(spans, { rotation: 0, y: 0, opacity: 1, duration: 0.3 });
            });

            // Apply position-specific behavior
            applyNavbarBehavior(positionType, $navbar, () => isMenuOpen);
        }

        function applyNavbarBehavior(type, $navbar, isMenuOpenFn) {
            switch (type) {
                case 'navbar-1':
                    setupFixedNavbar($navbar, isMenuOpenFn);
                    break;
                default:
                    // no-op
                    break;
            }
        }

        function setupFixedNavbar($navbar, isMenuOpenFn) {
            if (typeof ScrollTrigger === 'undefined') return;

            const showAnim = gsap.from($navbar, {
                yPercent: -100,
                paused: true,
                duration: 0.2
            }).progress(1);

            ScrollTrigger.create({
                start: "top top",
                end: "max",
                onUpdate: (self) => {
                    const isMobile = window.innerWidth <= 768;
                    const isMenuOpen = isMenuOpenFn();
                    
                    if (isMobile && isMenuOpen) return; // disable when fullscreen menu is active

                    self.direction === -1 ? showAnim.play() : showAnim.reverse();
                }
            });
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/navbar.default', navbarWidget);
    });
})(jQuery);


