@extends('admin.layouts.app')
@section('page-title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Categories</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-2"></i>Add Category
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
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" width="50" height="50" class="rounded-3" style="object-fit:cover" alt="">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px">
                                    <i class="bi bi-tags text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td><span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $category->products_count }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-light" style="border-radius:8px"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Are you sure? This will fail if category has products.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:8px"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-4">{{ $categories->links() }}</div>
@endsection
