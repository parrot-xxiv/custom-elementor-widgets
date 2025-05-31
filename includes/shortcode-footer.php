<?php

function footer_par_shortcode()
{
    ob_start();
?>

    <footer class="bg-[#1e1d1d] text-[#f7d2ca] px-6 pt-12 pb-6 text-sm font-[Inter]">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">

            <!-- Social Links -->
            <div>
                <h4 class="font-semibold mb-4 text-[#c8b2ab] font-[Space_Grotesk]">Follow Us</h4>
                <ul class="flex gap-4 text-[#727272]">
                    <li>
                        <a href="#" class="hover:text-[#f7d2ca] transition-colors">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-[#f7d2ca] transition-colors">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-[#f7d2ca] transition-colors">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-[#f7d2ca] transition-colors">
                            <i data-lucide="behance" class="w-5 h-5"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Office Locations -->
            <div>

                <h4 class="font-semibold mb-4 text-[#c8b2ab] font-[Space_Grotesk]">Manila</h4>
                <p class="text-[#727272]">Par Media.<br>123 Manila, Metro Manila.</p>
            </div>

            <!-- Work Inquiries -->
            <div>
                <h4 class="font-semibold mb-4 text-[#c8b2ab] font-[Space_Grotesk]">Work Inquiries</h4>
                <p class="mb-2 text-[#727272]">Interested in working with us?</p>
                <a href="mailto:hello@yourdomain.com" class="text-[#c8b2ab] hover:underline">eldren@par-media.com</a>

                <h4 class="font-semibold mt-6 mb-2 text-[#c8b2ab] font-[Space_Grotesk]">Work With Us</h4>
                <p class="mb-2 text-[#727272]">Looking for a job opportunity?</p>
                <a href="mailto:hr@yourdomain.com" class="text-[#c8b2ab] hover:underline">hr@par-media.com</a>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-semibold mb-4 text-[#c8b2ab] font-[Space_Grotesk]">Sign up for the newsletter</h4>
                <form class="space-y-3">
                    <input
                        type="email"
                        placeholder="Enter your email..."
                        class="w-full px-4 py-2 rounded border border-[#727272] bg-[#2d2c2b] text-[#f7d2ca] placeholder-[#727272] focus:outline-none focus:ring-2 focus:ring-[#c8b2ab]" />
                    <button
                        type="submit"
                        class="w-full bg-[#c8b2ab] hover:bg-[#f7d2ca] text-[#1e1d1d] py-2 rounded transition">
                        Subscribe
                    </button>
                </form>
                <p class="text-xs text-[#727272] mt-4">Protecting your privacy</p>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-[#727272] mt-12 pt-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-[#727272] gap-4 text-xs">
                <span>© 2025 Eldren Par</span>

                <div class="flex gap-4">
                    <a href="#" class="hover:underline hover:text-[#f7d2ca]">Privacy Policy</a>
                    <a href="#" class="hover:underline hover:text-[#f7d2ca]">Terms and Conditions</a>
                    <a href="#" class="hover:underline hover:text-[#f7d2ca]">Copyright</a>
                </div>
            </div>
        </div>
    </footer>

<?php
    return ob_get_clean();
}
add_shortcode('footer_par', 'footer_par_shortcode');
