@extends('layouts.store')
@section('title', $product->name)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-lg-6">
            @php
                // Generate the pastel background for consistency
                $colors = ['#f0fdf4', '#f0f9ff', '#fffbeb', '#fdf4ff', '#eff6ff', '#f8fafc'];
                $bg = $colors[$product->id % count($colors)];
            @endphp
            <div class="card overflow-hidden border-0" style="background-color: {{ $bg }}; border-radius: 20px;">
                <div class="p-4 d-flex align-items-center justify-content-center" style="min-height: 450px;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}" style="max-height:450px; object-fit:contain; mix-blend-mode: darken;">
                    @else
                        <div class="placeholder-img" style="height:400px; font-size:5rem">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <span class="badge rounded-pill bg-light text-dark mb-2 px-3 py-2">{{ $product->category->name }}</span>
            <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

            <div class="mb-3">
                @php
                    $avgRating = $product->reviews->avg('rating');
                    $reviewCount = $product->reviews->count();
                @endphp
                <span class="text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avgRating))
                            <i class="bi bi-star-fill"></i>
                        @else
                            <i class="bi bi-star"></i>
                        @endif
                    @endfor
                </span>
                <span class="ms-2 text-muted">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
            </div>

            <div class="d-flex align-items-center gap-3 mb-2">
                @if($product->hasActiveDiscount())
                    <span class="text-decoration-line-through text-muted" style="font-size:1.2rem">₵{{ number_format($product->price, 2) }}</span>
                    <span class="product-price" style="font-size:2rem">₵{{ number_format($product->discounted_price, 2) }}</span>
                    <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">-{{ $product->discount_percentage }}% OFF</span>
                @else
                    <span class="product-price" style="font-size:2rem">₵{{ number_format($product->price, 2) }}</span>
                @endif
                @if($product->stock > 0)
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                        <i class="bi bi-check-circle me-1"></i>In Stock ({{ $product->stock }})
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                        <i class="bi bi-x-circle me-1"></i>Out of Stock
                    </span>
                @endif
            </div>

            @if($product->hasActiveDiscount() && $product->discount_end)
            <div class="discount-countdown-lg mb-4" data-countdown-end="{{ $product->discount_end->toIso8601String() }}">
                <div>
                    <span class="cd-label"><i class="bi bi-clock-history me-1"></i>Sale ends in</span>
                </div>
                <div class="cd-timer">
                    <div class="cd-unit">
                        <span class="cd-value cd-days">00</span>
                        <span class="cd-text">Days</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-value cd-hours">00</span>
                        <span class="cd-text">Hrs</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-value cd-mins">00</span>
                        <span class="cd-text">Min</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-value cd-secs">00</span>
                        <span class="cd-text">Sec</span>
                    </div>
                </div>
            </div>
            @endif

            <p class="text-muted lh-lg mb-4">{{ $product->description }}</p>

            @if($product->stock > 0)
            <div class="d-flex gap-3 mb-4 align-items-center">
                @if($inCart)
                    <span class="btn btn-in-cart"><i class="bi bi-check2 me-1"></i>Already in Cart</span>
                @else
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                        @csrf
                        <button type="submit" class="btn btn-add-to-cart text-white px-4">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                    </form>
                @endif

                <form action="{{ route('cart.buyNow', $product->id) }}" method="POST" class="ajax-cart-form">
                    @csrf
                    <button type="submit" class="btn btn-buy-now text-white px-4">
                        <i class="bi bi-bag-check me-2"></i>Buy Now
                    </button>
                </form>
            </div>
            @endif

            <div class="row g-3 mt-3">
                <div class="col-4">
                    <div class="text-center p-3 bg-light rounded-3">
                        <i class="bi bi-truck text-primary fs-4 d-block mb-1"></i>
                        <small class="fw-semibold">Free Shipping</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 bg-light rounded-3">
                        <i class="bi bi-shield-check text-primary fs-4 d-block mb-1"></i>
                        <small class="fw-semibold">Secure Pay</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 bg-light rounded-3">
                        <i class="bi bi-arrow-repeat text-primary fs-4 d-block mb-1"></i>
                        <small class="fw-semibold">30-Day Return</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="mt-5 pt-5 border-top">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="section-title mb-4">Customer Reviews</h3>

                <!-- Average Rating Summary -->
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        @php
                            $avgRating = $product->reviews->avg('rating');
                            $reviewCount = $product->reviews->count();
                        @endphp
                        <span class="fs-1 fw-bold">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-muted">out of 5</span>
                    </div>
                    <div>
                        <div class="text-warning fs-5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted">Based on {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</small>
                    </div>
                </div>

                <!-- Review Form -->
                <div class="card bg-light mb-5 border-0">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Write a Review</h5>
                        @auth
                            <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-select w-auto" required>
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Very Good</option>
                                        <option value="3">3 - Good</option>
                                        <option value="2">2 - Fair</option>
                                        <option value="1">1 - Poor</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Comment (Optional)</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your thoughts about this product..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        @else
                            <p class="mb-0">Please <a href="{{ route('login') }}" class="text-primary fw-bold">log in</a> to leave a review.</p>
                        @endauth
                    </div>
                </div>

                <!-- Review List -->
                <div>
                    @forelse($product->reviews as $review)
                        <div class="mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <span class="text-muted ms-2 small">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="mb-0 text-muted">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
    <section class="mt-5 pt-5 border-top">
        <h3 class="section-title mb-5">Related Products</h3>
        <div class="row g-4 mt-3">
            @foreach($relatedProducts as $related)
            <div class="col-lg-3 col-md-4 col-6">
                @php
                    $colors = ['#f0fdf4', '#f0f9ff', '#fffbeb', '#fdf4ff', '#eff6ff', '#f8fafc'];
                    $bg = $colors[$related->id % count($colors)];
                @endphp
                <div class="card h-100 product-clickable-card bg-white" data-product-url="{{ route('products.show', $related->slug) }}" style="border: 1px solid #f0f0f0; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; position:relative;">
                    
                    <div class="m-2 d-flex align-items-center justify-content-center position-relative" style="background-color: {{ $bg }}; border-radius: 10px; height: 180px; overflow: hidden; padding: 10px;">
                        @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" style="width: 100%; height: 100%; object-fit: contain; z-index: 2; mix-blend-mode: darken;">
                        @else
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                        @endif
                        
                        @if($related->stock == 0)
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark rounded-pill px-2 py-1" style="font-size: 0.65rem; z-index: 5;">Sold Out</span>
                        @elseif($related->hasActiveDiscount())
                            <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.65rem; z-index: 5;">-{{ $related->discount_percentage }}%</span>
                        @endif
                    </div>

                    <div class="card-body px-2 pb-2 pt-1 d-flex flex-column bg-transparent" style="position:relative;">
                        <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;">
                            {{ $related->name }}
                        </h6>
                        <small class="text-muted d-block text-truncate mb-2" style="font-size: 0.70rem;">{{ $related->category->name ?? '' }}</small>
                        
                        <div class="mt-auto d-flex flex-column pt-1">
                            @if($related->hasActiveDiscount())
                                <span class="fw-bold" style="color: var(--primary); font-size: 0.95rem;">₵{{ number_format($related->discounted_price, 2) }}</span>
                                <span class="text-decoration-line-through text-muted" style="font-size: 0.75rem;">₵{{ number_format($related->price, 2) }}</span>
                            @else
                                <span class="fw-bold" style="color: var(--primary); font-size: 0.95rem;">₵{{ number_format($related->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
