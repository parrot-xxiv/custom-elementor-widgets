(function ($) {
    'use strict';

    $(document).ready(function () {

        function highlightWordWidget($scope, $) {
            const $widget = $scope.find('.highlight-word-widget');
            const $heading = $widget.find('.highlight-heading');

            if (!$widget.length || !$heading.length) return;

            const highlightWord = $widget.data('highlight-word');
            const highlightColor = $widget.data('highlight-color');
            const strokeAngle = $widget.data('stroke-angle');
            const brushStyle = $widget.data('brush-style');

            if (!highlightWord) return;

            const headingText = $heading.text();
            const regex = new RegExp(`(${highlightWord})`, 'gi');
            const highlightedHTML = headingText.replace(regex, `<span class="highlight-target">$1</span>`);

            $heading.html(highlightedHTML);

            const $highlightTarget = $heading.find('.highlight-target');

            if ($highlightTarget.length) {
                applyHighlightStyle($highlightTarget, highlightColor, strokeAngle, brushStyle);

                // gsap.set($highlightTarget, { opacity: 0, scale: 0.8 });

                ScrollTrigger.create({
                    trigger: $widget[0],
                    start: 'top 80%',
                    once: true,
                    onEnter: () => {
                        $highlightTarget.addClass('animate-stroke');
                    }
                });
            }
        }

        function applyHighlightStyle($element, color, angle, style) {
            const baseStyles = {
                position: 'relative',
                display: 'inline-block',
                color: '#000'
            };

            $element.css(baseStyles);

            let pseudoStyles = '';

            switch (style) {
                case 'underline':
                    pseudoStyles = `
                        content: '';
                        position: absolute;
                        bottom: -2px;
                        left: 0;
                        width: 0%;
                        height: 3px;
                        background: ${color};
                        transform: rotate(${angle});
                        transform-origin: left center;
                        transition: width 0.8s ease-out;
                    `;
                    break;

                case 'highlight':
                    pseudoStyles = `
                        content: '';
                        position: absolute;
                        top: 45%;
                        left: -2px;
                        right: 100%;
                        bottom: 0;
                        background: ${color};
                        transform: rotate(${angle});
                        z-index: -1;
                        opacity: 0.7;
                        height: 50%;
                        transition: right 0.8s ease-out;
                        border-radius: 20px 4px 30px 5px;
                    `;
                    break;

                case 'circle':
                    pseudoStyles = `
                        content: '';
                        position: absolute;
                        top: -5px;
                        left: -5px;
                        right: -5px;
                        bottom: -5px;
                        border: 2px solid ${color};
                        border-radius: 50%;
                        transform: rotate(${angle});
                        stroke-dasharray: 1000;
                        stroke-dashoffset: 1000;
                        animation: drawCircle 0.8s ease-out forwards;
                    `;
                    break;

                case 'strikethrough':
                    pseudoStyles = `
                        content: '';
                        position: absolute;
                        top: 50%;
                        left: 0;
                        width: 0%;
                        height: 2px;
                        background: ${color};
                        transform: translateY(-50%) rotate(${angle});
                        transition: width 0.8s ease-out;
                    `;
                    break;
            }

            const styleId = 'highlight-word-styles';
            let $style = $(`#${styleId}`);

            if (!$style.length) {
                $style = $(`<style id="${styleId}"></style>`).appendTo('head');
            }

            const className = `highlight-${Date.now()}`;
            $element.addClass(className);

            const keyframes = `
                @keyframes drawCircle {
                    to {
                        stroke-dashoffset: 0;
                    }
                }
            `;

            $style.append(`
                .${className}::after { ${pseudoStyles} }
                ${keyframes}
                .${className}.animate-stroke::after {
                    ${style === 'underline' || style === 'strikethrough' ? 'width: 100% !important;' : ''}
                    ${style === 'highlight' ? 'right: -2px !important;' : ''}
                }
            `);
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/highlight_word.default', highlightWordWidget);
    });
})(jQuery);
