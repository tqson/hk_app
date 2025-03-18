<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="sidebar-menu-icon fas fa-tachometer-alt"></i>
                <span class="sidebar-menu-text">Dashboard</span>
            </a>
        </li>

        @php
            $isSalesActive = request()->routeIs('sales.*') || Request::is('returns*');
        @endphp
        <li class="sidebar-menu-item {{ $isSalesActive ? 'active open' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu {{ $isSalesActive ? 'active' : '' }}">
                <i class="sidebar-menu-icon fas fa-shopping-cart"></i>
                <span class="sidebar-menu-text">Bán hàng</span>
            </a>
            <ul class="sidebar-submenu" style="{{ $isSalesActive ? 'display: block;' : '' }}">
                <li>
                    <a href="{{ route('sales.index') }}" class="sidebar-submenu-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                        <span>Tạo đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.invoices') }}" class="sidebar-submenu-link {{ request()->routeIs('sales.invoices') ? 'active' : '' }}">
                        <span>Danh sách đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('returns.index') }}" class="sidebar-submenu-link {{ Request::is('returns*') ? 'active' : '' }}">
                        <span>Trả hàng</span>
                    </a>
                </li>
            </ul>
        </li>

        @php
            $isProductActive = request()->routeIs('products.*') || request()->routeIs('product-categories.*');
        @endphp
        <li class="sidebar-menu-item {{ $isProductActive ? 'active open' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu {{ $isProductActive ? 'active' : '' }}">
                <i class="sidebar-menu-icon fas fa-box"></i>
                <span class="sidebar-menu-text">Sản phẩm</span>
            </a>
            <ul class="sidebar-submenu" style="{{ $isProductActive ? 'display: block;' : '' }}">
                <li>
                    <a href="{{ route('products.index') }}" class="sidebar-submenu-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <span>Danh sách sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product-categories.index') }}" class="sidebar-submenu-link {{ request()->routeIs('product-categories.*') ? 'active' : '' }}">
                        <span>Danh mục loại sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-submenu-link">
                        <span>Nhập hàng</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-toggle-container">
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
</div>
