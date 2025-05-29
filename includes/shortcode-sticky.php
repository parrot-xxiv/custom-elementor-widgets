<?php

function navbar_shortcode()
{
    ob_start();
?>
    <div class="flex items-center px-6 py-4 md:py-6">
        <div class="text-xl font-bold cursor-pointer">Brand</div>

        <!-- Hamburger Button -->
        <button id="menu-toggle" class="md:hidden relative w-8 h-6 focus:outline-none z-50 group ml-auto cursor-pointer">
            <span class="absolute group-[.open]:bg-white top-1 left-0 w-full h-0.5 bg-black transition-all duration-300 group-[.open]:rotate-45 group-[.open]:top-2.5"></span>
            <span class="absolute group-[.open]:bg-white top-2.5 left-0 w-full h-0.5 bg-black transition-all duration-300 group-[.open]:opacity-0"></span>
            <span class="absolute group-[.open]:bg-white top-4 left-0 w-full h-0.5 bg-black transition-all duration-300 group-[.open]:-rotate-45 group-[.open]:top-2.5"></span>
        </button>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex gap-6 text-lg font-medium ml-20">
            <a href="#" class="hover:text-white transition-all">Home</a>
            <a href="#" class="hover:text-white transition-all">About</a>
            <a href="#" class="hover:text-white transition-all">Services</a>
            <a href="#" class="hover:text-white transition-all">Contact</a>
        </nav>
    </div>

    <!-- Mobile Fullscreen Menu -->
    <div id="mobile-menu"
        class="fixed inset-0 bg-gray-900 text-white flex flex-col items-center justify-center gap-8 text-2xl font-semibold opacity-0 pointer-events-none transition-all duration-300 md:hidden">
        <a href="#" class="hover:text-blue-400">Home</a>
        <a href="#" class="hover:text-blue-400">About</a>
        <a href="#" class="hover:text-blue-400">Services</a>
        <a href="#" class="hover:text-blue-400">Contact</a>
    </div>

    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');

        toggleBtn.addEventListener('click', () => {
            toggleBtn.classList.toggle('open');
            menu.classList.toggle('opacity-100');
            menu.classList.toggle('pointer-events-auto');
            menu.classList.toggle('opacity-0');
            menu.classList.toggle('pointer-events-none');

        });
    </script>

<?php
    return ob_get_clean();
}
add_shortcode('navbar_par', 'navbar_shortcode');
