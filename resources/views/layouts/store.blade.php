<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Premium e-commerce store with the best products at amazing prices.">

    <title>{{ config('app.name', '7th June Computers') }} - @yield('title', 'Premium Online Store')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #3b82f6;
            --accent: #60a5fa;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f0f2f5;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Hide Pagination Prev/Next Arrows */
        .pagination .page-item:first-child,
        .pagination .page-item:last-child {
            display: none !important;
        }

        /* ── Navbar ── */
        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.5rem;
            color: #ffffff;
            text-decoration: none;
        }

        .nav-custom {
            background: #2563EB;
            backdrop-filter: blur(20px);
            box-shadow: 0 1px 20px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 0;
            z-index: 1050;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .nav-custom .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.2s;
            position: relative;
        }

        .nav-custom .nav-link:hover,
        .nav-custom .nav-link.active {
            color: #ffffff;
        }

        .nav-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #ffffff;
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
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: #fff;
        }

        .btn-secondary-custom {
            background: rgba(37, 99, 235, 0.1);
            border: none;
            color: var(--primary);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: rgba(37, 99, 235, 0.2);
            color: var(--primary-dark);
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-add-to-cart:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            color: #fff;
        }

        .btn-buy-now {
            background: linear-gradient(135deg, #059669, #047857);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-buy-now:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
            color: #fff;
        }

        .btn-in-cart {
            background: rgba(37, 99, 235, 0.1);
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 10px;
            cursor: default;
        }

        /* ── Cards ── */
        .card-custom {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        .card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
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
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #60a5fa 100%);
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
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .hero-section h1 {
            font-weight: 800;
            font-size: 3rem;
            color: #fff;
            line-height: 1.2;
        }

        .hero-section p {
            color: rgba(255, 255, 255, 0.85);
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

        .category-pill:hover,
        .category-pill.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        /* ── Footer ── */
        .footer-custom {
            background: #111827;
            color: rgba(255, 255, 255, 0.7);
            padding: 48px 0 24px;
            margin-top: auto;
        }

        .footer-custom h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .footer-custom a {
            color: rgba(255, 255, 255, 0.6);
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

        .status-pending {
            background: #ffeaa7;
            color: #d68910;
        }

        .status-processing {
            background: #74b9ff;
            color: #2c6fbb;
        }

        .status-shipped {
            background: #dfe6e9;
            color: #636e72;
        }

        .status-delivered {
            background: #55efc4;
            color: #00866e;
        }

        .status-cancelled {
            background: #fab1a0;
            color: #c0392b;
        }

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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
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
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            padding: 18px 0;
            color: #fff;
            margin-bottom: 24px;
        }

        .page-header h2 {
            font-weight: 800;
            margin: 0;
            font-size: 1.4rem;
        }

        .page-header p {
            font-size: 0.85rem;
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
            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section {
                padding: 50px 0;
            }
        }

        /* ── Dark Mode ── */
        body.dark-mode {
            background: #121212;
            color: #e0e0e0;
            --bs-body-color: #e0e0e0;
            --bs-heading-color: #ffffff;
        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6,
        body.dark-mode .h1,
        body.dark-mode .h2,
        body.dark-mode .h3,
        body.dark-mode .h4,
        body.dark-mode .h5,
        body.dark-mode .h6 {
            color: #ffffff !important;
        }

        body.dark-mode .text-dark {
            color: #f8f9fa !important;
        }

        body.dark-mode .text-muted {
            color: #adb5bd !important;
        }

        body.dark-mode a.text-dark:hover,
        body.dark-mode a.text-dark:focus {
            color: #e9ecef !important;
        }

        body.dark-mode .bg-white {
            background-color: #121212 !important;
        }

        body.dark-mode .bg-light {
            background-color: #1e1e1e !important;
        }

        body.dark-mode .nav-custom {
            background: rgba(18, 18, 18, 0.95);
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .nav-custom .nav-link {
            color: #b2bec3;
        }

        body.dark-mode .nav-custom .nav-link:hover,
        body.dark-mode .nav-custom .nav-link.active {
            color: var(--primary);
        }

        body.dark-mode .card-custom {
            background: #1e1e1e;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
            color: #e0e0e0;
        }

        body.dark-mode .card-custom .card-title,
        body.dark-mode .card-custom .card-text {
            color: #e0e0e0;
        }

        body.dark-mode .category-pill {
            background: #1e1e1e;
            border-color: #333;
            color: #b2bec3;
        }

        body.dark-mode .category-pill:hover,
        body.dark-mode .category-pill.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        body.dark-mode .form-control-custom,
        body.dark-mode .form-control {
            background: #2d2d2d;
            border-color: #444;
            color: #e0e0e0;
        }

        body.dark-mode .form-control-custom:focus,
        body.dark-mode .form-control:focus {
            background: #333;
            color: #fff;
            border-color: var(--primary);
        }

        body.dark-mode .dropdown-menu {
            background-color: #1e1e1e;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .dropdown-item {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #2d2d2d;
            color: var(--primary);
        }

        body.dark-mode .dropdown-divider {
            border-top-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .table,
        body.dark-mode .table-custom {
            color: #e0e0e0;
        }

        body.dark-mode .table-custom td {
            border-bottom-color: #333;
            background-color: transparent;
        }

        body.dark-mode .page-header {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
        }

        body.dark-mode .placeholder-img {
            background: linear-gradient(135deg, #2d3436, #636e72);
        }

        body.dark-mode .footer-custom {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        body.dark-mode .product-tabs .nav-link:hover {
            color: #ffffff !important;
        }

        body.dark-mode .product-tabs .nav-link.active {
            color: #ffffff !important;
            border-bottom-color: #ffffff !important;
        }

        /* ── Discount Countdown ── */
        .discount-countdown {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            animation: countdown-pulse 2s ease-in-out infinite;
        }

        .discount-countdown i {
            font-size: 0.7rem;
        }

        .discount-countdown-lg {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            padding: 12px 20px;
            border-radius: 12px;
            margin-top: 8px;
            animation: countdown-pulse 2s ease-in-out infinite;
        }

        .discount-countdown-lg .cd-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #dc2626;
        }

        .discount-countdown-lg .cd-timer {
            display: flex;
            gap: 6px;
        }

        .cd-unit {
            text-align: center;
            background: #fff;
            border-radius: 8px;
            padding: 4px 8px;
            min-width: 42px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .cd-unit .cd-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            color: #dc2626;
            line-height: 1.2;
        }

        .cd-unit .cd-text {
            display: block;
            font-size: 0.55rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @keyframes countdown-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.85;
            }
        }

        body.dark-mode .discount-countdown-lg {
            background: linear-gradient(135deg, #2d1a1a, #3d1f1f);
            border-color: #5c2626;
        }

        body.dark-mode .cd-unit {
            background: #1e1e1e;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        /* ── Professional Toast ── */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-item {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            max-width: 420px;
            padding: 14px 20px;
            border-radius: 14px;
            color: #fff;
            font-weight: 600;
            font-size: 0.88rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            backdrop-filter: blur(12px);
            animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .toast-item.toast-success {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .toast-item.toast-error {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .toast-item .toast-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .toast-item .toast-msg {
            flex: 1;
            line-height: 1.4;
        }

        .toast-item .toast-close {
            background: none;
            border: none;
            color: rgba(255,255,255,0.7);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
            transition: color 0.2s;
        }

        .toast-item .toast-close:hover {
            color: #fff;
        }

        .toast-item .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255,255,255,0.4);
            border-radius: 0 0 14px 14px;
            animation: toastProgress 4s linear forwards;
        }

        .toast-item.toast-exit {
            animation: toastSlideOut 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes toastSlideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes toastSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* ── Mobile Off-canvas Sidebar ── */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 300px;
            height: 100vh;
            z-index: 9998;
            transition: left 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow-y: auto;
        }

        .mobile-sidebar.open {
            left: 0;
        }

        .mobile-sidebar-inner {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            height: 100%;
            padding: 28px 24px;
            border-right: 1px solid rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
        }

        body.dark-mode .mobile-sidebar-inner {
            background: rgba(18, 18, 18, 0.95);
            border-right-color: rgba(255,255,255,0.08);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.45);
            z-index: 9997;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        body.dark-mode .sidebar-top-row {
            border-bottom-color: rgba(255,255,255,0.1);
        }

        .sidebar-top-row .sidebar-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            background: rgba(37, 99, 235, 0.08);
            color: var(--primary);
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }

        .sidebar-top-row .sidebar-icon-btn:hover {
            background: rgba(37, 99, 235, 0.15);
        }

        body.dark-mode .sidebar-top-row .sidebar-icon-btn {
            background: rgba(96, 165, 250, 0.12);
            color: var(--accent);
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .sidebar-nav-item:hover {
            background: rgba(37, 99, 235, 0.06);
            color: var(--primary);
        }

        .sidebar-nav-item i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            color: var(--gray);
        }

        .sidebar-nav-item:hover i {
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav-item {
            color: #e0e0e0;
        }

        body.dark-mode .sidebar-nav-item:hover {
            background: rgba(96, 165, 250, 0.1);
            color: var(--accent);
        }

        body.dark-mode .sidebar-nav-item i {
            color: #adb5bd;
        }

        .sidebar-nav-item.danger {
            color: var(--danger);
        }

        .sidebar-nav-item.danger i {
            color: var(--danger);
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(0,0,0,0.06);
            margin: 12px 0;
        }

        body.dark-mode .sidebar-divider {
            background: rgba(255,255,255,0.08);
        }

        /* ── FAB Search Button ── */
        .fab-search {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            font-size: 1.3rem;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.4);
            z-index: 1040;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .fab-search:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 32px rgba(37, 99, 235, 0.5);
        }

        @media (max-width: 991.98px) {
            .fab-search { display: flex; }
        }

        /* ── Fullscreen Search Modal ── */
        .search-fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            z-index: 9999;
            display: none;
            flex-direction: column;
            padding: 0;
        }

        .search-fullscreen.open {
            display: flex;
            animation: fadeInSearch 0.25s ease;
        }

        body.dark-mode .search-fullscreen {
            background: rgba(18, 18, 18, 0.98);
        }

        @keyframes fadeInSearch {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-fs-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }

        body.dark-mode .search-fs-header {
            border-bottom-color: rgba(255,255,255,0.08);
        }

        .search-fs-header input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1.1rem;
            font-weight: 500;
            outline: none;
            color: var(--dark);
        }

        body.dark-mode .search-fs-header input {
            color: #e0e0e0;
        }

        .search-fs-header input::placeholder {
            color: #adb5bd;
        }

        .search-fs-close {
            width: 36px;
            height: 36px;
            border: none;
            background: rgba(0,0,0,0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            color: var(--gray);
            transition: all 0.2s;
        }

        .search-fs-close:hover {
            background: rgba(0,0,0,0.1);
        }

        body.dark-mode .search-fs-close {
            background: rgba(255,255,255,0.08);
            color: #e0e0e0;
        }

        .search-fs-results {
            flex: 1;
            overflow-y: auto;
            padding: 12px 20px;
        }

        .search-suggestion-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.2s;
        }

        .search-suggestion-item:hover {
            background: rgba(37, 99, 235, 0.05);
            color: var(--primary);
        }

        body.dark-mode .search-suggestion-item {
            color: #e0e0e0;
        }

        body.dark-mode .search-suggestion-item:hover {
            background: rgba(96, 165, 250, 0.08);
        }

        .search-suggestion-item img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            background: #f3f4f6;
        }

        .search-suggestion-item .sug-info h6 {
            font-size: 0.88rem;
            font-weight: 600;
            margin: 0;
        }

        .search-suggestion-item .sug-info small {
            color: var(--gray);
            font-size: 0.75rem;
        }

        .search-fs-hint {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .search-fs-hint i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
            opacity: 0.4;
        }
    </style>
    @stack('styles')
</head>

<body>
    @php
        $cartCount = 0;
        if (auth()->check()) {
            $cartObj = \App\Models\Cart::where('user_id', auth()->id())->first();
        } else {
            $cartObj = \App\Models\Cart::where('session_id', session()->getId())->first();
        }
        if ($cartObj) {
            $cartCount = $cartObj->items()->sum('quantity');
        }
    @endphp

    <nav class="navbar nav-custom py-3">
        <div class="container-fluid px-4 px-lg-5 d-flex align-items-center justify-content-between">

            <!-- Left: Hamburger (mobile) + Logo -->
            <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: max-content;">
                <!-- Mobile hamburger -->
                <button class="d-lg-none border-0 bg-transparent text-white p-1" id="sidebarToggle" aria-label="Open menu">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
                <a class="navbar-brand navbar-brand-custom d-flex align-items-center m-0" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="7th June Computers Logo" height="35" class="me-2"
                        style="object-fit: contain;">
                    <span style="font-size: 1.2rem;">7th June Computers</span>
                </a>
            </div>

            <!-- Center: Search Bar (desktop only) -->
            <div class="d-none d-lg-flex justify-content-center mx-3" style="flex: 2;">
                <form action="{{ route('products.index') }}" method="GET" class="position-relative w-60 desktop-search-form"
                    style="max-width: 550px;">
                    <i class="bi bi-search position-absolute text-muted"
                        style="left: 15px; top: 50%; transform: translateY(-50%); padding-left: 5px;"></i>
                    <input type="text" name="search" class="form-control ps-5 py-2 border-0" placeholder="Search products..." minlength="3"
                        style="background:#f3f4f6; border-radius: 50px; padding-right: 110px; color:#333; font-size: 0.95rem;">
                    <button type="submit" class="btn btn-primary position-absolute"
                        style="top: 3px; right: 3px; bottom: 3px; padding-left:15px; padding-right:15px; border-radius: 50px; font-weight:600; font-size: 0.9rem;">Search</button>
                </form>
            </div>

            <!-- Right: Mobile cart + Desktop icons -->
            <div class="d-flex align-items-center gap-2" style="flex: 1; justify-content: flex-end;">
                <!-- Mobile cart icon (visible on mobile only) -->
                <a class="d-lg-none nav-link position-relative text-white bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                    style="width:36px;height:36px;" href="{{ route('cart.index') }}">
                    <i class="bi bi-cart3" style="font-size: 0.95rem;"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle border border-white"
                            style="font-size: 0.6rem; transform: translate(-30%, -30%) !important;">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- Desktop icons -->
                <ul class="navbar-nav align-items-center flex-row gap-2 d-none d-lg-flex">
                    <li class="nav-item">
                        <a class="nav-link position-relative text-white bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                            style="width:36px;height:36px;" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3" style="font-size: 0.95rem;"></i>
                            @if($cartCount > 0)
                                <span class="cart-badge badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle border border-white"
                                    style="font-size: 0.6rem; transform: translate(-30%, -30%) !important;">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    @guest
                        <li class="nav-item dropdown">
                            <a class="nav-link text-white bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center dropdown-toggle"
                                href="#" role="button" data-bs-toggle="dropdown" style="width:36px;height:36px;">
                                <i class="bi bi-person" style="font-size: 0.95rem;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:12px">
                                <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                                <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('register') }}"><i class="bi bi-person-plus me-2"></i>Register</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link text-white bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center dropdown-toggle"
                                href="#" role="button" data-bs-toggle="dropdown" style="width:36px;height:36px;" title="{{ Auth::user()->name }}">
                                <i class="bi bi-person-check-fill" style="font-size: 0.95rem;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius:12px">
                                <li class="px-3 py-2 text-muted small fw-bold text-uppercase border-bottom mb-1">{{ Auth::user()->name }}</li>
                                <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2 text-primary"></i>Profile</a></li>
                                <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('orders.index') }}"><i class="bi bi-box-seam me-2 text-primary"></i>My Orders</a></li>
                                @if(Auth::user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 fw-semibold text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">@csrf
                                        <button class="dropdown-item py-2 fw-bold text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                    <li class="nav-item ms-1">
                        <button id="theme-toggle" class="nav-link text-white bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center border-0"
                            style="width:36px;height:36px;" aria-label="Toggle Dark Mode">
                            <i class="bi bi-moon-fill" id="theme-icon" style="font-size: 0.85rem;"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Off-canvas Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="mobile-sidebar-inner">
            <!-- Close button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="28" style="object-fit:contain;" class="me-2">
                    <span class="fw-bold" style="color: var(--dark); font-size: 0.95rem;">7th June</span>
                </a>
                <button class="border-0 bg-transparent" id="sidebarClose" style="font-size:1.3rem; color: var(--gray);"><i class="bi bi-x-lg"></i></button>
            </div>

            <!-- Cart + Theme row -->
            <div class="sidebar-top-row">
                <a href="{{ route('cart.index') }}" class="sidebar-icon-btn position-relative">
                    <i class="bi bi-cart3"></i>
                    @if($cartCount > 0)
                        <span class="badge rounded-pill bg-danger position-absolute" style="font-size:0.55rem; top:-4px; right:-4px; padding:2px 5px;">{{ $cartCount }}</span>
                    @endif
                </a>
                <button class="sidebar-icon-btn" id="theme-toggle-mobile" aria-label="Toggle Dark Mode">
                    <i class="bi bi-moon-fill" id="theme-icon-mobile" style="font-size: 0.95rem;"></i>
                </button>
            </div>

            <!-- Nav items -->
            <nav class="flex-grow-1">
                @auth
                    <a href="{{ route('profile.edit') }}" class="sidebar-nav-item"><i class="bi bi-person"></i>Profile</a>
                    <a href="{{ route('orders.index') }}" class="sidebar-nav-item"><i class="bi bi-box-seam"></i>My Orders</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item"><i class="bi bi-speedometer2"></i>Admin Panel</a>
                    @endif
                    <div class="sidebar-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-nav-item danger w-100 border-0 bg-transparent text-start"><i class="bi bi-box-arrow-right"></i>Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="sidebar-nav-item"><i class="bi bi-box-arrow-in-right"></i>Login</a>
                    <a href="{{ route('register') }}" class="sidebar-nav-item"><i class="bi bi-person-plus"></i>Register</a>
                @endauth
            </nav>

            <div class="mt-auto pt-3" style="border-top: 1px solid rgba(0,0,0,0.06);">
                <small class="text-muted">&copy; {{ date('Y') }} 7th June Computers</small>
            </div>
        </div>
    </div>

    <!-- FAB Search (mobile only) -->
    <button class="fab-search" id="fabSearch" aria-label="Search"><i class="bi bi-search"></i></button>

    <!-- Fullscreen Search Modal (mobile) -->
    <div class="search-fullscreen" id="searchFullscreen">
        <div class="search-fs-header">
            <i class="bi bi-search" style="font-size:1.1rem; color: var(--gray);"></i>
            <input type="text" id="searchFsInput" placeholder="Search products..." autocomplete="off">
            <button class="search-fs-close" id="searchFsClose"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="search-fs-results" id="searchFsResults">
            <div class="search-fs-hint">
                <i class="bi bi-search"></i>
                <p>Type at least 3 characters to search</p>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast-item toast-success">
                <i class="bi bi-check-circle-fill toast-icon"></i>
                <span class="toast-msg">{{ session('success') }}</span>
                <button class="toast-close" onclick="this.parentElement.classList.add('toast-exit');setTimeout(()=>this.parentElement.remove(),350)"><i class="bi bi-x"></i></button>
                <div class="toast-progress"></div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-item toast-error">
                <i class="bi bi-exclamation-triangle-fill toast-icon"></i>
                <span class="toast-msg">{{ session('error') }}</span>
                <button class="toast-close" onclick="this.parentElement.classList.add('toast-exit');setTimeout(()=>this.parentElement.remove(),350)"><i class="bi bi-x"></i></button>
                <div class="toast-progress"></div>
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
                    <h6 class="align-items-center d-flex">
                        <img src="{{ asset('images/logo.png') }}" alt="7th June Computers Logo" height="30" class="me-2"
                            style="object-fit: contain;">
                        7th June Computers
                    </h6>
                    <p class="small">Your premium online shopping destination. Discover amazing products at unbeatable
                        prices.</p>
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
                    <p class="small mb-1"><i class="bi bi-envelope me-2"></i>june7thcomputers@gmail.com</p>
                    <p class="small mb-1"><i class="bi bi-telephone me-2"></i>+1 (555) 123-4567</p>
                    <div class="mt-3">
                        <a href="https://web.facebook.com/7thJuneComputers" target="_blank" rel="noopener noreferrer"
                            class="me-3 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1)">
            <p class="text-center small mb-0">&copy; {{ date('Y') }} 7th June Computers. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;

            // ── Theme Toggle (shared logic) ──
            function applyTheme(dark) {
                if (dark) {
                    body.classList.add('dark-mode');
                    document.querySelectorAll('.theme-icon-el').forEach(i => { i.classList.replace('bi-moon-fill','bi-sun-fill'); });
                } else {
                    body.classList.remove('dark-mode');
                    document.querySelectorAll('.theme-icon-el').forEach(i => { i.classList.replace('bi-sun-fill','bi-moon-fill'); });
                }
            }
            // Tag all theme icons
            document.querySelectorAll('#theme-icon, #theme-icon-mobile').forEach(el => el.classList.add('theme-icon-el'));
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) applyTheme(true);

            function toggleTheme() {
                const isDark = !body.classList.contains('dark-mode');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                applyTheme(isDark);
            }
            document.getElementById('theme-toggle')?.addEventListener('click', toggleTheme);
            document.getElementById('theme-toggle-mobile')?.addEventListener('click', toggleTheme);

            // ── Mobile Sidebar ──
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            function openSidebar() { sidebar?.classList.add('open'); overlay?.classList.add('active'); }
            function closeSidebar() { sidebar?.classList.remove('open'); overlay?.classList.remove('active'); }
            document.getElementById('sidebarToggle')?.addEventListener('click', openSidebar);
            document.getElementById('sidebarClose')?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            // ── FAB Fullscreen Search ──
            const fabBtn = document.getElementById('fabSearch');
            const searchFs = document.getElementById('searchFullscreen');
            const searchInput = document.getElementById('searchFsInput');
            const searchResults = document.getElementById('searchFsResults');
            let searchTimer = null;

            fabBtn?.addEventListener('click', () => {
                searchFs?.classList.add('open');
                setTimeout(() => searchInput?.focus(), 200);
            });
            document.getElementById('searchFsClose')?.addEventListener('click', () => {
                searchFs?.classList.remove('open');
                searchInput.value = '';
                searchResults.innerHTML = '<div class="search-fs-hint"><i class="bi bi-search"></i><p>Type at least 3 characters to search</p></div>';
            });

            searchInput?.addEventListener('input', () => {
                clearTimeout(searchTimer);
                const q = searchInput.value.trim();
                if (q.length < 3) {
                    searchResults.innerHTML = '<div class="search-fs-hint"><i class="bi bi-search"></i><p>Type at least 3 characters to search</p></div>';
                    return;
                }
                searchResults.innerHTML = '<div class="search-fs-hint"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><p class="mt-2">Searching...</p></div>';
                searchTimer = setTimeout(async () => {
                    try {
                        const resp = await fetch(`{{ route('products.index') }}?search=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } });
                        if (!resp.ok) throw new Error();
                        const html = await resp.text();
                        // Parse product links from HTML response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const cards = doc.querySelectorAll('.product-clickable-card');
                        if (cards.length === 0) {
                            searchResults.innerHTML = '<div class="search-fs-hint"><i class="bi bi-emoji-frown"></i><p>No products found</p></div>';
                            return;
                        }
                        let resultsHtml = '';
                        cards.forEach((card, i) => {
                            if (i >= 8) return;
                            const url = card.dataset.productUrl;
                            const img = card.querySelector('img');
                            const title = card.querySelector('h6');
                            const price = card.querySelector('.fw-bold[style*="color: var(--primary)"]');
                            resultsHtml += `<a href="${url}" class="search-suggestion-item">
                                ${img ? `<img src="${img.src}" alt="">` : '<div style="width:44px;height:44px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;"><i class="bi bi-box-seam text-muted"></i></div>'}
                                <div class="sug-info">
                                    <h6>${title ? title.textContent.trim() : 'Product'}</h6>
                                    <small>${price ? price.textContent.trim() : ''}</small>
                                </div>
                            </a>`;
                        });
                        searchResults.innerHTML = resultsHtml;
                    } catch (e) {
                        searchResults.innerHTML = '<div class="search-fs-hint"><i class="bi bi-wifi-off"></i><p>Search failed. Try again.</p></div>';
                    }
                }, 400);
            });

            searchInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const q = searchInput.value.trim();
                    if (q.length >= 3) window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(q)}`;
                }
            });

            // ── Desktop Search Validation (min 3 chars) ──
            document.querySelectorAll('.desktop-search-form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    const input = form.querySelector('input[name="search"]');
                    if (input && input.value.trim().length > 0 && input.value.trim().length < 3) {
                        e.preventDefault();
                        showToast('Please enter at least 3 characters to search', 'error');
                    }
                });
            });

            // ── Hero carousel fallback ──
            const heroCarousel = document.getElementById('heroCarousel');
            if (heroCarousel && window.bootstrap && bootstrap.Carousel) {
                new bootstrap.Carousel(heroCarousel, { interval: 5000, ride: 'carousel', pause: 'hover', wrap: true, touch: true });
            }

            // ── Clickable product cards ──
            document.querySelectorAll('.product-clickable-card').forEach(card => {
                card.style.cursor = 'pointer';
                card.addEventListener('click', (event) => {
                    if (event.target.closest('button') || event.target.closest('a') || event.target.closest('form')) return;
                    const href = card.dataset.productUrl;
                    if (href) window.location.href = href;
                });
            });

            // ── AJAX add-to-cart and buy-now forms ──
            document.querySelectorAll('form.ajax-cart-form').forEach(form => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) { submitBtn.disabled = true; submitBtn.classList.add('opacity-75'); }
                    try {
                        const response = await fetch(form.action, {
                            method: (form.method || 'POST').toUpperCase(),
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                            body: new FormData(form),
                        });
                        const data = await response.json();
                        const status = response.ok ? 'success' : 'error';
                        showToast(data.message || (status === 'success' ? 'Done' : 'Something went wrong'), status);
                        if (status === 'success' && data.redirect) { window.location.href = data.redirect; return; }
                        if (status === 'success') {
                            document.querySelectorAll('.cart-badge').forEach(badge => {
                                const current = Number(badge.textContent || '0');
                                badge.textContent = String(current + 1);
                                badge.style.display = 'inline-block';
                            });
                            // Toggle button to "In Cart" if on product page
                            const addBtn = form.querySelector('.btn-add-to-cart');
                            if (addBtn) {
                                addBtn.outerHTML = '<span class="btn btn-in-cart"><i class="bi bi-check2 me-1"></i>In Cart</span>';
                            }
                        }
                    } catch (error) {
                        showToast('Unable to update cart. Please try again.', 'error');
                    } finally {
                        if (submitBtn) { submitBtn.disabled = false; submitBtn.classList.remove('opacity-75'); }
                    }
                });
            });

            // ── Professional Toast Function ──
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast-item toast-${type === 'success' ? 'success' : 'error'}`;
                toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} toast-icon"></i>` +
                    `<span class="toast-msg">${message}</span>` +
                    `<button class="toast-close" onclick="this.parentElement.classList.add('toast-exit');setTimeout(()=>this.parentElement.remove(),350)"><i class="bi bi-x"></i></button>` +
                    `<div class="toast-progress"></div>`;
                container.appendChild(toast);
                setTimeout(() => { toast.classList.add('toast-exit'); setTimeout(() => toast.remove(), 350); }, 4000);
            };

            // Auto-dismiss server toasts
            document.querySelectorAll('#toastContainer .toast-item').forEach(t => {
                setTimeout(() => { t.classList.add('toast-exit'); setTimeout(() => t.remove(), 350); }, 4000);
            });
        });
    </script>
    <script>
        // ── Discount Countdown Timer Engine ──
        (function () {
            function updateCountdowns() {
                document.querySelectorAll('[data-countdown-end]').forEach(el => {
                    const endTime = new Date(el.dataset.countdownEnd).getTime();
                    const now = Date.now();
                    const diff = endTime - now;

                    if (diff <= 0) {
                        el.innerHTML = '<i class="bi bi-x-circle me-1"></i>Sale ended';
                        el.style.opacity = '0.6';
                        return;
                    }

                    const days = Math.floor(diff / 86400000);
                    const hours = Math.floor((diff % 86400000) / 3600000);
                    const mins = Math.floor((diff % 3600000) / 60000);
                    const secs = Math.floor((diff % 60000) / 1000);

                    // Check if it's the large format
                    if (el.classList.contains('discount-countdown-lg')) {
                        el.querySelector('.cd-days').textContent = String(days).padStart(2, '0');
                        el.querySelector('.cd-hours').textContent = String(hours).padStart(2, '0');
                        el.querySelector('.cd-mins').textContent = String(mins).padStart(2, '0');
                        el.querySelector('.cd-secs').textContent = String(secs).padStart(2, '0');
                    } else {
                        // Compact format
                        let text = '';
                        if (days > 0) text += days + 'd ';
                        text += String(hours).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                        const icon = el.querySelector('i');
                        const span = el.querySelector('.cd-compact-text');
                        if (span) span.textContent = text;
                    }
                });
            }
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        })();
    </script>
    @stack('scripts')
</body>

</html>