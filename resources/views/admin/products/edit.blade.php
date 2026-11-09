@extends('layouts.admin')

@section('title', 'Edit: ' . $product->name)
@section('page-title', 'Edit Furniture Piece')
@section('page-subtitle', 'Modify product details, pricing, stock, or manage gallery images')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white max-w-4xl mb-4">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Product Title -->
            <div class="col-md-8">
                <label class="form-label small fw-bold text-uppercase">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select bg-light @error('category_id') is-invalid @enderror" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pricing & Stock -->
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Regular Price (₱) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="price" class="form-control bg-light @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Sale Price (₱) <span class="text-muted">(Optional)</span></label>
                <input type="number" step="0.01" name="sale_price" class="form-control bg-light @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $product->sale_price) }}">
                @error('sale_price')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Stock Quantity <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control bg-light @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required min="0">
                @error('stock')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Short Description -->
            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase">Short Summary</label>
                <input type="text" name="short_description" class="form-control bg-light" value="{{ old('short_description', $product->short_description) }}">
            </div>

            <!-- Full Description -->
            <div class="col-12">
                <label class="form-label small fw-bold text-uppercase">Detailed Description & Specifications <span class="text-danger">*</span></label>
                <textarea name="description" rows="5" class="form-control bg-light @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Main Image & Preview -->
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase">Replace Main Image</label>
                @if($product->main_image)
                    <div class="mb-2 border rounded-3 p-1 bg-light d-inline-block">
                        <img src="{{ $product->main_image }}" style="height: 80px; width: 100px; object-fit: cover;" class="rounded-2">
                    </div>
                @endif
                <input type="file" name="main_image" class="form-control bg-light" accept="image/*">
            </div>

            <!-- Additional Images -->
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase">Upload More Gallery Images</label>
                <input type="file" name="additional_images[]" class="form-control bg-light" accept="image/*" multiple>
            </div>

            <!-- Existing Gallery Images Preview -->
            @if($product->images->count() > 0)
                <div class="col-12">
                    <label class="form-label small fw-bold text-uppercase d-block mb-2">Existing Gallery Photos ({{ $product->images->count() }})</label>
                    <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-3 border">
                        @foreach($product->images as $img)
                            <div class="position-relative border rounded-2 overflow-hidden" style="width: 100px; height: 100px;">
                                <img src="{{ $img->image_path }}" class="w-100 h-100 object-fit-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Options -->
            <div class="col-12">
                <div class="d-flex gap-4 p-3 bg-light rounded-3 border">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="is_featured">
                            Feature on Home Page
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="is_active">
                            Visible to Customers
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                    <i class="bi bi-check2 me-1"></i> Update Furniture
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light px-4">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
