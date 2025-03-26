<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                <i class="sidebar-menu-icon fas fa-tachometer-alt"></i>
                <span class="sidebar-menu-text">Dashboard</span>
            </a>
        </li>

        @php
            $isSalesActive = request()->routeIs('sales.*') || Request::is('returns*');
        @endphp
        <li class="sidebar-menu-item {{ $isSalesActive ? 'active open' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu {{ $isSalesActive ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Bán hàng">
                <i class="sidebar-menu-icon fas fa-shopping-cart"></i>
                <span class="sidebar-menu-text">Bán hàng</span>
            </a>
            <ul class="sidebar-submenu" style="{{ $isSalesActive && !session('sidebar_collapsed', false) ? 'display: block;' : 'display: none;' }}">
                <li>
                    <a href="{{ route('sales.index') }}" class="sidebar-submenu-link {{ request()->routeIs('sales.index') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Tạo đơn hàng">
                        <span>Tạo đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.invoices') }}" class="sidebar-submenu-link {{ request()->routeIs('sales.invoices') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Danh sách đơn hàng">
                        <span>Danh sách đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('returns.index') }}" class="sidebar-submenu-link {{ Request::is('returns*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Trả hàng">
                        <span>Trả hàng</span>
                    </a>
                </li>
            </ul>
        </li>

        @php
            $isProductActive = request()->routeIs('products.*') || request()->routeIs('product-categories.*');
        @endphp
        <li class="sidebar-menu-item {{ $isProductActive ? 'active open' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu {{ $isProductActive ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Sản phẩm">
                <i class="sidebar-menu-icon fas fa-box"></i>
                <span class="sidebar-menu-text">Sản phẩm</span>
            </a>
            <ul class="sidebar-submenu" style="{{ $isProductActive && !session('sidebar_collapsed', false) ? 'display: block;' : 'display: none;' }}">
                <li>
                    <a href="{{ route('products.index') }}" class="sidebar-submenu-link {{ request()->routeIs('products.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Danh sách sản phẩm">
                        <span>Danh sách sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product-categories.index') }}" class="sidebar-submenu-link {{ request()->routeIs('product-categories.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Nhóm sản phẩm">
                        <span>Nhóm sản phẩm</span>
                    </a>
                </li>
            </ul>
        </li>

        @php
            $isPurchaseActive = Request::is('imports*') || Request::is('suppliers*');
        @endphp
        <li class="sidebar-menu-item {{ $isPurchaseActive ? 'active open' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu {{ $isPurchaseActive ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Nhập hàng">
                <i class="sidebar-menu-icon fas fa-truck"></i>
                <span class="sidebar-menu-text">Nhập hàng</span>
            </a>
            <ul class="sidebar-submenu" style="{{ $isPurchaseActive && !session('sidebar_collapsed', false) ? 'display: block;' : 'display: none;' }}">
                <li>
                    <a href="{{ route('imports.create') }}" class="sidebar-submenu-link {{ request()->routeIs('imports.create') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Tạo phiếu nhập">
                        <span>Tạo phiếu nhập</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('imports.index') }}" class="sidebar-submenu-link {{ request()->routeIs('imports.index') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Danh sách phiếu nhập">
                        <span>Danh sách phiếu nhập</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="sidebar-submenu-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Nhà cung cấp">
                        <span>Nhà cung cấp</span>
                    </a>
                </li>
            </ul>
        </li>

        @php
            $isInventoryActive = request()->routeIs('inventory.*') || request()->routeIs('disposal.*');
        @endphp
        <li class="sidebar-menu-item {{ $isInventoryActive ? 'active open' : '' }}">
            <a href="#" class="sidebar-menu-link has-submenu {{ $isInventoryActive ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kho hàng">
                <i class="sidebar-menu-icon fas fa-warehouse"></i>
                <span class="sidebar-menu-text">Kho hàng</span>
            </a>
            <ul class="sidebar-submenu" style="{{ $isInventoryActive && !session('sidebar_collapsed', false) ? 'display: block;' : 'display: none;' }}">
                <li>
                    <a href="{{ route('inventory.index') }}" class="sidebar-submenu-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kiểm kê kho">
                        <span>Kiểm kê kho</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('disposal.index') }}" class="sidebar-submenu-link {{ request()->routeIs('disposal.index') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Danh sách phiếu xuất hủy">
                        <span>Danh sách phiếu xuất hủy</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('disposal.create') }}" class="sidebar-submenu-link {{ request()->routeIs('disposal.create') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Tạo phiếu xuất hủy">
                        <span>Tạo phiếu xuất hủy</span>
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
