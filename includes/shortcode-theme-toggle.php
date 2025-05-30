<?php

function theme_toggle_shortcode()
{
    ob_start();
?>

    <div id="theme-toggle"
        class="relative w-12 h-6 mr-4 md:ml-4 rounded-full bg-slate-300/20 dark:bg-slate-800/60 cursor-pointer transition-all duration-500 overflow-hidden">
        <!-- Toggle knob -->
        <div class="absolute top-[2px] left-[2px] w-5 h-5 bg-slate-50 dark:bg-slate-900 rounded-full shadow-sm transition-all duration-500 dark:translate-x-6"></div>
        <!-- Sun Icon -->
        <span
            class="absolute top-1 left-1 w-4 h-4 text-yellow-500 opacity-100 dark:opacity-0 transition-opacity duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
            </svg>
        </span>
        <!-- Moon Icon -->
        <span
            class="absolute top-1 right-1 w-4 h-4 text-blue-500 opacity-0 dark:opacity-100 transition-opacity duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path
                    fill-rule="evenodd"
                    d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('hatdog1');

            // Function to get the initial theme
            function getInitialTheme() {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme) {
                    return savedTheme;
                }
                // Check if system prefers dark mode
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            const htmlEl = document.documentElement; // <html> element
            const initialTheme = getInitialTheme();

            // Set the initial data-theme attribute
            htmlEl.setAttribute('data-theme', initialTheme);

            // Function to toggle theme
            function toggleTheme() {
                console.log('toggle');
                const currentTheme = htmlEl.getAttribute('data-theme');
                if (currentTheme === 'dark') {
                    htmlEl.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                } else {
                    htmlEl.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

            // Attach click handlers to toggle buttons
            document.querySelectorAll('#theme-toggle, #mobile-theme-toggle').forEach(function(element) {
                element.addEventListener('click', function() {
                    toggleTheme();
                    console.log('hatdog');
                });
            });
        });
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('theme_toggle', 'theme_toggle_shortcode');
