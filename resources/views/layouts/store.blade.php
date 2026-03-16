<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Premium e-commerce store with the best products at amazing prices.">

    <title>{{ config('app.name', 'ShopVue') }} - @yield('title', 'Premium Online Store')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5a4bd1;
            --secondary: #00cec9;
            --accent: #fd79a8;
            --dark: #2d3436;
            --light: #f8f9fa;
            --gray: #636e72;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #e17055;
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background: #f0f2f5;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ── */
        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 1px 20px rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 1050;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }

        .nav-custom .nav-link {
            font-weight: 500;
            color: var(--gray);
            transition: color 0.2s;
            position: relative;
        }
        .nav-custom .nav-link:hover,
        .nav-custom .nav-link.active {
            color: var(--primary);
        }
        .nav-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }

        .cart-badge {
            background: var(--accent);
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 10px;
            position: absolute;
            top: -5px;
            right: -8px;
            font-weight: 700;
        }

        /* ── Buttons ── */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108,92,231,0.3);
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108,92,231,0.4);
            color: #fff;
        }

        .btn-secondary-custom {
            background: rgba(108,92,231,0.1);
            border: none;
            color: var(--primary);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-secondary-custom:hover {
            background: rgba(108,92,231,0.2);
            color: var(--primary-dark);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #e84393);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253,121,168,0.4);
            color: #fff;
        }

        /* ── Cards ── */
        .card-custom {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            background: #fff;
        }
        .card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        }

        .card-custom .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .card-custom:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-img-wrapper {
            overflow: hidden;
            position: relative;
        }

        .card-img-wrapper .badge-overlay {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
        }

        .product-price {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
        }

        /* ── Hero ── */
        .hero-section {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 50%, #00cec9 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .hero-section h1 {
            font-weight: 800;
            font-size: 3rem;
            color: #fff;
            line-height: 1.2;
        }
        .hero-section p {
            color: rgba(255,255,255,0.85);
            font-size: 1.15rem;
        }

        /* ── Category Pills ── */
        .category-pill {
            background: #fff;
            border: 2px solid #eee;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            color: var(--gray);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .category-pill:hover, .category-pill.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108,92,231,0.3);
        }

        /* ── Footer ── */
        .footer-custom {
            background: linear-gradient(135deg, #2d3436 0%, #000 100%);
            color: rgba(255,255,255,0.7);
            padding: 48px 0 24px;
            margin-top: auto;
        }
        .footer-custom h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .footer-custom a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-custom a:hover {
            color: var(--secondary);
        }

        /* ── Alerts ── */
        .alert-custom {
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-weight: 500;
        }

        /* ── Badges ── */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending { background: #ffeaa7; color: #d68910; }
        .status-processing { background: #74b9ff; color: #2c6fbb; }
        .status-shipped { background: #dfe6e9; color: #636e72; }
        .status-delivered { background: #55efc4; color: #00866e; }
        .status-cancelled { background: #fab1a0; color: #c0392b; }

        /* ── Tables ── */
        .table-custom {
            border-radius: 12px;
            overflow: hidden;
        }
        .table-custom thead th {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 14px 16px;
        }
        .table-custom tbody td {
            padding: 14px 16px;
            vertical-align: middle;
        }

        /* ── Form Styling ── */
        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108,92,231,0.15);
        }

        /* ── Placeholder product image ── */
        .placeholder-img {
            background: linear-gradient(135deg, #dfe6e9, #b2bec3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #636e72;
            font-size: 3rem;
            height: 220px;
        }

        /* ── Page header ── */
        .page-header {
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            padding: 40px 0;
            color: #fff;
            margin-bottom: 32px;
        }
        .page-header h2 {
            font-weight: 800;
            margin: 0;
        }

        /* ── Pagination ── */
        .pagination .page-link {
            border: none;
            color: var(--primary);
            font-weight: 600;
            border-radius: 10px;
            margin: 0 3px;
        }
        .pagination .page-item.active .page-link {
            background: var(--primary);
            color: #fff;
        }

        /* ── Section title ── */
        .section-title {
            font-weight: 800;
            font-size: 1.8rem;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2rem; }
            .hero-section { padding: 50px 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg nav-custom py-3">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="{{ route('home') }}">
                <i class="bi bi-bag-heart-fill me-2"></i>ShopVue
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">My Orders</a>
                    </li>
                    @endauth
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            @php
                                $cartCount = 0;
                                if(auth()->check()) {
                                    $cartObj = \App\Models\Cart::where('user_id', auth()->id())->first();
                                } else {
                                    $cartObj = \App\Models\Cart::where('session_id', session()->getId())->first();
                                }
                                if($cartObj) { $cartCount = $cartObj->items()->sum('quantity'); }
                            @endphp
                            @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    @guest
                    <li class="nav-item">
                        <a class="btn btn-secondary-custom btn-sm me-2" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary-custom btn-sm" href="{{ route('register') }}">Register</a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:12px">
                            <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
                            @if(Auth::user()->isAdmin())
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item py-2 text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
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
    </div>

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h6><i class="bi bi-bag-heart-fill me-2"></i>ShopVue</h6>
                    <p class="small">Your premium online shopping destination. Discover amazing products at unbeatable prices.</p>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6>Shop</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('products.index') }}">All Products</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}">Featured</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6>Account</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('cart.index') }}">Cart</a></li>
                        @auth
                        <li class="mb-2"><a href="{{ route('orders.index') }}">Orders</a></li>
                        <li class="mb-2"><a href="{{ route('profile.edit') }}">Profile</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6>Contact</h6>
                    <p class="small mb-1"><i class="bi bi-envelope me-2"></i>support@shopvue.com</p>
                    <p class="small mb-1"><i class="bi bi-telephone me-2"></i>+1 (555) 123-4567</p>
                    <div class="mt-3">
                        <a href="#" class="me-3 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1)">
            <p class="text-center small mb-0">&copy; {{ date('Y') }} ShopVue. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
