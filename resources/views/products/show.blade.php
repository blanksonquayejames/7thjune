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
            <div class="card card-custom overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-100" alt="{{ $product->name }}" style="max-height:500px; object-fit:cover">
                @else
                    <div class="placeholder-img" style="height:400px; font-size:5rem">
                        <i class="bi bi-box-seam"></i>
                    </div>
                @endif
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

            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="product-price" style="font-size:2rem">${{ number_format($product->price, 2) }}</span>
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

            <p class="text-muted lh-lg mb-4">{{ $product->description }}</p>

            @if($product->stock > 0)
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex gap-3 mb-4">
                @csrf
                <button type="submit" class="btn btn-add-to-cart text-white btn-lg px-4">
                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                </button>
                <button type="button" class="btn btn-buy-now text-white btn-lg px-4" onclick="this.form.submit()">
                    <i class="bi bi-bag-check me-2"></i>Buy Now
                </button>
            </form>
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
                <div class="card card-custom h-100">
                    <div class="card-img-wrapper">
                        @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->name }}">
                        @else
                            <div class="placeholder-img"><i class="bi bi-box-seam"></i></div>
                        @endif
                    </div>
                    <div class="card-body">
                        <small class="text-muted">{{ $related->category->name ?? '' }}</small>
                        <h6 class="mt-1"><a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark">{{ $related->name }}</a></h6>
                        <span class="product-price">${{ number_format($related->price, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
