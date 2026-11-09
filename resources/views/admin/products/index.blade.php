@extends('layouts.admin')

@section('title', 'Product Management')
@section('page-title', 'Furniture Catalog Management')
@section('page-subtitle', 'Add, edit, delete, and control stock availability of furniture pieces')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <!-- Header Controls -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <!-- Search and Filters Form -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex flex-wrap gap-2 flex-grow-1">
            <input type="text" name="search" class="form-control form-control-sm bg-light" style="max-width: 200px;" placeholder="Search name/desc..." value="{{ request('search') }}">
            
            <select name="category_id" class="form-select form-select-sm bg-light" style="max-width: 170px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="stock_status" class="form-select form-select-sm bg-light" style="max-width: 150px;">
                <option value="">Stock Status</option>
                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock (>5)</option>
                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock (1-5)</option>
                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
            </select>

            <button type="submit" class="btn btn-dark btn-sm">Filter</button>
            @if(request()->anyFilled(['search', 'category_id', 'stock_status']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.products.create') }}" class="btn btn-warning fw-bold btn-sm text-dark px-3 text-nowrap">
            <i class="bi bi-plus-lg me-1"></i> Add New Furniture
        </a>
    </div>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small text-muted text-uppercase">
                <tr>
                    <th style="min-width: 260px;">Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th class="text-end" style="min-width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3 py-1">
                                <div class="rounded-3 overflow-hidden border shadow-sm" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <img src="{{ $product->main_image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=100&q=80' }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small">
                                        {{ $product->name }}
                                        @if($product->is_featured)
                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Featured</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $product->images->count() + 1 }} total photos</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border small">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            @if($product->is_on_sale)
                                <span class="fw-bold text-dark">₱{{ number_format($product->sale_price, 2) }}</span>
                                <small class="text-muted text-decoration-line-through d-block">₱{{ number_format($product->price, 2) }}</small>
                            @else
                                <span class="fw-bold text-dark">₱{{ number_format($product->price, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->stock > 5)
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $product->stock }} units</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold">{{ $product->stock }} units (Low)</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger fw-bold">0 units (Out)</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-star-fill text-warning small"></i>
                                <span class="fw-bold small">{{ $product->average_rating }}</span>
                                <small class="text-muted">({{ $product->review_count }})</small>
                            </div>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success small">Active</span>
                            @else
                                <span class="badge bg-secondary small">Hidden</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-light btn-sm p-1 px-2" title="Preview on store" target="_blank">
                                    <i class="bi bi-eye text-muted"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-light btn-sm p-1 px-2" title="Edit product">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product and its associated images?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm p-1 px-2 text-danger" title="Delete product">
                                        <i class="bi bi-trash"></i>
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

    <div class="mt-4 pt-2 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
