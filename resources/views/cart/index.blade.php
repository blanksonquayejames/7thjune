@extends('layouts.store')
@section('title', 'Shopping Cart')

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>
    </div>
</div>

<div class="container pb-5">
    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted d-block mb-3" style="font-size:4rem !important"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary-custom">
                <i class="bi bi-bag me-2"></i>Start Shopping
            </a>
        </div>
    @else
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" width="60" height="60" class="rounded-3" style="object-fit:cover" alt="">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px">
                                                        <i class="bi bi-box-seam text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->product->price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2" style="width:120px">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                                       class="form-control form-control-sm text-center" style="border-radius:8px" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-semibold text-success">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">${{ number_format($total, 2) }}</span>
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary-custom w-100 py-3">
                                <i class="bi bi-lock me-2"></i>Proceed to Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary-custom w-100 py-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login to Checkout
                            </a>
                            <p class="text-center text-muted small mt-2">You need an account to checkout</p>
                        @endauth

                        <a href="{{ route('products.index') }}" class="btn btn-secondary-custom w-100 mt-2">
                            <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
