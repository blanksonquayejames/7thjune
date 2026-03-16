@extends('layouts.store')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-receipt me-2"></i>Order #{{ $order->id }}</h2>
    </div>
</div>

<div class="container pb-5">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary-custom mb-4">
        <i class="bi bi-arrow-left me-2"></i>Back to Orders
    </a>

    <div class="row g-4">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Items</h5>

                    @foreach($order->items as $item)
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        @if($item->product->image ?? false)
                            <img src="{{ asset('storage/' . $item->product->image) }}" width="60" height="60" class="rounded-3" style="object-fit:cover" alt="">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px">
                                <i class="bi bi-box-seam text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">{{ $item->product->name ?? 'Product Unavailable' }}</h6>
                            <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</small>
                        </div>
                        <span class="fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-end">
                        <div class="text-end">
                            <span class="text-muted">Total:</span>
                            <span class="fw-bold fs-4 text-primary ms-2">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="col-lg-4">
            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Order Status</h6>
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    <p class="text-muted small mt-3 mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Shipping Information</h6>
                    <p class="mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i>{{ $order->shipping_address }}</p>
                    <p class="mb-0"><i class="bi bi-telephone me-2 text-primary"></i>{{ $order->phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
