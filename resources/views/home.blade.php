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
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    .hero-carousel .carousel-control-prev { left: 16px; }
    .hero-carousel .carousel-control-next { right: 16px; }
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
        box-shadow: 0 6px 20px rgba(37,99,235,0.35);
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
        .hero-slide { min-height: 350px; }
        .hero-slide h1 { font-size: 2rem; }
        .hero-slide-image { width: 45%; opacity: 0.3; }
        .hero-slide-content { padding: 40px 0; }
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
        background: rgba(108,92,231,0.08);
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
    .star-rating i { color: #facc15; font-size: 0.8rem; }
    .star-rating i.empty { color: #d1d5db; }
</style>
@endpush

@section('content')
<!-- ═══════════ HERO CAROUSEL ═══════════ -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <div class="hero-slide">
                <img src="{{ asset('images/hero/slide1.png') }}" class="hero-slide-image" alt="">
                <div class="container hero-slide-content">
                    <div class="col-lg-5">
                        <h1>Charge Your<br>Phone Safely!</h1>
                        <p class="my-4">A wonderful collection of premium tech accessories. Discover the latest gadgets at unbeatable prices.</p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('products.index') }}" class="btn btn-hero-primary">To Shop</a>
                            <a href="{{ route('products.index', ['category' => 'electronics']) }}" class="btn btn-hero-outline">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="hero-slide">
                <img src="{{ asset('images/hero/slide2.png') }}" class="hero-slide-image" alt="">
                <div class="container hero-slide-content">
                    <div class="col-lg-5">
                        <h1>Style That<br>Speaks For You</h1>
                        <p class="my-4">Explore our curated fashion collection. Premium quality clothing and accessories for every occasion.</p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('products.index', ['category' => 'clothing']) }}" class="btn btn-hero-primary">To Shop</a>
                            <a href="{{ route('products.index') }}" class="btn btn-hero-outline">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <div class="hero-slide">
                <img src="{{ asset('images/hero/slide3.png') }}" class="hero-slide-image" alt="">
                <div class="container hero-slide-content">
                    <div class="col-lg-5">
                        <h1>Make Your<br>Home Beautiful</h1>
                        <p class="my-4">Transform your living space with our home & garden catalog. Candles, plants, decor and more.</p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('products.index', ['category' => 'home-garden']) }}" class="btn btn-hero-primary">To Shop</a>
                            <a href="{{ route('products.index') }}" class="btn btn-hero-outline">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>


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
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
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
                <button class="nav-link {{ $first ? 'active' : '' }}"
                        id="tab-{{ $slug }}"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-{{ $slug }}"
                        type="button" role="tab">{{ strtoupper($data['name']) }}</button>
            </li>
            @php $first = false; @endphp
            @endforeach
        </ul>

        <!-- Tab Panels -->
        <div class="tab-content" id="arrivalTabContent">
            @php $first = true; @endphp
            @foreach($newArrivals as $slug => $data)
            <div class="tab-pane fade {{ $first ? 'show active' : '' }}"
                 id="panel-{{ $slug }}" role="tabpanel">
                <div class="row g-4">
                    @foreach($data['products'] as $product)
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="card card-custom h-100">
                            <div class="card-img-wrapper">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @else
                                    <div class="placeholder-img" style="height:180px; font-size:2.5rem">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                @endif

                                {{-- Badges --}}
                                <div class="product-badge">
                                    @if($product->stock == 0)
                                        <span class="badge-sold-out">Sold Out</span>
                                    @endif
                                    @if($product->is_hot)
                                        <span class="badge-hot">Hot</span>
                                    @endif
                                    @if($product->hasActiveDiscount())
                                        <span class="badge-hot" style="background:#ef4444">-{{ $product->discount_percentage }}%</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body px-3 py-3">
                                <h6 class="mb-1" style="font-size:0.85rem; font-weight:600">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($product->name, 30) }}</a>
                                </h6>
                                <small class="text-muted d-block mb-1">{{ $product->category->name }}</small>
                                @if($product->is_hot)
                                <div class="star-rating mb-1">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                @endif
                                @if($product->hasActiveDiscount())
                                    <span class="text-decoration-line-through text-muted" style="font-size:0.8rem">₵{{ number_format($product->price, 2) }}</span>
                                    <span class="product-price" style="font-size:1rem">₵{{ number_format($product->discounted_price, 2) }}</span>
                                    @if($product->discount_end)
                                    <div class="discount-countdown mt-1" data-countdown-end="{{ $product->discount_end->toIso8601String() }}">
                                        <i class="bi bi-clock"></i>
                                        <span class="cd-compact-text">Loading...</span>
                                    </div>
                                    @endif
                                @else
                                    <span class="product-price" style="font-size:1rem">₵{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
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


<!-- ═══════════ FEATURED PRODUCTS ═══════════ -->
<section class="py-5" style="background-color: #F9FAFB;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title">Popular Products</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary-custom">View All <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card card-custom h-100">
                    <div class="card-img-wrapper">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <div class="placeholder-img">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif

                        {{-- Badges --}}
                        <div class="product-badge">
                            @if($product->stock == 0)
                                <span class="badge-sold-out">Sold Out</span>
                            @elseif($product->stock < 5)
                                <span class="badge-hot" style="background:#f59e0b">Low Stock</span>
                            @endif
                            @if($product->is_hot)
                                <span class="badge-hot">Hot</span>
                            @endif
                            @if($product->hasActiveDiscount())
                                <span class="badge-hot" style="background:#ef4444">-{{ $product->discount_percentage }}%</span>
                            @elseif($product->created_at->diffInDays(now()) < 7)
                                <span class="badge-new">New</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <small class="text-muted">{{ $product->category->name ?? '' }}</small>
                        <h6 class="mt-1 mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                        </h6>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            @if($product->hasActiveDiscount())
                                <span class="text-decoration-line-through text-muted small">₵{{ number_format($product->price, 2) }}</span>
                                <span class="product-price">₵{{ number_format($product->discounted_price, 2) }}</span>
                            @else
                                <span class="product-price">₵{{ number_format($product->price, 2) }}</span>
                            @endif
                            @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-add-to-cart btn-sm px-3">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                        @if($product->hasActiveDiscount() && $product->discount_end)
                        <div class="discount-countdown mt-2" data-countdown-end="{{ $product->discount_end->toIso8601String() }}">
                            <i class="bi bi-clock"></i>
                            <span class="cd-compact-text">Loading...</span>
                        </div>
                        @endif
                    </div>
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
                    <div class="category-icon-circle mx-auto mb-3" style="width:70px;height:70px;font-size:1.5rem;background:rgba(37,99,235,0.06);color:#2563eb">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h6 class="fw-bold">Free Shipping</h6>
                    <small class="text-muted">On orders over $50</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <div class="category-icon-circle mx-auto mb-3" style="width:70px;height:70px;font-size:1.5rem;background:rgba(16,185,129,0.06);color:#10b981">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h6 class="fw-bold">Secure Payment</h6>
                    <small class="text-muted">100% protected</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <div class="category-icon-circle mx-auto mb-3" style="width:70px;height:70px;font-size:1.5rem;background:rgba(245,158,11,0.06);color:#f59e0b">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h6 class="fw-bold">Easy Returns</h6>
                    <small class="text-muted">30-day returns</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4">
                    <div class="category-icon-circle mx-auto mb-3" style="width:70px;height:70px;font-size:1.5rem;background:rgba(108,92,231,0.06);color:#6c5ce7">
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
