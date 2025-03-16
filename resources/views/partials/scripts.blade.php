<script>
    // Toggle sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileToggle = document.getElementById('mobile-toggle');
        const footer = document.querySelector('.footer');

        // Check if sidebar state is saved in localStorage
        const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

        // Apply initial state
        if (sidebarCollapsed) {
            sidebar.classList.add('sidebar-collapsed');
            mainContent.classList.add('main-content-collapsed');
            if (footer) footer.classList.add('footer-collapsed');
        }

        // Toggle sidebar on button click
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('main-content-collapsed');
            if (footer) footer.classList.toggle('footer-collapsed');

            // Save state to localStorage
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('sidebar-collapsed'));
        });

        // Mobile toggle
        if (mobileToggle) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-visible');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isMobile = window.innerWidth <= 768;
            if (isMobile && !sidebar.contains(event.target) && event.target !== mobileToggle) {
                sidebar.classList.remove('sidebar-visible');
            }
        });

        // Handle submenu toggles
        const menuItemsWithSubmenu = document.querySelectorAll('.sidebar-menu-link.has-submenu');

        menuItemsWithSubmenu.forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const parent = this.parentElement;

                // Close all other open submenus
                const openItems = document.querySelectorAll('.sidebar-menu-item.open');
                openItems.forEach(function(openItem) {
                    if (openItem !== parent) {
                        openItem.classList.remove('open');
                    }
                });

                // Toggle current submenu
                parent.classList.toggle('open');
            });
        });

        // Initialize Flatpickr date pickers
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "vn",
                allowInput: true
            });

            flatpickr(".datetimepicker", {
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                time_24hr: true,
                locale: "vn",
                allowInput: true
            });
        }

        // Set active state for submenu items
        const currentPath = window.location.pathname;
        const submenuLinks = document.querySelectorAll('.sidebar-submenu-link');

        submenuLinks.forEach(function(link) {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');

                // Open parent menu
                const parentMenuItem = link.closest('.sidebar-menu-item');
                if (parentMenuItem) {
                    parentMenuItem.classList.add('open');
                }
            }
        });
    });

    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
</script>
