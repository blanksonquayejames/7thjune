@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['totalProducts'] }}</div>
                    <small class="text-muted fw-semibold">Total Products</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['totalOrders'] }}</div>
                    <small class="text-muted fw-semibold">Total Orders</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['totalUsers'] }}</div>
                    <small class="text-muted fw-semibold">Customers</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="stat-value">₵{{ number_format($stats['totalRevenue'], 2) }}</div>
                    <small class="text-muted fw-semibold">Revenue</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions + Recent Orders -->
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card admin-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom py-2">
                    <i class="bi bi-plus-lg me-2"></i>Add Product
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary py-2" style="border-radius:10px">
                    <i class="bi bi-plus-lg me-2"></i>Add Category
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary py-2" style="border-radius:10px">
                    <i class="bi bi-receipt me-2"></i>View All Orders
                </a>
            </div>

            @if($stats['pendingOrders'] > 0)
            <div class="alert alert-warning alert-custom mt-3 mb-0 py-2 px-3">
                <i class="bi bi-clock me-2"></i>
                <strong>{{ $stats['pendingOrders'] }}</strong> pending order(s)
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card admin-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Recent Orders</h6>
            @if($stats['recentOrders']->isEmpty())
                <p class="text-muted text-center py-4">No orders yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recentOrders'] as $order)
                            <tr>
                                <td class="fw-bold">#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>₵{{ number_format($order->total, 2) }}</td>
                                <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light" style="border-radius:8px">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
