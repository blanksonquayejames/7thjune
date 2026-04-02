@extends('layouts.store')
@section('title', 'My Orders')

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-box-seam me-2"></i>My Orders</h2>
    </div>
</div>

<div class="container pb-5">
    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-box-seam fs-1 text-muted d-block mb-3" style="font-size:4rem !important"></i>
            <h4>No orders yet</h4>
            <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary-custom">
                <i class="bi bi-bag me-2"></i>Shop Now
            </a>
        </div>
    @else
        <div class="card card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="fw-bold">#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->items->count() }} item(s)</td>
                                <td class="fw-bold">₵{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-secondary-custom">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
