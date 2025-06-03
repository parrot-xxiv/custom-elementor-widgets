/**
 * HTML Tag Widget Animations
 * Handles entrance animations for the Custom Elementor Widgets HTML Tag widget
 */

(function($) {
    'use strict';

    class HTMLTagAnimations {
        constructor() {
            this.elements = [];
            this.observers = [];
            this.init();
        }

        init() {
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.initializeAnimations());
            } else {
                this.initializeAnimations();
            }

            // Reinitialize for Elementor editor
            if (typeof elementorFrontend !== 'undefined') {
                elementorFrontend.hooks.addAction('frontend/element_ready/widget', () => {
                    setTimeout(() => this.initializeAnimations(), 100);
                });
            }
        }

        initializeAnimations() {
            const animatedElements = document.querySelectorAll('.has-entrance-animation');
            
            // Clear existing observers
            this.clearObservers();

            animatedElements.forEach(element => {
                const animation = element.dataset.animation;
                
                if (!animation || animation === 'none') return;

                // Prepare element for animation
                this.prepareElement(element, animation);

                // Setup intersection observer for viewport detection
                this.setupObserver(element);
            });
        }

        prepareElement(element, animation) {
            // Add initial styles to prevent flash
            element.style.opacity = '0';
            
            switch (animation) {
                case 'staggered_blur_fade':
                    this.prepareStaggeredBlurFade(element);
                    break;
                case 'typewriter':
                    this.prepareTypewriter(element);
                    break;
            }
        }

        prepareStaggeredBlurFade(element) {
            const text = element.textContent || element.innerText;
            const isLink = element.querySelector('a');
            const targetElement = isLink ? element.querySelector('a') : element;
            
            // Split text into individual letters
            const letters = text.split('').map((letter, index) => {
                if (letter === ' ') {
                    return '<span class="letter-space" data-letter-index="' + index + '">&nbsp;</span>';
                }
                return '<span class="letter" data-letter-index="' + index + '" style="opacity: 0; filter: blur(10px); display: inline-block; transform: translateY(20px);">' + 
                       this.escapeHtml(letter) + '</span>';
            }).join('');

            targetElement.innerHTML = letters;
            
            // Show container but keep letters hidden
            element.style.opacity = '1';
        }

        prepareTypewriter(element) {
            const text = element.textContent || element.innerText;
            const isLink = element.querySelector('a');
            const targetElement = isLink ? element.querySelector('a') : element;
            
            // Store original text and clear content
            element.dataset.originalText = text;
            targetElement.innerHTML = '<span class="typewriter-text"></span><span class="typewriter-cursor">|</span>';
            
            // Show container
            element.style.opacity = '1';
            
            // Style cursor
            const cursor = element.querySelector('.typewriter-cursor');
            if (cursor) {
                cursor.style.opacity = '1';
                cursor.style.animation = `blink ${element.dataset.cursorBlink || 500}ms infinite`;
            }
        }

        setupObserver(element) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !element.classList.contains('animation-started')) {
                        element.classList.add('animation-started');
                        this.startAnimation(element);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            observer.observe(element);
            this.observers.push(observer);
        }

        startAnimation(element) {
            const animation = element.dataset.animation;
            const delay = parseInt(element.dataset.delay) || 0;

            setTimeout(() => {
                switch (animation) {
                    case 'staggered_blur_fade':
                        this.animateStaggeredBlurFade(element);
                        break;
                    case 'typewriter':
                        this.animateTypewriter(element);
                        break;
                }
            }, delay);
        }

        animateStaggeredBlurFade(element) {
            const letters = element.querySelectorAll('.letter');
            const staggerDelay = parseInt(element.dataset.staggerDelay) || 50;
            const duration = parseInt(element.dataset.duration) || 2000;
            const letterDuration = Math.min(duration / letters.length, 800); // Max 800ms per letter

            letters.forEach((letter, index) => {
                setTimeout(() => {
                    letter.style.transition = `opacity ${letterDuration}ms ease-out, 
                                             filter ${letterDuration}ms ease-out, 
                                             transform ${letterDuration}ms cubic-bezier(0.175, 0.885, 0.32, 1.275)`;
                    letter.style.opacity = '1';
                    letter.style.filter = 'blur(0px)';
                    letter.style.transform = 'translateY(0px)';
                }, index * staggerDelay);
            });

            // Mark as completed
            setTimeout(() => {
                element.classList.add('animation-completed');
            }, (letters.length * staggerDelay) + letterDuration);
        }

        animateTypewriter(element) {
            const originalText = element.dataset.originalText;
            const typingSpeed = parseInt(element.dataset.typingSpeed) || 100;
            const textContainer = element.querySelector('.typewriter-text');
            const cursor = element.querySelector('.typewriter-cursor');
            
            if (!textContainer || !originalText) return;

            let currentIndex = 0;
            
            const typeNextCharacter = () => {
                if (currentIndex < originalText.length) {
                    const char = originalText.charAt(currentIndex);
                    textContainer.textContent += char;
                    currentIndex++;
                    
                    setTimeout(typeNextCharacter, typingSpeed);
                } else {
                    // Animation completed
                    element.classList.add('animation-completed');
                    
                    // Optionally hide cursor after completion
                    setTimeout(() => {
                        if (cursor) {
                            cursor.style.opacity = '0';
                        }
                    }, 2000);
                }
            };

            typeNextCharacter();
        }

        clearObservers() {
            this.observers.forEach(observer => {
                observer.disconnect();
            });
            this.observers = [];
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Public method to restart animations (useful for dynamic content)
        restartAnimations() {
            const elements = document.querySelectorAll('.has-entrance-animation');
            elements.forEach(element => {
                element.classList.remove('animation-started', 'animation-completed');
                element.style.opacity = '0';
            });
            
            setTimeout(() => {
                this.initializeAnimations();
            }, 100);
        }

        // Public method to pause all animations
        pauseAnimations() {
            const elements = document.querySelectorAll('.has-entrance-animation .letter, .typewriter-text');
            elements.forEach(element => {
                element.style.animationPlayState = 'paused';
                element.style.transitionDuration = '0s';
            });
        }

        // Public method to resume all animations
        resumeAnimations() {
            const elements = document.querySelectorAll('.has-entrance-animation .letter, .typewriter-text');
            elements.forEach(element => {
                element.style.animationPlayState = 'running';
                element.style.transitionDuration = '';
            });
        }
    }

    // CSS Animations for cursor blinking
    const addCursorBlinkStyles = () => {
        if (document.getElementById('html-tag-cursor-styles')) return;

        const style = document.createElement('style');
        style.id = 'html-tag-cursor-styles';
        style.textContent = `
            @keyframes blink {
                0%, 50% { opacity: 1; }
                51%, 100% { opacity: 0; }
            }
            
            .typewriter-cursor {
                display: inline-block;
                margin-left: 2px;
                font-weight: 100;
            }
            
            .html-tag-element.animation-completed .typewriter-cursor {
                animation: none !important;
            }
            
            /* Smooth scroll behavior for animated elements */
            .has-entrance-animation {
                scroll-margin-top: 100px;
            }
            
            /* Prevent layout shift during animation preparation */
            .has-entrance-animation.animation-staggered_blur_fade {
                min-height: 1em;
            }
            
            /* Accessibility: Respect user's motion preferences */
            @media (prefers-reduced-motion: reduce) {
                .has-entrance-animation .letter {
                    transition-duration: 0.1s !important;
                }
                .has-entrance-animation[data-animation="typewriter"] .typewriter-text {
                    animation-duration: 0.1s !important;
                }
            }
        `;
        document.head.appendChild(style);
    };

    // Initialize everything
    const initHTMLTagAnimations = () => {
        addCursorBlinkStyles();
        window.HTMLTagAnimations = new HTMLTagAnimations();
    };

    // Auto-initialize
    initHTMLTagAnimations();

    // Expose restart method globally for external use
    window.restartHTMLTagAnimations = () => {
        if (window.HTMLTagAnimations) {
            window.HTMLTagAnimations.restartAnimations();
        }
    };

    // jQuery compatibility
    if (typeof $ !== 'undefined') {
        $.fn.restartHTMLTagAnimations = function() {
            if (window.HTMLTagAnimations) {
                window.HTMLTagAnimations.restartAnimations();
            }
            return this;
        };
        
        // Elementor compatibility
        $(window).on('elementor/frontend/init', function() {
            setTimeout(initHTMLTagAnimations, 500);
        });
    }

})(typeof jQuery !== 'undefined' ? jQuery : null);