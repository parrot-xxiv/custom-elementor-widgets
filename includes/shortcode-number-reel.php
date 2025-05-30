<?php

function number_reel_shortcode($atts) {
    // Extract attributes with defaults
    $atts = shortcode_atts([
        'data_number' => '0000',
        'reel_id'     => 'reel-wrapper'
    ], $atts);

    ob_start();
    ?>

    

    <!-- Number Reel Section -->
    <div class="flex justify-center bg-white">
       <i data-lucide="arrow-up"></i>
      <div id="<?php echo esc_attr($atts['reel_id']); ?>" class="flex" data-number="<?php echo esc_attr($atts['data_number']); ?>"></div>
       <i data-lucide="plus" class="self-center"></i>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const reelWrapper = document.getElementById("<?php echo esc_js($atts['reel_id']); ?>");
        const targetNumber = reelWrapper.getAttribute("data-number");

        // Build digit reels
        for (let digit of targetNumber) {
          const reel = document.createElement("div");
          reel.className = "reel-container overflow-hidden h-16 w-8 font-semibold rounded text-center";
          reel.style.fontFamily = '"Space Grotesk", Sans-serif';

          const column = document.createElement("div");
          column.className = "reel-column flex flex-col transition-transform duration-1000 ease-out";

          for (let i = 0; i <= 10; i++) {
            const num = document.createElement("div");
            num.textContent = i % 10;
            num.className = "h-16 flex items-center justify-center text-5xl";
            column.appendChild(num);
          }

          reel.appendChild(column);
          reelWrapper.appendChild(reel);
        }

        // Scroll trigger using IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const reels = document.querySelectorAll(`#<?php echo esc_js($atts['reel_id']); ?> .reel-column`);
              reels.forEach((col, i) => {
                const digit = +targetNumber[i];
                col.style.transform = `translateY(-${digit * 4}rem)`;
                col.style.transitionDelay = `${i * 0.1}s`;
              });
              observer.disconnect(); // Only trigger once
            }
          });
        }, {
          threshold: 0.5
        });

        observer.observe(reelWrapper);
      });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('number_reel', 'number_reel_shortcode');
