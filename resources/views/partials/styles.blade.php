<style>
    :root {
        --primary-color: #117CC0;
        --secondary-color: #E9FCFD;
        --success-color: #69db7c;
        --warning-color: #ffd43b;
        --danger-color: #ff8787;
        --dark-color: #343a40;
        --light-color: #f8f9fa;
        --sidebar-width: 307px;
        --sidebar-collapsed-width: 80px;
        --topbar-height: 68px;
        --border-radius: 8px;
        --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --transition-speed: 0.3s;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        max-width: 1920px;
        margin: 0 auto;
    }

    /* Top Bar Styles */
    .topbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: var(--topbar-height);
        background-color: var(--primary-color);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 32px;
        z-index: 1001;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 1920px;
        margin: 0 auto;
    }

    .topbar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .topbar-logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
        text-decoration: none;
    }

    .topbar-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .topbar-user-info {
        text-align: right;
    }

    .topbar-user-name {
        font-weight: 500;
        font-size: 0.9rem;
    }

    .topbar-user-role {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .topbar-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        overflow: hidden;
    }

    .topbar-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* User dropdown styles */
    .topbar-user-avatar {
        position: relative;
        cursor: pointer;
    }

    .topbar-user-avatar .dropdown-toggle {
        display: block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }

    .topbar-user-avatar .dropdown-toggle::after {
        display: none; /* Hide default dropdown arrow */
    }

    .topbar-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s;
    }

    .topbar-user-avatar:hover img {
        transform: scale(1.05);
    }

    .topbar-user .dropdown-menu {
        min-width: 220px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .topbar-user .dropdown-item {
        padding: 0.6rem 1.5rem;
        color: var(--dark-color);
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .topbar-user .dropdown-item:hover {
        background-color: var(--secondary-color);
        color: var(--primary-color);
    }

    .topbar-user .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    .topbar-user .dropdown-divider {
        margin: 0.5rem 0;
        border-top: 1px solid #f0f0f0;
    }

    .topbar-user-avatar.dropdown {
        position: relative;
        cursor: pointer;
    }

    .topbar-user-avatar .dropdown-menu {
        position: absolute;
        right: 0;
        left: auto;
        top: 100%;
        margin-top: 0.5rem;
        z-index: 1002; /* Ensure it's above other elements */
    }

    /* Add this to ensure dropdown is visible */
    .dropdown-menu.show {
        display: block;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: var(--topbar-height);
        left: 0;
        height: calc(100vh - var(--topbar-height));
        width: var(--sidebar-width);
        background-color: white;
        transition: all var(--transition-speed);
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        overflow-y: auto;
        padding: 20px 4px;
    }

    .sidebar-collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar-menu {
        display: flex;
        width: 100%;
        padding: 4px;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        flex-shrink: 0;
        align-self: stretch;
        list-style: none;
        margin: 0;
    }

    .sidebar-menu-item {
        width: 100%;
        margin-bottom: 4px;
    }

    .sidebar-menu-link {
        display: flex;
        padding: 0px 16px 0px 24px;
        align-items: center;
        gap: 10px;
        align-self: stretch;
        border-radius: 8px;
        color: var(--dark-color);
        text-decoration: none;
        transition: all 0.2s;
        height: 48px;
        position: relative;
    }

    .sidebar-menu-link:hover {
        background-color: rgba(233, 252, 253, 0.5);
        color: var(--primary-color);
    }

    .sidebar-menu-link.active {
        background-color: var(--secondary-color);
        color: var(--primary-color);
        font-weight: 500;
    }

    .sidebar-menu-icon {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
        color: inherit;
    }

    .sidebar-menu-text {
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: opacity var(--transition-speed);
    }

    .sidebar-collapsed .sidebar-menu-text {
        opacity: 0;
        width: 0;
    }

    .sidebar-submenu {
        list-style: none;
        padding-left: 0;
        margin: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        width: 100%;
    }

    .sidebar-menu-item.open .sidebar-submenu {
        max-height: 1000px;
    }

    .sidebar-submenu-link {
        display: flex;
        padding: 8px 16px 8px 58px;
        align-items: center;
        color: var(--dark-color);
        text-decoration: none;
        transition: all 0.2s;
        border-radius: 8px;
    }

    .sidebar-submenu-link:hover {
        background-color: rgba(233, 252, 253, 0.5);
        color: var(--primary-color);
    }

    .sidebar-submenu-link.active {
        background-color: var(--secondary-color);
        color: var(--primary-color);
        font-weight: 500;
    }

    .sidebar-toggle-container {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 1002;
    }

    .sidebar-toggle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
    }

    .sidebar-toggle:hover {
        background-color: #0e6ba8;
        transform: scale(1.05);
    }

    .sidebar-toggle i {
        transition: transform 0.3s;
    }

    .sidebar-collapsed .sidebar-toggle i {
        transform: rotate(180deg);
    }

    .has-submenu::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 16px;
        transition: transform 0.3s;
    }

    .sidebar-menu-item.open .has-submenu::after {
        transform: rotate(180deg);
    }

    .sidebar-collapsed .has-submenu::after {
        display: none;
    }

    /* Make sure all submenus are closed when sidebar is collapsed */
    .sidebar-collapsed .sidebar-submenu {
        display: none !important;
    }

    /* Ensure the sidebar-menu-item doesn't show as open when collapsed */
    .sidebar-collapsed .sidebar-menu-item.open {
        max-height: 48px;
        overflow: hidden;
    }

    .tooltip {
        z-index: 1080;
    }

    .sidebar-collapsed .tooltip {
        display: block;
    }

    .sidebar:not(.sidebar-collapsed) .tooltip {
        display: none !important;
    }

    /* Main Content Styles */
    .main-content {
        margin-left: var(--sidebar-width);
        margin-top: var(--topbar-height);
        padding: 20px;
        transition: margin-left var(--transition-speed);
        min-height: calc(100vh - var(--topbar-height));
    }

    .main-content-collapsed {
        margin-left: var(--sidebar-collapsed-width);
    }

    .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 500;
        color: var(--dark-color);
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-visible {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .main-content-collapsed {
            margin-left: 0;
        }

        .mobile-toggle {
            display: block !important;
        }
    }

    .mobile-toggle {
        display: none;
        background: transparent;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: white;
    }

    /* Card Styles */
    .card {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 24px;
        border: none;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 16px 24px;
        font-weight: 500;
        color: rgba(0, 0, 0, 0.85);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body {
        padding: 24px;
    }

    /* Stats Card Styles */
    .stats-card {
        color: white;
        transition: transform var(--transition-speed);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-card .card-body {
        padding: 24px;
    }

    .stats-value {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .stats-label {
        font-size: 14px;
        opacity: 0.85;
    }

    .stats-icon {
        font-size: 24px;
        margin-bottom: 16px;
    }

    /* Gradient backgrounds for stats cards */
    .bg-primary {
        background: linear-gradient(135deg, #4dabf7 0%, #3b9cff 100%) !important;
    }

    .bg-success {
        background: linear-gradient(135deg, #69db7c 0%, #40c057 100%) !important;
    }

    .bg-warning {
        background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%) !important;
    }

    .bg-danger {
        background: linear-gradient(135deg, #ff8787 0%, #fa5252 100%) !important;
    }

    /* Filter form styles */
    .filter-form {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-form .form-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0;
    }

    .filter-form label {
        margin-bottom: 0;
        white-space: nowrap;
    }

    /* Chart container */
    .chart-container {
        position: relative;
        height: 400px;
    }

    /* Footer */
    .footer {
        margin-left: var(--sidebar-width);
        padding: 16px 20px;
        transition: margin-left var(--transition-speed);
        border-top: 1px solid #f0f0f0;
        background-color: white;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .footer.footer-collapsed {
        margin-left: var(--sidebar-collapsed-width);
    }

    @media (max-width: 768px) {
        .footer {
            margin-left: 0;
        }
    }

    .text-right {
        text-align: right !important;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .btn-info {
        color: #fff;
    }

    .pagination {
        justify-content: center;
    }

    /* Custom Styles */
    i.fas {
        margin-right: 5px;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        border-radius: 12px;
        text-transform: uppercase;
    }

    .badge-success {
        background-color: #28a745; /* Màu xanh lá */
        color: white;
    }

    .badge-danger {
        background-color: #dc3545; /* Màu đỏ */
        color: white;
    }

    .badge-warning {
        background-color: #ffc107; /* Màu vàng */
        color: black;
    }

    .badge-info {
        background-color: #17a2b8; /* Màu xanh dương */
        color: white;
    }

</style>
