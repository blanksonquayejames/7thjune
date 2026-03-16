@extends('admin.layouts.app')
@section('page-title', 'Order #' . $order->id)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light" style="border-radius:10px">
        <i class="bi bi-arrow-left me-2"></i>Back to Orders
    </a>
</div>

<div class="row g-4">
    <!-- Order Items -->
    <div class="col-lg-8">
        <div class="card admin-card p-4">
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
                    <h6 class="mb-0 fw-semibold">{{ $item->product->name ?? 'Deleted Product' }}</h6>
                    <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</small>
                </div>
                <span class="fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach

            <hr>
            <div class="d-flex justify-content-end">
                <div>
                    <span class="text-muted">Total:</span>
                    <span class="fw-bold fs-4 ms-2" style="color:#6c5ce7">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Info -->
    <div class="col-lg-4">
        <div class="card admin-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Customer Info</h6>
            <p class="mb-1"><i class="bi bi-person me-2"></i>{{ $order->user->name ?? 'N/A' }}</p>
            <p class="mb-0"><i class="bi bi-envelope me-2"></i>{{ $order->user->email ?? 'N/A' }}</p>
        </div>

        <div class="card admin-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Shipping</h6>
            <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $order->shipping_address }}</p>
            <p class="mb-0"><i class="bi bi-telephone me-2"></i>{{ $order->phone }}</p>
        </div>

        <div class="card admin-card p-4">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select form-control-custom mb-3">
                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-check-lg me-2"></i>Update Status
                </button>
            </form>
            <p class="text-muted small mt-2 mb-0">Placed: {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
        </div>
    </div>
</div>
@endsection
