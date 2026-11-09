@extends('layouts.admin')

@section('title', 'Category Management')
@section('page-title', 'Furniture Categories')
@section('page-subtitle', 'Manage room collections and organizational taxonomy')

@section('content')
<div class="row g-4">
    <!-- Add Category Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3">Add New Category</h5>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-light" required placeholder="e.g. Dining Room">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">Description</label>
                    <textarea name="description" rows="3" class="form-control bg-light" placeholder="Short description of this room or collection..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">Category Image</label>
                    <input type="file" name="image" class="form-control bg-light" accept="image/*">
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label small" for="is_active">
                        Active & Visible on Store
                    </label>
                </div>

                <button type="submit" class="btn btn-warning fw-bold text-dark w-100 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Save Category
                </button>
            </form>
        </div>
    </div>

    <!-- Category List Table -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3">Existing Categories ({{ $categories->total() }})</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light small text-muted text-uppercase">
                        <tr>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 overflow-hidden border" style="width: 48px; height: 48px; flex-shrink: 0;">
                                            <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=100&q=80' }}" class="w-100 h-100 object-fit-cover">
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 small">{{ $category->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($category->description, 45) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $category->products_count }} items</span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success small">Active</span>
                                    @else
                                        <span class="badge bg-secondary small">Hidden</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm text-danger p-1 px-2" {{ $category->products_count > 0 ? 'disabled title="Cannot delete category containing products"' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No categories created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-2 d-flex justify-content-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
