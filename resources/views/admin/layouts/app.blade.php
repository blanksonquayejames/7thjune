<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'ShopVue') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #6c5ce7;
            --primary-dark: #5a4bd1;
            --secondary: #00cec9;
            --dark: #2d3436;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #2d3436 0%, #000 100%);
            color: #fff;
            padding-top: 0;
            z-index: 1040;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 20px 24px;
            font-weight: 800;
            font-size: 1.3rem;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand i { color: var(--primary); }
        .sidebar-nav { padding: 16px 12px; }
        .sidebar-nav .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35);
            padding: 16px 16px 8px;
            font-weight: 700;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 2px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .sidebar-link.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(108,92,231,0.4);
        }
        .sidebar-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1rem;
        }

        /* Main Content */
        .admin-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .admin-topbar {
            background: #fff;
            padding: 16px 32px;
            box-shadow: 0 1px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .admin-main { padding: 32px; }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }
        .stat-card:hover { transform: translateY(-4px); }
        .stat-card .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
        }

        .admin-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            background: #fff;
        }

        /* Table */
        .admin-table thead th {
            background: #f8f9fa;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #636e72;
            border: none;
            padding: 14px 16px;
        }
        .admin-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-color: #f1f3f5;
        }

        /* Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending { background: #ffeaa7; color: #d68910; }
        .status-processing { background: #74b9ff; color: #2c6fbb; }
        .status-shipped { background: #dfe6e9; color: #636e72; }
        .status-delivered { background: #55efc4; color: #00866e; }
        .status-cancelled { background: #fab1a0; color: #c0392b; }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108,92,231,0.3);
            color: #fff;
        }

        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px 14px;
            transition: all 0.3s;
        }
        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,92,231,0.12);
        }

        .alert-custom {
            border: none;
            border-radius: 12px;
        }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="bi bi-bag-heart-fill me-2"></i>ShopVue
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-label">Manage</div>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categories
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Orders
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>

            <div class="nav-label">Store</div>
            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="bi bi-shop"></i> View Store
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Content -->
    <div class="admin-content">
        <div class="admin-topbar">
            <div>
                <button class="btn btn-light d-lg-none me-3" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <span class="fw-bold">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ Auth::user()->name }}</span>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:0.8rem;font-weight:700">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="admin-main">
            @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
