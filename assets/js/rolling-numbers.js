(function ($) {
    'use strict';
    
    $(document).ready(function () {
        function rollingNumbers($scope, $) {
            const reelWrapper = $scope.find('.reel-wrapper')[0];
            
            if (!reelWrapper) return;
            
            const targetNumber = reelWrapper.getAttribute('data-number');
            
            if (!targetNumber) return;
            
            // Clear existing content
            reelWrapper.innerHTML = '';
            
            // Build digit reels
            for (let digit of targetNumber) {
                const reel = document.createElement('div');
                reel.className = 'reel-container';
                reel.style.cssText = `
                    overflow: hidden;
                    height: 4rem;
                    border-radius: 0.375rem;
                    text-align: center;
                    margin: 0 0.125rem;
                `;
                
                const column = document.createElement('div');
                column.className = 'reel-column';
                column.style.cssText = `
                    display: flex;
                    flex-direction: column;
                `;
                
                // Create digits 0-9 plus extra 0 at end for smooth animation
                for (let i = 0; i <= 10; i++) {
                    const num = document.createElement('div');
                    num.textContent = i % 10;
                    num.className = 'reel-digit';
                    num.style.cssText = `
                        height: 4rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-family: monospace;
                        font-weight: bold;
                    `;
                    column.appendChild(num);
                }
                
                reel.appendChild(column);
                reelWrapper.appendChild(reel);
            }
            
            // Initialize Lucide icons if present
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            // Check if GSAP and ScrollTrigger are available
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                
                // Create ScrollTrigger animation
                ScrollTrigger.create({
                    trigger: reelWrapper,
                    start: 'top 80%',
                    once: true,
                    onEnter: () => {
                        const reels = reelWrapper.querySelectorAll('.reel-column');
                        
                        reels.forEach((col, i) => {
                            const digit = +targetNumber[i];
                            
                            gsap.to(col, {
                                y: `-${digit * 4}rem`,
                                duration: 1.5,
                                delay: i * 0.1,
                                ease: 'power2.out'
                            });
                        });
                    }
                });
            } else {
                // Fallback animation without GSAP
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const reels = entry.target.querySelectorAll('.reel-column');
                            
                            reels.forEach((col, i) => {
                                const digit = +targetNumber[i];
                                
                                setTimeout(() => {
                                    col.style.transition = 'transform 1.5s ease-out';
                                    col.style.transform = `translateY(-${digit * 4}rem)`;
                                }, i * 100);
                            });
                            
                            observer.unobserve(entry.target);
                        }
                    });
                });
                
                observer.observe(reelWrapper);
            }
        }
        
        // Hook into Elementor frontend
        elementorFrontend.hooks.addAction('frontend/element_ready/rolling_numbers.default', rollingNumbers);
    });
})(jQuery);