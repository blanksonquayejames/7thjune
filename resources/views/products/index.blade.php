@extends('layouts.store')
@section('title', 'Products')

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-grid-3x3-gap me-2"></i>Our Products</h2>
        <p class="mb-0 mt-2 opacity-75">Discover our amazing collection</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card card-custom p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-2"></i>Filters</h6>

                <!-- Search -->
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="mb-3">
                        <input type="text" name="search" class="form-control form-control-custom" placeholder="Search products..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Category</label>
                        <select name="category" class="form-select form-control-custom">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Sort By</label>
                        <select name="sort" class="form-select form-control-custom">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-search me-2"></i>Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                    <h5>No products found</h5>
                    <p class="text-muted">Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary-custom">Clear Filters</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4 col-6">
                        <div class="card card-custom h-100 product-clickable-card" data-product-url="{{ route('products.show', $product->slug) }}">
                            <div class="card-img-wrapper">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @else
                                    <div class="placeholder-img">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                @endif
                                @if($product->stock == 0)
                                    <span class="badge-overlay"><span class="badge bg-danger">Out of Stock</span></span>
                                @elseif($product->hasActiveDiscount())
                                    <span class="badge-overlay"><span class="badge bg-danger">-{{ $product->discount_percentage }}%</span></span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted">{{ $product->category->name ?? '' }}</small>
                                <h6 class="mt-1 mb-2">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                                </h6>
                                <p class="text-muted small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($product->hasActiveDiscount())
                                        <span class="text-decoration-line-through text-muted small">₵{{ number_format($product->price, 2) }}</span>
                                        <span class="product-price">₵{{ number_format($product->discounted_price, 2) }}</span>
                                    @else
                                        <span class="product-price">₵{{ number_format($product->price, 2) }}</span>
                                    @endif
                                    @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                                        @csrf
                                        <button type="submit" class="btn btn-accent btn-sm px-3">
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

                <div class="d-flex justify-content-center mt-5">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
