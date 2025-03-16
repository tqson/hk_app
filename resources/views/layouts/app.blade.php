<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
<div class="ant-layout ant-layout-has-sider">
    <!-- Sidebar -->
    <div class="ant-layout-sider" style="width: 200px; min-width: 200px; max-width: 200px;">
        <div class="ant-sider-logo">
            {{ config('app.name', 'Laravel') }}
        </div>
        <ul class="ant-menu ant-menu-vertical ant-sider-menu">
            <li class="ant-menu-item {{ request()->is('dashboard') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="ant-menu-item {{ request()->is('users*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('users.index') }}">Users</a>
            </li>
            <li class="ant-menu-item {{ request()->is('products*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('products.index') }}">Products</a>
            </li>
            <li class="ant-menu-item {{ request()->is('categories*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('categories.index') }}">Categories</a>
            </li>
            <li class="ant-menu-item {{ request()->is('sales*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('sales.index') }}">Sales</a>
            </li>
            <li class="ant-menu-item {{ request()->is('purchases*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('purchases.index') }}">Purchases</a>
            </li>
            <li class="ant-menu-item {{ request()->is('suppliers*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('suppliers.index') }}">Suppliers</a>
            </li>
            <li class="ant-menu-item {{ request()->is('reports*') ? 'ant-menu-item-selected' : '' }}">
                <a href="{{ route('reports.index') }}">Reports</a>
            </li>
        </ul>
    </div>

    <div class="ant-layout">
        <!-- Header -->
        <div class="ant-layout-header">
            <div class="ant-header-right">
                <div class="ant-header-user-info">
                    <span class="ant-header-user-name">{{ Auth::user()->username ?? 'Guest' }}</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="ant-layout-content">
            <div class="ant-layout-content-wrapper">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <div class="ant-layout-footer">
            <div class="ant-footer-copyright">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </div>
        </div>
    </div>
</div>
</body>
</html>
