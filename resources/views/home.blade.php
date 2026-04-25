@extends('layouts.store')
@section('title', 'Welcome')

@push('styles')
    <style>
        /* ── Hero Carousel ── */
        .hero-carousel {
            background: #DBEAFE;
            overflow: hidden;
        }

        .hero-carousel .carousel-item {
            transition: transform 0.8s ease-in-out;
        }

        .hero-slide {
            position: relative;
            min-height: 480px;
            overflow: hidden;
        }

        .hero-slide-image {
            position: absolute;
            top: 0;
            right: 0;
            width: 55%;
            height: 100%;
            object-fit: cover;
        }

        .hero-slide-content {
            position: relative;
            z-index: 2;
            padding: 80px 0;
        }

        .hero-slide h1 {
            font-weight: 900;
            font-size: 3.2rem;
            line-height: 1.15;
            color: var(--dark);
        }

        .hero-slide p {
            font-size: 1.05rem;
            color: var(--gray);
            max-width: 400px;
        }

        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .hero-carousel .carousel-control-prev {
            left: 16px;
        }

        .hero-carousel .carousel-control-next {
            right: 16px;
        }

        .hero-carousel .carousel-control-prev-icon,
        .hero-carousel .carousel-control-next-icon {
            filter: invert(1) grayscale(100) brightness(0);
            width: 16px;
            height: 16px;
        }

        .hero-carousel .carousel-indicators {
            bottom: 20px;
        }

        .hero-carousel .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #aaa;
            border: none;
            opacity: 0.5;
            margin: 0 4px;
        }

        .hero-carousel .carousel-indicators .active {
            background: var(--dark);
            opacity: 1;
        }

        .btn-hero-primary {
            background: #2563eb;
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 28px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .btn-hero-primary:hover {
            background: #1d4ed8;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
        }

        .btn-hero-outline {
            background: #fff;
            color: var(--dark);
            border: 2px solid #ddd;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 28px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .btn-hero-outline:hover {
            border-color: var(--dark);
            color: var(--dark);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .hero-slide {
                min-height: 350px;
            }

            .hero-slide h1 {
                font-size: 2rem;
            }

            .hero-slide-image {
                width: 45%;
                opacity: 0.3;
            }

            .hero-slide-content {
                padding: 40px 0;
            }
        }

        /* ── Category Icons ── */
        .category-icon-item {
            text-align: center;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
            display: block;
        }

        .category-icon-item:hover {
            transform: translateY(-8px);
            color: var(--primary);
        }

        .category-icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 2.2rem;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .category-icon-item:hover .category-icon-circle {
            background: rgba(108, 92, 231, 0.08);
            border-color: var(--primary);
            color: var(--primary);
        }

        .category-icon-item h6 {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .category-icon-item small {
            font-size: 0.78rem;
            color: var(--gray);
        }

        /* ── Tabs ── */
        .product-tabs {
            border: none;
            justify-content: center;
            gap: 8px;
            margin-bottom: 32px;
        }

        .product-tabs .nav-link {
            border: none;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray);
            padding: 8px 20px;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .product-tabs .nav-link:hover {
            color: var(--dark);
        }

        .product-tabs .nav-link.active {
            color: var(--dark);
            border-bottom-color: var(--dark);
            background: transparent;
        }

        /* ── Product Badges ── */
        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 3;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .badge-hot {
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        .badge-sold-out {
            background: #111;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        .badge-new {
            background: #10b981;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        /* ── Section Heading ── */
        .section-heading {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-heading .sub-label {
            color: #2563eb;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }

        .section-heading h2 {
            font-weight: 800;
            font-size: 2.2rem;
        }

        .section-heading p {
            color: var(--gray);
            max-width: 500px;
            margin: 8px auto 0;
        }

        /* ── Star Rating ── */
        .star-rating i {
            color: #facc15;
            font-size: 0.8rem;
        }

        .star-rating i.empty {
            color: #d1d5db;
        }
    </style>
@endpush

@section('content')
    <!-- ═══════════ HERO BANNER ═══════════ -->
    <div class="hero-section text-center text-lg-start position-relative"
        style="overflow: hidden; padding-top: 15vh; padding-bottom: 20vh; min-height: 600px;">

        <!-- Full Bleed Animated Background -->
        <style>
            .hero-fade-carousel,
            .hero-fade-carousel .carousel-inner,
            .hero-fade-carousel .carousel-item {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
            }

            .hero-fade-carousel .carousel-item {
                transition: opacity 1.5s ease-in-out;
                background-color: #000;
            }

            .hero-fade-carousel img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.55;
            }

            /* Overlay gradient */
            .hero-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
                background: linear-gradient(to right, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.3) 100%);
            }
        </style>

        <div id="heroFadeCarousel" class="carousel slide carousel-fade hero-fade-carousel" data-bs-ride="carousel"
            data-bs-pause="false" data-bs-interval="4000">
            <div class="carousel-inner" style="overflow: hidden;">
                <div class="carousel-item active">
                    <img src="{{ asset('images/hero/slide1.png') }}" alt="Tech Devices">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/hero/slide2.png') }}" alt="Fashion">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/hero/slide3.png') }}" alt="Home & Garden">
                </div>
            </div>
        </div>

        <div class="hero-overlay"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-5 mb-lg-0 pe-lg-4 text-center text-lg-start">
                    <h1
                        style="font-weight: 800; font-size: 3.5rem; line-height: 1.15; color: #fff; margin-bottom: 24px; letter-spacing: -1px;">
                        ALL THE TECH YOU<br>NEED, ALL IN ONE PLACE</h1>
                    <p class="mb-4 mx-auto mx-lg-0"
                        style="color: rgba(255,255,255,0.85); font-size: 1.05rem; max-width: 500px; line-height: 1.6;">
                        Discover the latest electronics, powerful computers, cutting-edge mobiles, security gadgets,
                        accessories, and more — all from trusted sellers and at unbeatable prices. Whether you're upgrading
                        your setup or searching for smart solutions, we've got everything covered.</p>
                    <a href="{{ route('products.index') }}"
                        class="btn rounded-pill d-inline-flex align-items-center fw-bold shadow-sm"
                        style="background-color: #f97316; color: #fff; padding: 12px 28px; font-size: 1rem; border: none; transition: transform 0.2s;">
                        Shop Now <span
                            class="bg-white rounded-circle ms-3 d-inline-flex align-items-center justify-content-center"
                            style="width:28px;height:28px; color:#f97316;"><i class="bi bi-arrow-up-right fw-bold"
                                style="font-size:0.8rem;"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- ═══════════ CATEGORY ICONS ═══════════ -->
    <!-- ═══════════ CATEGORY ICONS ═══════════ -->
    <section class="py-5">
        <div class="container">
            @php
                $categoryIcons = [
                    'electronics' => 'bi-cpu',
                    'clothing' => 'bi-bag',
                    'home-garden' => 'bi-house-heart',
                    'sports-outdoors' => 'bi-bicycle',
                    'books' => 'bi-book',
                ];
            @endphp
            <div class="row justify-content-center g-4">
                @foreach($categories as $category)
                    <div class="col-4 col-md-2">
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-icon-item">
                            <div class="category-icon-circle">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <i class="bi {{ $categoryIcons[$category->slug] ?? 'bi-grid' }}"></i>
                                @endif
                            </div>
                            <h6>{{ $category->name }}</h6>
                            <small>{{ $category->products_count }} products</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- ═══════════ NEW ARRIVALS WITH TABS ═══════════ -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="section-heading">
                <div class="sub-label">Hurry up to buy</div>
                <h2>New Arrivals</h2>
                <p>Discover the latest products from our top categories</p>
            </div>

            <!-- Category Tabs -->
            <ul class="nav product-tabs" id="arrivalTabs" role="tablist">
                @php $first = true; @endphp
                @foreach($newArrivals as $slug => $data)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $first ? 'active' : '' }}" id="tab-{{ $slug }}" data-bs-toggle="tab"
                            data-bs-target="#panel-{{ $slug }}" type="button"
                            role="tab">{{ strtoupper($data['name']) }}</button>
                    </li>
                    @php $first = false; @endphp
                @endforeach
            </ul>

            <!-- Tab Panels -->
            <div class="tab-content" id="arrivalTabContent">
                @php $first = true; @endphp
                @foreach($newArrivals as $slug => $data)
                    <div class="tab-pane fade {{ $first ? 'show active' : '' }}" id="panel-{{ $slug }}" role="tabpanel">
                        <div class="row g-4">
                            @foreach($data['products'] as $product)
                                @php
                                    // Generate a soft random pastel background based on product ID for the image box
                                    $colors = ['#f0fdf4', '#f0f9ff', '#fffbeb', '#fdf4ff', '#eff6ff', '#f8fafc'];
                                    $bg = $colors[$product->id % count($colors)];
                                @endphp
                                <div class="col-lg-2 col-md-3 col-6">
                                    <!-- Wrapper card -->
                                    <div class="card h-100 product-clickable-card bg-white"
                                        data-product-url="{{ route('products.show', $product->slug) }}"
                                        style="border: 1px solid #f0f0f0; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; position:relative;">

                                        <!-- Inner Image block -->
                                        <div class="m-2 d-flex align-items-center justify-content-center position-relative"
                                            style="background-color: {{ $bg }}; border-radius: 10px; height: 160px; overflow: hidden; padding: 10px;">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                    style="width: 100%; height: 100%; object-fit: contain; z-index: 2; mix-blend-mode: darken;">
                                            @else
                                                <i class="bi bi-box-seam fs-1 text-muted"></i>
                                            @endif

                                            <!-- Mini Badges inside image -->
                                            @if($product->stock == 0)
                                                <span class="position-absolute top-0 start-0 m-2 badge bg-dark rounded-pill px-2 py-1"
                                                    style="font-size: 0.65rem; z-index: 5;">Sold Out</span>
                                            @elseif($product->hasActiveDiscount())
                                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill px-2 py-1"
                                                    style="font-size: 0.65rem; z-index: 5;">-{{ $product->discount_percentage }}%</span>
                                            @elseif($product->is_hot)
                                                <span
                                                    class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark rounded-pill px-2 py-1"
                                                    style="font-size: 0.65rem; z-index: 5;">Hot</span>
                                            @endif
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body px-2 pb-2 pt-1 d-flex flex-column bg-transparent"
                                            style="position:relative;">
                                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;">
                                                {{ $product->name }}
                                            </h6>
                                            <small class="text-muted d-block text-truncate mb-2"
                                                style="font-size: 0.70rem;">{{ $product->category->name }}</small>

                                            <div class="mt-auto d-flex flex-column pt-1">
                                                @if($product->hasActiveDiscount())
                                                    <span class="fw-bold"
                                                        style="color: var(--primary); font-size: 0.95rem;">₵{{ number_format($product->discounted_price, 2) }}</span>
                                                    <span class="text-decoration-line-through text-muted"
                                                        style="font-size: 0.75rem;">₵{{ number_format($product->price, 2) }}</span>
                                                @else
                                                    <span class="fw-bold"
                                                        style="color: var(--primary); font-size: 0.95rem;">₵{{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Floating Action Button -->
                                        @if($product->stock > 0)
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST"
                                                class="ajax-cart-form position-absolute" style="bottom: 8px; right: 8px; z-index:5;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center add-cart-btn"
                                                    style="width: 28px; height: 28px; padding:0; border: none; background-color: var(--primary); color: white; transition: background 0.2s;">
                                                    <i class="bi bi-arrow-up-right fw-bold" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @php $first = false; @endphp
                @endforeach
            </div>
        </div>
    </section>


    <!-- ═══════════ MOBILE FOR ALL (PRODUCTS) ═══════════ -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.4rem;">Mobile for All</h4>
                <a href="{{ route('products.index') }}" class="fw-bold text-decoration-none"
                    style="color: var(--primary); font-size: 0.85rem;">See All</a>
            </div>

            <div class="row g-4">
                @foreach($featuredProducts->take(4) as $product)
                    @php
                        // Generate a soft random pastel background based on product ID for the image box
                        $colors = ['#f0fdf4', '#f0f9ff', '#fffbeb', '#fdf4ff', '#eff6ff', '#f8fafc'];
                        $bg = $colors[$product->id % count($colors)];
                    @endphp
                    <div class="col-lg-3 col-md-4 col-6">
                        <!-- Wrapper card (border outline around everything) -->
                        <div class="card h-100 product-clickable-card bg-white"
                            data-product-url="{{ route('products.show', $product->slug) }}"
                            style="border: 1px solid #f0f0f0; border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s; position:relative;">

                            <!-- Inner Image block -->
                            <div class="m-2 d-flex align-items-center justify-content-center position-relative"
                                style="background-color: {{ $bg }}; border-radius: 12px; height: 200px; overflow: hidden; padding: 10px;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        style="width: 100%; height: 100%; object-fit: contain; z-index: 2; mix-blend-mode: darken;">
                                @else
                                    <i class="bi bi-phone fs-1 text-muted"></i>
                                @endif

                                <!-- Mini Badges inside image -->
                                @if($product->hasActiveDiscount())
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill px-2 py-1"
                                        style="font-size: 0.65rem;">-{{ $product->discount_percentage }}%</span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body px-3 pb-3 pt-2 d-flex flex-column bg-transparent" style="position:relative;">
                                <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.95rem;">
                                    {{ $product->name }}
                                </h6>
                                <small class="text-muted d-block text-truncate mb-2" style="font-size: 0.75rem;">Discover the
                                    latest electronic</small>

                                <div class="mt-auto d-flex flex-column pt-1">
                                    @if($product->hasActiveDiscount())
                                        <span class="fw-bold"
                                            style="color: var(--primary); font-size: 1rem;">₵{{ number_format($product->discounted_price, 2) }}</span>
                                        <span class="text-decoration-line-through text-muted"
                                            style="font-size: 0.75rem;">₵{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="fw-bold"
                                            style="color: var(--primary); font-size: 1rem;">₵{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Floating Action Button -->
                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST"
                                    class="ajax-cart-form position-absolute" style="bottom: 12px; right: 12px; z-index:5;">
                                    @csrf
                                    <button type="submit"
                                        class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center add-cart-btn"
                                        style="width: 32px; height: 32px; padding:0; border: none; background-color: var(--primary); color: white; transition: background 0.2s;">
                                        <i class="bi bi-arrow-up-right fw-bold" style="font-size: 0.9rem;"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- ═══════════ FEATURES / TRUST BADGES ═══════════ -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="p-4">
                        <div class="category-icon-circle mx-auto mb-3"
                            style="width:70px;height:70px;font-size:1.5rem;background:rgba(37,99,235,0.06);color:#2563eb">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h6 class="fw-bold">Free Shipping</h6>
                        <small class="text-muted">On orders over $50</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-4">
                        <div class="category-icon-circle mx-auto mb-3"
                            style="width:70px;height:70px;font-size:1.5rem;background:rgba(16,185,129,0.06);color:#10b981">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="fw-bold">Secure Payment</h6>
                        <small class="text-muted">100% protected</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-4">
                        <div class="category-icon-circle mx-auto mb-3"
                            style="width:70px;height:70px;font-size:1.5rem;background:rgba(245,158,11,0.06);color:#f59e0b">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h6 class="fw-bold">Easy Returns</h6>
                        <small class="text-muted">30-day returns</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-4">
                        <div class="category-icon-circle mx-auto mb-3"
                            style="width:70px;height:70px;font-size:1.5rem;background:rgba(108,92,231,0.06);color:#6c5ce7">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h6 class="fw-bold">24/7 Support</h6>
                        <small class="text-muted">Always here for you</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection