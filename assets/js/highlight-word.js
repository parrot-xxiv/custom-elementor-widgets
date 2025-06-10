(function ($) {
    'use strict';

    $(document).ready(function () {

        function highlightWordWidget($scope, $) {
            // Select all instances of the highlight word widget
            const highlightWidgets = document.querySelectorAll('.highlight-word-widget');

            // If no widgets are found on the page, do nothing.
            if (!highlightWidgets.length) {
                return;
            }

            /**
             * Injects the necessary CSS styles into the document's head.
             * This makes the script self-contained and avoids the need for a separate CSS file.
             * It runs only once, even if there are multiple widgets on the page.
             */
            const injectStyles = () => {
                // Check if styles have already been injected to prevent duplication
                if (document.getElementById('cew-highlight-styles')) {
                    return;
                }

                const style = document.createElement('style');
                style.id = 'cew-highlight-styles';
                style.innerHTML = `
      .highlight-heading {
        /* Ensure the heading doesn't have its own positioning that could conflict */
        position: relative;
        z-index: 1;
      }
      .highlight-heading .cew-highlight-word-wrapper {
        position: relative;
        display: inline-block;
        white-space: nowrap; /* Prevents the word and its SVG from wrapping */
      }
      .cew-highlight-word-wrapper .cew-highlight-svg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: visible;
        z-index: -1; /* Place SVG behind the text */
        pointer-events: none; /* Make SVG non-interactive */
      }
      .cew-highlight-word-wrapper .cew-highlight-svg path {
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
      }
      /* Fallback for browsers that don't support inline SVG styles well */
      .cew-highlight-word-wrapper {
          -webkit-transform: translateZ(0);
          transform: translateZ(0);
      }
    `;
                document.head.appendChild(style);
            };

            // Inject the base styles needed for the animations
            injectStyles();


            // Process each widget instance individually
            highlightWidgets.forEach((widget, index) => {
                const heading = widget.querySelector('.highlight-heading');

                if (!heading) {
                    return;
                }

                // --- 1. Read Data Attributes from the Widget ---
                const highlightWord = widget.dataset.highlightWord?.toLowerCase() || '';
                const highlightColor = widget.dataset.highlightColor || '#ffeb3b';
                const strokeAngle = parseFloat(widget.dataset.strokeAngle) || -5;
                const brushStyle = widget.dataset.brushStyle || 'underline';

                // --- 2. Split Text and Find the Target Word ---
                const split = new SplitText(heading, { type: 'words' });
                let targetWordEl = null;

                split.words.forEach(wordEl => {
                    if (targetWordEl) return; // already found
                    const cleanWord = wordEl.textContent.trim().replace(/[.,!?;:]/g, '');
                    if (cleanWord.toLowerCase() === highlightWord) {
                        targetWordEl = wordEl;
                    }
                });

                if (!targetWordEl) {
                    split.revert();
                    console.warn(`Highlight Word Widget: The word "${highlightWord}" was not found in the heading.`);
                    return;
                }

                // --- 3. Prepare the Word for Animation ---
                const wrapper = document.createElement('span');
                wrapper.classList.add('cew-highlight-word-wrapper');
                targetWordEl.parentNode.insertBefore(wrapper, targetWordEl);
                wrapper.appendChild(targetWordEl);


                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.classList.add('cew-highlight-svg');
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

                // For the textured brush, we need to add a filter definition
                if (brushStyle === 'highlight') {
                    const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
                    const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
                    const filterId = `brush-texture-${index}`; // Unique ID for each widget instance
                    filter.setAttribute('id', filterId);
                    filter.setAttribute('x', '-20%');
                    filter.setAttribute('y', '-20%');
                    filter.setAttribute('width', '140%');
                    filter.setAttribute('height', '140%');

                    // Add turbulence for the brush texture
                    const feTurbulence = document.createElementNS('http://www.w3.org/2000/svg', 'feTurbulence');
                    feTurbulence.setAttribute('type', 'fractalNoise');
                    feTurbulence.setAttribute('baseFrequency', '0.9 0.9'); // Controls the roughness
                    feTurbulence.setAttribute('numOctaves', '2');
                    feTurbulence.setAttribute('result', 'turbulence');

                    // Use a displacement map to apply the texture to the stroke
                    const feDisplacementMap = document.createElementNS('http://www.w3.org/2000/svg', 'feDisplacementMap');
                    feDisplacementMap.setAttribute('in', 'SourceGraphic');
                    feDisplacementMap.setAttribute('in2', 'turbulence');
                    feDisplacementMap.setAttribute('scale', '5'); // Controls the intensity of the effect
                    feDisplacementMap.setAttribute('xChannelSelector', 'R');
                    feDisplacementMap.setAttribute('yChannelSelector', 'G');

                    filter.appendChild(feTurbulence);
                    filter.appendChild(feDisplacementMap);
                    defs.appendChild(filter);
                    svg.appendChild(defs);

                    // Apply the filter to the path
                    path.setAttribute('filter', `url(#${filterId})`);
                }

                svg.appendChild(path);
                wrapper.appendChild(svg);


                // Use a timeout to ensure the DOM has updated and dimensions are available
                setTimeout(() => {
                    const w = wrapper.offsetWidth;
                    const h = wrapper.offsetHeight;
                    let pathData = '';
                    let strokeWidth = Math.max(2, h / 8);
                    let animationEase = 'power3.inOut';

                    // --- 4. Define SVG Paths and Animation Properties for Brush Styles ---
                    switch (brushStyle) {
                        case 'circle':
                            // An open, hand-drawn circle path for soft, rounded ends.
                            strokeWidth = Math.max(2, h / 15);
                            pathData = `M${w * 0.5}, ${h * 0.95} C ${-w * 0.1},${h * 1.2} ${-w * 0.1},${-h * 0.2} ${w * 0.5},${h * 0.1} C ${w * 1.1},${-h * 0.2} ${w * 1.2},${h * 1.2} ${w * 0.55},${h * 0.9}`;
                            // This ease overshoots the end value, creating the "extend the ends" effect.
                            animationEase = 'back.out(1.2)';
                            break;

                        case 'highlight':
                            // A thick, wavy, organic path that mimics a real brush stroke.
                            strokeWidth = h * 0.7; // Thick like a highlighter
                            pathData = `M${w * 0.1},${h * 0.6} Q${w * 0.15},${h * 0.4} ${w * 0.5},${h * 0.6} T${w * 0.9},${h * 0.55}`;
                            path.style.strokeOpacity = '0.75';
                            break;

                        case 'strikethrough':
                            strokeWidth = Math.max(2, h / 10);
                            pathData = `M0,${h * 0.5} L${w},${h * 0.5}`;
                            break;

                        case 'underline':
                        default:
                            strokeWidth = Math.max(2, h / 10);
                            pathData = `M0,${h * 0.9} L${w},${h * 0.9}`;
                            break;
                    }

                    // --- 5. Apply Path and Styles ---
                    path.setAttribute('d', pathData);
                    path.setAttribute('stroke', highlightColor);
                    path.setAttribute('stroke-width', strokeWidth);

                    const pathLength = path.getTotalLength();
                    path.style.strokeDasharray = pathLength;
                    path.style.strokeDashoffset = pathLength;

                    gsap.set(svg, {
                        rotate: strokeAngle,
                        xPercent: -50,
                        yPercent: -50,
                        left: '50%',
                        top: '50%'
                    });

                    // --- 6. Create GSAP Scroll-Triggered Animation ---
                    const tl = gsap.timeline({
                        scrollTrigger: {
                            trigger: widget,
                            start: 'top 85%',
                            end: 'bottom top',
                            toggleActions: 'play none none none',
                        }
                    });

                    tl.to(path, {
                        strokeDashoffset: 0,
                        duration: 1.5,
                        ease: animationEase
                    });

                }, 100);
            });
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/highlight_word.default', highlightWordWidget);
    });
})(jQuery);
