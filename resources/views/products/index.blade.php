@extends('layouts.store')
@section('title', 'Products')

@push('styles')
<style>
    @media (min-width: 992px) {
        .search-sidebar { position: sticky; top: 90px; }
        .search-results-scroll { max-height: calc(100vh - 140px); overflow-y: auto; padding-right: 4px; }
        .search-results-scroll::-webkit-scrollbar { width: 4px; }
        .search-results-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 4px; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-grid-3x3-gap me-2"></i>Our Products</h2>
        <p class="mb-0 mt-1 opacity-75">Discover our amazing collection</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card card-custom p-4 search-sidebar">
                <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-2"></i>Filters</h6>

                <!-- Search -->
                <form action="{{ route('products.index') }}" method="GET" class="desktop-search-form">
                    <div class="mb-3">
                        <input type="text" name="search" class="form-control form-control-custom" placeholder="Search products..." minlength="3"
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
                <div class="search-results-scroll">
                    <div class="row g-4">
                        @foreach($products as $product)
                        <div class="col-md-4 col-6">
                            @php
                                $colors = ['#f0fdf4', '#f0f9ff', '#fffbeb', '#fdf4ff', '#eff6ff', '#f8fafc'];
                                $bg = $colors[$product->id % count($colors)];
                            @endphp
                            <div class="card h-100 product-clickable-card bg-white" data-product-url="{{ route('products.show', $product->slug) }}" style="border: 1px solid #f0f0f0; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; position:relative;">
                                <div class="m-2 d-flex align-items-center justify-content-center position-relative" style="background-color: {{ $bg }}; border-radius: 10px; height: 180px; overflow: hidden; padding: 10px;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain; z-index: 2; mix-blend-mode: darken;">
                                    @else
                                        <i class="bi bi-box-seam fs-1 text-muted"></i>
                                    @endif
                                    @if($product->stock == 0)
                                        <span class="position-absolute top-0 start-0 m-2 badge bg-dark rounded-pill px-2 py-1" style="font-size: 0.65rem; z-index: 5;">Sold Out</span>
                                    @elseif($product->hasActiveDiscount())
                                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.65rem; z-index: 5;">-{{ $product->discount_percentage }}%</span>
                                    @elseif($product->is_hot)
                                        <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 0.65rem; z-index: 5;">Hot</span>
                                    @endif
                                </div>
                                <div class="card-body px-3 pb-3 pt-2 d-flex flex-column bg-transparent" style="position:relative;">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.95rem;">{{ $product->name }}</h6>
                                    <small class="text-muted d-block text-truncate mb-2" style="font-size: 0.75rem;">{{ $product->category->name ?? 'Discover the latest' }}</small>
                                    <p class="text-muted small flex-grow-1" style="font-size:0.8rem;">{{ Str::limit($product->description, 50) }}</p>
                                    <div class="mt-auto d-flex flex-column pt-1">
                                        @if($product->hasActiveDiscount())
                                            <span class="fw-bold" style="color: var(--primary); font-size: 1rem;">₵{{ number_format($product->discounted_price, 2) }}</span>
                                            <span class="text-decoration-line-through text-muted" style="font-size: 0.75rem;">₵{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="fw-bold" style="color: var(--primary); font-size: 1rem;">₵{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form position-absolute" style="bottom: 12px; right: 12px; z-index:5;">
                                    @csrf
                                    <button type="submit" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center add-cart-btn" style="width: 32px; height: 32px; padding:0; border: none; background-color: var(--primary); color: white; transition: background 0.2s;">
                                        <i class="bi bi-cart-plus fw-bold" style="font-size: 0.9rem;"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
