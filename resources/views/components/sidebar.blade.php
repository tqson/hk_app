<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="sidebar-menu-icon fas fa-tachometer-alt"></i>
                <span class="sidebar-menu-text">Dashboard</span>
            </a>
        </li>

        <li class="sidebar-menu-item">
            <a href="#" class="sidebar-menu-link has-submenu">
                <i class="sidebar-menu-icon fas fa-shopping-cart"></i>
                <span class="sidebar-menu-text">Bán hàng</span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('sales.index') }}" class="sidebar-submenu-link">
                        <span>Tạo đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-submenu-link">
                        <span>Danh sách đơn hàng</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="sidebar-menu-item {{ request()->routeIs('products.*') || request()->routeIs('product-categories.*') ? 'active' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu">
                <i class="sidebar-menu-icon fas fa-box"></i>
                <span class="sidebar-menu-text">Sản phẩm</span>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('products.index') }}" class="sidebar-submenu-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <span>Danh sách sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product-categories.index') }}" class="sidebar-submenu-link {{ request()->routeIs('product-categories.*') ? 'active' : '' }}">
                        <span>Danh mục</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-submenu-link">
                        <span>Nhập hàng</span>
                    </a>
                </li>
            </ul>
        </li>

{{--        <li class="sidebar-menu-item">--}}
{{--            <a href="#" class="sidebar-menu-link has-submenu">--}}
{{--                <i class="sidebar-menu-icon fas fa-chart-bar"></i>--}}
{{--                <span class="sidebar-menu-text">Báo cáo</span>--}}
{{--            </a>--}}
{{--            <ul class="sidebar-submenu">--}}
{{--                <li>--}}
{{--                    <a href="#" class="sidebar-submenu-link">--}}
{{--                        <span>Báo cáo doanh thu</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#" class="sidebar-submenu-link">--}}
{{--                        <span>Báo cáo tồn kho</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#" class="sidebar-submenu-link">--}}
{{--                        <span>Báo cáo lợi nhuận</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}

{{--        <li class="sidebar-menu-item">--}}
{{--            <a href="#" class="sidebar-menu-link has-submenu">--}}
{{--                <i class="sidebar-menu-icon fas fa-cog"></i>--}}
{{--                <span class="sidebar-menu-text">Cài đặt</span>--}}
{{--            </a>--}}
{{--            <ul class="sidebar-submenu">--}}
{{--                <li>--}}
{{--                    <a href="#" class="sidebar-submenu-link">--}}
{{--                        <span>Thông tin cửa hàng</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#" class="sidebar-submenu-link">--}}
{{--                        <span>Người dùng</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#" class="sidebar-submenu-link">--}}
{{--                        <span>Phân quyền</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}
    </ul>

    <div class="sidebar-toggle-container">
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
</div>
