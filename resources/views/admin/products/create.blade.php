@extends('layouts.admin')

@section('title', 'Add Furniture Product')
@section('page-title', 'Create New Furniture Item')
@section('page-subtitle', 'Add new handcrafted piece to the store catalog')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white max-w-4xl mb-4">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Product Title -->
            <div class="col-md-8">
                <label class="form-label small fw-bold text-uppercase">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Minimalist Solid Walnut Dining Table">
                @error('name')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select bg-light @error('category_id') is-invalid @enderror" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pricing & Stock -->
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Regular Price (₱) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="price" class="form-control bg-light @error('price') is-invalid @enderror" value="{{ old('price') }}" required placeholder="e.g. 899.00">
                @error('price')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Sale Price (₱) <span class="text-muted">(Optional)</span></label>
                <input type="number" step="0.01" name="sale_price" class="form-control bg-light @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}" placeholder="e.g. 799.00">
                @error('sale_price')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Stock Quantity <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control bg-light @error('stock') is-invalid @enderror" value="{{ old('stock', 10) }}" required min="0">
                @error('stock')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Short Description -->
            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase">Short Summary</label>
                <input type="text" name="short_description" class="form-control bg-light" value="{{ old('short_description') }}" placeholder="Brief 1-sentence highlight for cards and previews...">
            </div>

            <!-- Full Description -->
            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase">Detailed Description & Specifications <span class="text-danger">*</span></label>
                <textarea name="description" rows="5" class="form-control bg-light @error('description') is-invalid @enderror" required placeholder="Detailed information regarding materials, dimensions, craftsmanship, joinery, and care instructions...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Images -->
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase">Main Product Image</label>
                <input type="file" name="main_image" class="form-control bg-light @error('main_image') is-invalid @enderror" accept="image/*">
                <small class="text-muted d-block mt-1">High resolution JPG/PNG/WebP recommended.</small>
                @error('main_image')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase">Additional Gallery Images</label>
                <input type="file" name="additional_images[]" class="form-control bg-light" accept="image/*" multiple>
                <small class="text-muted d-block mt-1">Select multiple images for angles, dimensions, and texture.</small>
            </div>

            <!-- Options -->
            <div class="col-12">
                <div class="d-flex gap-4 p-3 bg-light rounded-3 border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="is_featured">
                            Feature on Home Page
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="is_active">
                            Visible to Customers
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                    <i class="bi bi-check2 me-1"></i> Save Furniture
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light px-4">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
