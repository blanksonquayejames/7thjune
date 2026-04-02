@extends('admin.layouts.app')
@section('page-title', 'Add Product')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-light" style="border-radius:10px">
        <i class="bi bi-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="card admin-card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Add New Product</h5>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category_id" class="form-select form-control-custom @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="4" class="form-control form-control-custom @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price (₵)</label>
                    <input type="number" step="0.01" name="price" class="form-control form-control-custom @error('price') is-invalid @enderror"
                           value="{{ old('price') }}" min="0" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock</label>
                    <input type="number" name="stock" class="form-control form-control-custom @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" class="form-control form-control-custom @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Active (visible in store)</label>
                    </div>
                </div>
            </div>

            {{-- Discount Section --}}
            <div class="mt-4 p-4 rounded-3" style="background:#fff7ed; border-left:4px solid #f59e0b">
                <h6 class="fw-bold mb-3"><i class="bi bi-tag me-2 text-warning"></i>Product Discount <span class="text-muted fw-normal small">(optional)</span></h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="discount_percentage"
                                   class="form-control form-control-custom @error('discount_percentage') is-invalid @enderror"
                                   value="{{ old('discount_percentage') }}" min="0" max="100"
                                   placeholder="e.g. 15">
                            <span class="input-group-text"><i class="bi bi-percent"></i></span>
                        </div>
                        @error('discount_percentage')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="datetime-local" name="discount_start"
                               class="form-control form-control-custom @error('discount_start') is-invalid @enderror"
                               value="{{ old('discount_start') }}">
                        @error('discount_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="datetime-local" name="discount_end"
                               class="form-control form-control-custom @error('discount_end') is-invalid @enderror"
                               value="{{ old('discount_end') }}">
                        @error('discount_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i>Leave dates empty for an always-active discount. Set end date for a limited-time sale.</small>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-primary-custom px-4 py-2">
                <i class="bi bi-check-lg me-2"></i>Create Product
            </button>
        </form>
    </div>
</div>
@endsection

