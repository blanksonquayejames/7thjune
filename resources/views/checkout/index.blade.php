@extends('layouts.store')
@section('title', 'Checkout')

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-credit-card me-2"></i>Checkout</h2>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Shipping Form -->
        <div class="col-lg-7">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2 text-primary"></i>Shipping Information</h5>

                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-semibold">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                      class="form-control form-control-custom @error('shipping_address') is-invalid @enderror"
                                      placeholder="Enter your full shipping address..." required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control form-control-custom @error('phone') is-invalid @enderror"
                                   placeholder="Enter your phone number" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 py-3">
                            <i class="bi bi-check-circle me-2"></i>Place Order — ₵{{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Review -->
        <div class="col-lg-5">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-bag-check me-2 text-primary"></i>Order Review</h5>

                    @foreach($cartItems as $item)
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" width="50" height="50" class="rounded-3" style="object-fit:cover" alt="">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px">
                                <i class="bi bi-box-seam text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0 small fw-semibold">{{ $item->product->name }}</h6>
                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                        </div>
                        <span class="fw-bold">₵{{ number_format($item->product->discounted_price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₵{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary">₵{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
