<?php

function navbar_shortcode()
{
    ob_start();
?>
    <div class="flex items-center px-6 py-4 md:py-6 text-[#2d2c2b] font-[Inter,sans-serif]">
        <div class="text-xl font-bold cursor-pointer">ParMedia</div>

        <!-- Hamburger Button -->
        <button id="menu-toggle"
            class="md:hidden relative w-8 h-6 focus:outline-none z-50 group ml-auto cursor-pointer">
            <span class="absolute bg-[#2d2c2b] top-1 left-0 w-full h-0.5 transition-all duration-300 group-[.open]:rotate-45 group-[.open]:top-2.5 group-[.open]:bg-white"></span>
            <span class="absolute bg-[#2d2c2b] top-2.5 left-0 w-full h-0.5 transition-all duration-300 group-[.open]:opacity-0 group-[.open]:bg-white"></span>
            <span class="absolute bg-[#2d2c2b] top-4 left-0 w-full h-0.5 transition-all duration-300 group-[.open]:-rotate-45 group-[.open]:top-2.5 group-[.open]:bg-white"></span>
        </button>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex gap-6 text-lg font-medium ml-20">
            <a href="#" class="transition-all hover:text-[#c8b2ab]">Home</a>
            <a href="#" class="transition-all hover:text-[#c8b2ab]">About</a>
            <a href="#" class="transition-all hover:text-[#c8b2ab]">Services</a>
            <a href="#" class="transition-all hover:text-[#c8b2ab]">Contact</a>
        </nav>
    </div>

    <!-- Mobile Fullscreen Menu -->
    <div id="mobile-menu"
        class="fixed inset-0 bg-[#1e1d1d] text-white flex flex-col items-center justify-center gap-8 text-2xl font-semibold opacity-0 pointer-events-none transition-all duration-300 md:hidden font-['Space_Grotesk',sans-serif]">
        <a href="#" class="hover:text-[#c8b2ab] transition-all">Home</a>
        <a href="#" class="hover:text-[#c8b2ab] transition-all">About</a>
        <a href="#" class="hover:text-[#c8b2ab] transition-all">Services</a>
        <a href="#" class="hover:text-[#c8b2ab] transition-all">Contact</a>
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

