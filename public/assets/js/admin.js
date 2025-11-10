// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== Sidebar Toggle for Mobile =====
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
    }
    
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }
    
    // Close sidebar when clicking a link on mobile
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) {
                toggleSidebar();
            }
        });
    });
    
    // ===== Submenu Toggle =====
    const menuToggles = document.querySelectorAll('.menu-toggle');
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const submenu = this.nextElementSibling;
            const arrow = this.querySelector('svg');
            
            // Close other submenus
            menuToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    const otherSubmenu = otherToggle.nextElementSibling;
                    const otherArrow = otherToggle.querySelector('svg');
                    otherSubmenu.classList.add('hidden');
                    otherArrow.classList.remove('rotate-180');
                }
            });
            
            // Toggle current submenu
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });
    
    // Auto-open active submenu
    const activeSubmenuItem = document.querySelector('.submenu a.bg-gray-50, .submenu a.bg-blue-50');
    if (activeSubmenuItem) {
        const submenu = activeSubmenuItem.closest('.submenu');
        const toggle = submenu.previousElementSibling;
        const arrow = toggle.querySelector('svg');
        submenu.classList.remove('hidden');
        arrow.classList.add('rotate-180');
    }
    
    // ===== Profile Dropdown =====
    const profileDropdown = document.getElementById('profileDropdown');
    const profileMenu = document.getElementById('profileMenu');
    
    if (profileDropdown && profileMenu) {
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    }
    
    // ===== Dark Mode Toggle =====
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
        html.classList.add('dark');
    }
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            html.classList.toggle('dark');
            
            // Save preference
            const theme = html.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });
    }
    
    // ===== Close sidebar on window resize =====
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
    });
    
});