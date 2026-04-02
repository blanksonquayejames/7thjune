@extends('admin.layouts.app')
@section('page-title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Products</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-2"></i>Add Product
    </a>
</div>

<div class="card admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" class="rounded-3" style="object-fit:cover" alt="">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td><span class="badge bg-light text-dark">{{ $product->category->name ?? 'N/A' }}</span></td>
                        <td>
                            @if($product->hasActiveDiscount())
                                <span class="text-decoration-line-through text-muted small">₵{{ number_format($product->price, 2) }}</span>
                                <span class="fw-bold text-success">₵{{ number_format($product->discounted_price, 2) }}</span>
                                <span class="badge bg-warning-subtle text-warning ms-1" style="font-size:0.65rem">-{{ $product->discount_percentage }}%</span>
                            @else
                                ₵{{ number_format($product->price, 2) }}
                            @endif
                        </td>
                        <td>
                            @if($product->stock > 10)
                                <span class="text-success fw-semibold">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="text-warning fw-semibold">{{ $product->stock }}</span>
                            @else
                                <span class="text-danger fw-semibold">0</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-light" style="border-radius:8px" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:8px" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endsection
