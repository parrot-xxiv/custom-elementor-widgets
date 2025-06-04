(function () {
    'use strict';

    // Scoped to avoid conflicts with other widgets
    // Using a simpler unique ID method for the namespace, compatible with your original.
    const WIDGET_NAMESPACE = 'htmlTagWidget_<?php echo esc_js($this->get_id()); ?>';
    // Using widget ID makes it unique per widget instance if this script is output per widget.
    // If script is global, Date.now + random is fine too.

    document.addEventListener('DOMContentLoaded', function () {
        // Consider using Elementor's frontend hooks for better integration if issues arise in editor
        // Example: elementorFrontend.hooks.addAction('frontend/element_ready/' + WIDGET_NAMESPACE + '.default', callback);

        const animatedElements = document.querySelectorAll('.html-tag-element.has-entrance-animation');

        if (typeof IntersectionObserver === 'undefined') {
            // Fallback for browsers that don't support IntersectionObserver
            animatedElements.forEach(element => {
                const animation = element.dataset.animation;
                const delay = parseInt(element.dataset.delay) || 0;
                setTimeout(() => {
                    if (animation === 'staggered_blur_fade') {
                        window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'](element);
                    } else if (animation === 'typewriter') {
                        window[WIDGET_NAMESPACE + '_initTypewriter'](element);
                    }
                }, delay);
            });
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const animation = element.dataset.animation;
                    const delay = parseInt(element.dataset.delay) || 0;

                    setTimeout(() => {
                        if (animation === 'staggered_blur_fade') {
                            if (typeof window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'] === 'function') {
                                window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'](element);
                            }
                        } else if (animation === 'typewriter') {
                            if (typeof window[WIDGET_NAMESPACE + '_initTypewriter'] === 'function') {
                                window[WIDGET_NAMESPACE + '_initTypewriter'](element);
                            }
                        }
                    }, delay);

                    observer.unobserve(element); // Animate only once
                }
            });
        }, {
            threshold: 0.1
        }); // Trigger when 10% of the element is visible

        animatedElements.forEach(el => observer.observe(el));
    });

    // Staggered Blur & Fade-In Letters Animation (Word Wrap Fixed)
    window[WIDGET_NAMESPACE + '_initStaggeredBlurFade'] = function (element) {
        const originalText = (element.textContent || element.innerText || '').trim();
        if (!originalText) {
            element.classList.add('initialized'); // Make visible even if empty
            return;
        }

        const staggerDelay = parseInt(element.dataset.staggerDelay) || 50;

        element.innerHTML = ''; // Clear current content
        element.classList.add('initialized'); // Make parent visible (opacity: 1)

        // Split by words and preserve spaces. (\s+) captures one or more whitespace characters.
        const parts = originalText.split(/(\s+)/);
        let currentDelay = 0;

        parts.forEach(part => {
            if (part.match(/^\s+$/)) { // It's one or more space characters
                element.appendChild(document.createTextNode(part)); // Append spaces as text node
            } else if (part.length > 0) { // It's a word
                const wordSpan = document.createElement('span');
                wordSpan.className = 'html-tag-word';
                for (let i = 0; i < part.length; i++) {
                    const char = part[i];
                    const letterSpan = document.createElement('span');
                    letterSpan.className = 'html-tag-letter';
                    letterSpan.textContent = char;
                    letterSpan.style.transitionDelay = `${currentDelay}ms`;
                    wordSpan.appendChild(letterSpan);
                    currentDelay += staggerDelay;
                }
                element.appendChild(wordSpan);
            }
        });

        // Trigger the animation by adding 'animate' class after a short delay
        // This ensures styles are applied and then transitions start based on individual delays
        setTimeout(() => {
            const letters = element.querySelectorAll('.html-tag-letter');
            letters.forEach(letter => {
                letter.classList.add('animate');
            });
        }, 50); // Small delay for rendering
    };

    // Typewriter Effect Animation (Fixed)
    window[WIDGET_NAMESPACE + '_initTypewriter'] = function (element) {
        // IMPORTANT: Get the text BEFORE modifying innerHTML
        const originalFullText = (element.textContent || element.innerText || '').trim();

        const typingSpeed = parseInt(element.dataset.typingSpeed) || 100;
        const cursorBlinkSpeed = parseInt(element.dataset.cursorBlink) || 500;

        // Clear the element and set up the structure for the typewriter
        // Do this after getting originalFullText
        element.innerHTML = '<span class="html-tag-typewriter-text"></span><span class="html-tag-cursor" style="animation-duration: ' + cursorBlinkSpeed + 'ms;"></span>';
        element.classList.add('initialized'); // Make it visible

        if (!originalFullText) { // If there's no text, nothing to type.
            // Cursor will still be visible if not hidden. Could hide cursor here too.
            const cursorElement = element.querySelector('.html-tag-cursor');
            if (cursorElement) cursorElement.style.display = 'none';
            return;
        }

        const textElement = element.querySelector('.html-tag-typewriter-text');
        // const cursorElement = element.querySelector('.html-tag-cursor'); // For potential hiding later

        if (!textElement) {
            console.error('Typewriter text span not found within:', element);
            return;
        }

        let currentIndex = 0;
        let displayedText = '';

        function typeNextCharacter() {
            if (currentIndex < originalFullText.length) {
                const char = originalFullText[currentIndex];
                // For HTML, literal spaces can collapse. Using &nbsp; ensures they are preserved as typed.
                if (char === ' ') {
                    displayedText += '&nbsp;';
                } else {
                    displayedText += char;
                }
                textElement.innerHTML = displayedText; // Update the displayed text
                console.log(textElement.innerHTML)
                currentIndex++;
                setTimeout(typeNextCharacter, typingSpeed);
            } else {
                // Typing finished
                // Optional: hide cursor after typing if desired
                // if (cursorElement) {
                //     cursorElement.style.display = 'none'; 
                // }
            }
        }

        typeNextCharacter(); // Start the typing animation
    };

    // You can keep your extensible animation system placeholder
    window[WIDGET_NAMESPACE + '_initCustomAnimation'] = function (element, animationType) {
        // Future animations can be added here
    };
})();