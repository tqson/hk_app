<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileToggle = document.getElementById('mobile-toggle');
        const footer = document.querySelector('.footer');

        // Tooltip instances
        let tooltipInstances = [];

        // Function to initialize tooltips
        function initTooltips(onlyCollapsed = false) {
            // Dispose existing tooltips
            destroyTooltips();

            // Get all tooltip elements
            const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');

            tooltipElements.forEach(el => {
                // Only initialize tooltips for sidebar items when sidebar is collapsed
                if (!onlyCollapsed || (onlyCollapsed && sidebar.classList.contains('sidebar-collapsed'))) {
                    tooltipInstances.push(new bootstrap.Tooltip(el));
                }
            });
        }

        // Function to destroy all tooltips
        function destroyTooltips() {
            tooltipInstances.forEach(tooltip => {
                tooltip.dispose();
            });
            tooltipInstances = [];
        }

        // Function to close all submenus
        function closeAllSubmenus() {
            const openItems = document.querySelectorAll('.sidebar-menu-item.open');
            openItems.forEach(function(openItem) {
                openItem.classList.remove('open');
                const submenu = openItem.querySelector('.sidebar-submenu');
                if (submenu) {
                    submenu.style.display = 'none';
                }
            });
        }

        // Check if sidebar state is saved in localStorage
        const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

        // Apply initial state
        if (sidebarCollapsed) {
            sidebar.classList.add('sidebar-collapsed');
            mainContent.classList.add('main-content-collapsed');
            if (footer) footer.classList.add('footer-collapsed');

            // Close all submenus when sidebar is collapsed
            closeAllSubmenus();

            // Initialize tooltips for collapsed sidebar
            initTooltips(true);
        } else {
            // Disable tooltips when sidebar is expanded
            destroyTooltips();
        }

        // Toggle sidebar on button click
        sidebarToggle.addEventListener('click', function() {
            const willBeCollapsed = !sidebar.classList.contains('sidebar-collapsed');

            sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('main-content-collapsed');
            if (footer) footer.classList.toggle('footer-collapsed');

            // Save state to localStorage
            localStorage.setItem('sidebar-collapsed', willBeCollapsed);

            // If sidebar will be collapsed, close all submenus
            if (willBeCollapsed) {
                closeAllSubmenus();
                initTooltips(true);
            } else {
                destroyTooltips();
            }
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

                // Don't toggle submenus if sidebar is collapsed
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    return;
                }

                const parent = this.parentElement;
                const submenu = parent.querySelector('.sidebar-submenu');

                // Close all other open submenus
                const openItems = document.querySelectorAll('.sidebar-menu-item.open');
                openItems.forEach(function(openItem) {
                    if (openItem !== parent) {
                        openItem.classList.remove('open');
                        const otherSubmenu = openItem.querySelector('.sidebar-submenu');
                        if (otherSubmenu) {
                            otherSubmenu.style.display = 'none';
                        }
                    }
                });

                // Toggle current submenu
                parent.classList.toggle('open');
                if (submenu) {
                    submenu.style.display = parent.classList.contains('open') ? 'block' : 'none';
                }
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

                // Open parent menu only if sidebar is not collapsed
                if (!sidebar.classList.contains('sidebar-collapsed')) {
                    const parentMenuItem = link.closest('.sidebar-menu-item');
                    if (parentMenuItem) {
                        parentMenuItem.classList.add('open');
                        const submenu = parentMenuItem.querySelector('.sidebar-submenu');
                        if (submenu) {
                            submenu.style.display = 'block';
                        }
                    }
                }
            }
        });
    });
</script>
