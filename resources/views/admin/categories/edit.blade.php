@extends('admin.layouts.app')
@section('page-title', 'Edit Category')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light" style="border-radius:10px">
        <i class="bi bi-arrow-left me-2"></i>Back to Categories
    </a>
</div>

<div class="card admin-card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Edit Category: {{ $category->name }}</h5>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Image (optional)</label>
                    <input type="file" name="image" class="form-control form-control-custom @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($category->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $category->image) }}" width="80" class="rounded-3" alt="">
                            <small class="text-muted d-block mt-1">Current image</small>
                        </div>
                    @endif
                </div>
            </div>
            <hr class="my-4">
            <button type="submit" class="btn btn-primary-custom px-4 py-2">
                <i class="bi bi-check-lg me-2"></i>Update Category
            </button>
        </form>
    </div>
</div>
@endsection
