<header class="sticky w-full top-0 left-0 py-4 border-b-2 border-gray-300 bg-white z-50">
    <div class="mx-auto px-4 lg:px-8 max-w-[1600px] flex items-center justify-between">

        {{-- Brand Logo --}}
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset($brandlogo) }}" alt="{{ $brandname }}" class="h-10">
        </a>

        {{-- Mobile Menu Toggle --}}
        <button onclick="toggleMenu()" class="lg:hidden text-[#012252] focus:outline-none transition-all duration-200">
            <svg id="icon-hamburger" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>

            <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2" class="w-8 h-8 hidden">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Navigation --}}
        <nav id="nav-menu"
            class="hidden lg:flex flex-col lg:flex-row items-center gap-4 lg:gap-8 absolute lg:static top-full left-0 w-full lg:w-auto bg-white lg:bg-transparent lg:border-0 shadow-lg lg:shadow-none p-4 lg:p-0 transition-all duration-300">

            @if (Auth::guard('wholesaler')->check())
                {{-- Chat Icon with Notification Badge --}}
                <a href="/wholesaler/chats" class="w-[40px] h-[40px] flex items-center justify-center p-2 relative">
                    <img src="/assets/images/chat.png" alt="Messages" class="w-full">
                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </a>

                {{-- Profile Circle with Arrow --}}
                <div class="relative">
                    <div class="hidden lg:flex items-center gap-2 cursor-pointer toggle_profile_details select-none">
                        <div
                            class="w-[40px] h-[40px] flex items-center justify-center bg-[#F6F6F6] text-[#46484D] rounded-full font-semibold text-xl">
                            @if (Auth::guard('wholesaler')->user()->profile_picture)
                                <img src="{{ asset(Auth::guard('wholesaler')->user()->profile_picture) }}"
                                    alt="{{ Auth::guard('wholesaler')->user()->company_name }}"
                                    class="w-full h-full rounded-full">
                            @else
                                <div
                                    class="w-[40px] h-[40px] flex items-center justify-center p-2 bg-[#F6F6F6] text-[#46484D] rounded-full font-semibold text-xl">
                                    {{ strtoupper(substr(Auth::guard('wholesaler')->user()->company_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        {{-- Arrow Icon --}}
                        <svg id="arrow-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2"
                            class="w-5 h-5 text-gray-600 transition-transform duration-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    {{-- Dropdown Menu --}}
                    <div id="profileDropdown"
                        class="lg:hidden text-sm lg:text-md lg:absolute right-0 mt-2 bg-white lg:border border-gray-200 rounded-lg lg:shadow-lg lg:px-4 lg:py-2 text-gray-700 z-50">
                        <a href="/wholesaler/set-up-wholesaler-profile"
                            class="block px-4 py-2 hover:bg-gray-100 transition-colors duration-150 rounded-md">Profile</a>
                        <a href="/wholesaler/set-up-wholesaler-settings"
                            class="block px-4 py-2 hover:bg-gray-100 transition-colors duration-150 rounded-md">Settings</a>
                        <hr class="my-1 hidden lg:block">

                        <button type="button"
                            class="w-full block px-4 py-2 text-red-600 hover:bg-gray-100 transition-colors duration-150 text-left rounded-md"
                            onclick="logoutPopUp()">
                            Log out
                        </button>
                    </div>
                </div>
            @elseif(Auth::guard('manufacturer')->check())
                {{-- Chat Icon with Notification Badge --}}
                <a href="/manufacturer/chats" class="w-[40px] h-[40px] flex items-center justify-center p-2 relative">
                    <img src="/assets/images/chat.png" alt="Messages" class="w-full">
                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </a>

                {{-- Profile Circle with Arrow --}}
                <div class="relative">
                    <div class="hidden lg:flex items-center gap-2 cursor-pointer toggle_profile_details select-none">
                        <div
                            class="w-[40px] h-[40px] flex items-center justify-center bg-[#F6F6F6] text-[#46484D] rounded-full font-semibold text-xl">
                            @if (Auth::guard('manufacturer')->user()->company_logo)
                                <img src="{{ asset(Auth::guard('manufacturer')->user()->company_logo) }}"
                                    alt="{{ Auth::guard('manufacturer')->user()->company_name }}"
                                    class="w-full h-full rounded-full">
                            @else
                                <img src="/assets/images/menufacturer.png" alt="Default Company Logo"
                                    class="w-full h-full rounded-full">
                            @endif
                        </div>

                        {{-- Arrow Icon --}}
                        <svg id="arrow-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2"
                            class="w-5 h-5 text-gray-600 transition-transform duration-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    {{-- Dropdown Menu --}}
                    <div id="profileDropdown"
                        class="lg:hidden text-sm lg:text-md lg:absolute right-0 mt-2 bg-white lg:border border-gray-200 rounded-lg lg:shadow-lg lg:px-4 lg:py-2 text-gray-700 z-50">
                        <a href="/manufacturer/packages"
                            class="block px-4 py-2 hover:bg-gray-100 transition-colors duration-150 rounded-md">Packages</a>
                        <a href="/manufacturer/manage-subscription"
                            class="block px-4 py-2 hover:bg-gray-100 transition-colors duration-150 rounded-md">Subscription</a>
                        <a href="/manufacturer/set-up-manufacturer-profile"
                            class="block px-4 py-2 hover:bg-gray-100 transition-colors duration-150 rounded-md">Profile</a>
                        <a href="/manufacturer/set-up-manufacturer-settings"
                            class="block px-4 py-2 hover:bg-gray-100 transition-colors duration-150 rounded-md">Settings</a>
                        <hr class="my-1 hidden lg:block">

                        <button type="button"
                            class="w-full block px-4 py-2 text-red-600 hover:bg-gray-100 transition-colors duration-150 text-left rounded-md"
                            onclick="logoutPopUp()">
                            Log out
                        </button>
                    </div>
                </div>
            @else
                <div class="flex gap-2">
                    <a href="/manufacturer/login"
                        class="block text-gray-700 hover:text-[#003FB4] text-center px-4 py-2 font-semibold transition-colors duration-200">
                        Log in
                    </a>

                    <a href="/manufacturer/packages"
                        class="block text-gray-700 hover:text-[#003FB4] text-center px-4 py-2 font-semibold transition-colors duration-200">
                        Packages
                    </a>
                </div>

                <a href="/create-account"
                    class="block text-white hover:bg-[#002F8E] rounded-lg px-4 py-3 bg-[#003FB4] text-center font-semibold transition-all duration-200">
                    Create account
                </a>
            @endif
        </nav>
    </div>
</header>




<div id="logoutModal" class="modal-overlay">
    <div class="modal-content filter_content">
        <div class="col-span-3 p-4 py-10 rounded-lg bg-[#F6F6F6] mx-4 lg:w-[680px] lg:mx-auto logout_modal relative">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeLogoutModal()">
            <img src="/assets/images/log-out.png" alt="" class="w-24 rounded block mx-auto">
            <h3 class="text-xl my-4 mb-8 text-[40px] text-[#121212] text-center">
                Are you sure you want to <br> log out?
            </h3>

            <div
                class="flex lg:flex-row justify-center items-center gap-2 lg:gap-8 absolute lg:static top-full left-0 w-full lg:w-auto bg-white lg:bg-transparent lg:border-0 shadow-lg lg:shadow-none p-4 lg:p-0 transition-all duration-300">
                <button class="block text-[#003FB4] text-center px-2 py-2 transition-colors duration-200"
                    onclick="closeLogoutModal()">
                    Stay signed in
                </button>
                @if (Auth::guard('wholesaler')->check())
                    <form method="POST" action="/wholesaler/logout">
                        @csrf
                        <button type="submit"
                            class="block text-white hover:bg-[#002F8E] rounded-lg px-4 py-2 bg-[#003FB4] text-center transition-all duration-200">
                            Log out
                        </button>
                    </form>
                @else
                    <form method="POST" action="/manufacturer/logout">
                        @csrf
                        <button type="submit"
                            class="block text-white hover:bg-[#002F8E] rounded-lg px-4 py-2 bg-[#003FB4] text-center transition-all duration-200">
                            Log out
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- Scripts --}}
<script>
    // Mobile nav toggle
    function toggleMenu() {
        const menu = document.getElementById('nav-menu');
        const iconHamburger = document.getElementById('icon-hamburger');
        const iconClose = document.getElementById('icon-close');
        menu.classList.toggle('hidden');
        iconHamburger.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    }

    // Profile dropdown toggle with arrow rotation
    document.addEventListener('click', function(event) {
        const toggle = document.querySelector('.toggle_profile_details');
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('arrow-icon');

        if (toggle && toggle.contains(event.target)) {
            dropdown.classList.toggle('lg:hidden');

            // Rotate arrow - up when open, down when closed
            if (dropdown.classList.contains('lg:hidden')) {
                arrow.style.transform = 'rotate(0deg)';
            } else {
                arrow.style.transform = 'rotate(180deg)';
            }
        } else if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.add('lg:hidden');
            if (arrow) {
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('nav-menu');
        const menuButton = document.querySelector('button[onclick="toggleMenu()"]');

        if (menu && !menu.contains(event.target) && !menuButton.contains(event.target)) {
            if (!menu.classList.contains('hidden') && window.innerWidth < 1024) {
                toggleMenu();
            }
        }
    });


    function logoutPopUp() {
        document.getElementById('logoutModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
</script>