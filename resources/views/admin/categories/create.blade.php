@extends('admin.layouts.app')
@section('page-title', 'Add Category')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light" style="border-radius:10px">
        <i class="bi bi-arrow-left me-2"></i>Back to Categories
    </a>
</div>

<div class="card admin-card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Add New Category</h5>
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Image (optional)</label>
                    <input type="file" name="image" class="form-control form-control-custom @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <hr class="my-4">
            <button type="submit" class="btn btn-primary-custom px-4 py-2">
                <i class="bi bi-check-lg me-2"></i>Create Category
            </button>
        </form>
    </div>
</div>
@endsection
