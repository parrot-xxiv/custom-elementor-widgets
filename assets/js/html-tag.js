(function ($) {
  'use strict';

  $(document).ready(function () {
    function htmlTagWidgetAnimation($scope, $) {
      const $elements = $scope.find('.html-tag-element');

      $elements.each(function () {
        const el = this;
        const animation = el.dataset.animation;

        if (!animation || animation === 'none') return;

        const triggerType = el.dataset.trigger || 'viewport';
        const duration = parseFloat(el.dataset.duration || 2000) / 1000;
        const delay = parseFloat(el.dataset.delay || 0) / 1000;
        const splitType = el.dataset.split || 'chars';
        const stagger = parseFloat(el.dataset.stagger || 50) / 1000;
        const speed = parseFloat(el.dataset.speed || 100);
        const blinkSpeed = parseFloat(el.dataset.blink || 500);

        function animate() {
          if (animation === 'staggered_blur_fade') {
            const split = new SplitText(el, { type: splitType });

            gsap.from(split[splitType], {
              opacity: 0,
              y: 20,
              filter: 'blur(5px)',
              stagger,
              duration,
              delay,
              ease: 'power3.out',
              scrollTrigger: triggerType === 'viewport' ? {
                trigger: el,
                start: 'top 80%',
                toggleActions: 'play none none none',
              } : undefined
            });
          }

          else if (animation === 'typewriter') {
            const chars = el.textContent.split('');
            el.innerHTML = `<span class="typewriter-text"></span><span class="typewriter-cursor">|</span>`;
            const textSpan = el.querySelector('.typewriter-text');
            const cursor = el.querySelector('.typewriter-cursor');

            let i = 0;

            function type() {
              if (i < chars.length) {
                textSpan.textContent += chars[i++];
                setTimeout(type, speed);
              }
            }

            function startTypewriter() {
              type();
              setInterval(() => {
                cursor.style.visibility = cursor.style.visibility === 'hidden' ? 'visible' : 'hidden';
              }, blinkSpeed);
            }

            if (triggerType === 'page_load') {
              startTypewriter();
            } else if (triggerType === 'viewport') {
              ScrollTrigger.create({
                trigger: el,
                start: 'top 80%',
                once: true,
                onEnter: startTypewriter
              });
            }
          }
        }

        animate(); // always call animate. Internal logic handles page_load/viewport
      });
    }

    // Make sure plugins are registered
    if (typeof gsap !== 'undefined' && gsap.registerPlugin) {
      gsap.registerPlugin(ScrollTrigger, SplitText);
    }

    elementorFrontend.hooks.addAction(
      'frontend/element_ready/html_tag_widget.default',
      htmlTagWidgetAnimation
    );
  });
})(jQuery);







