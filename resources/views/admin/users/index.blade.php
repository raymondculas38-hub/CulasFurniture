@extends('layouts.admin')

@section('title', 'Customer & User Management')
@section('page-title', 'Customer Management')
@section('page-subtitle', 'View registered customers, their spending, and order frequency')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <!-- Search and Role filter -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex flex-wrap gap-2 flex-grow-1">
            <input type="text" name="search" class="form-control form-control-sm bg-light" style="max-width: 250px;" placeholder="Search name, email, phone..." value="{{ request('search') }}">
            
            <select name="role" class="form-select form-select-sm bg-light" style="max-width: 150px;">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Customers</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrators</option>
            </select>

            <button type="submit" class="btn btn-dark btn-sm">Filter</button>
            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>

        <div class="text-muted small">
            Total Accounts: <strong class="text-dark">{{ $users->total() }}</strong>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small text-muted text-uppercase">
                <tr>
                    <th>User</th>
                    <th>Contact Phone</th>
                    <th>Role</th>
                    <th>Total Orders</th>
                    <th>Total Spent (COD)</th>
                    <th>Joined</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning bg-opacity-25 text-warning-emphasis fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small text-dark">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="small text-muted">{{ $user->phone ?? '—' }}</td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge bg-dark text-warning small">Admin</span>
                            @else
                                <span class="badge bg-light text-dark border small">Customer</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $user->orders_count }} orders</span>
                        </td>
                        <td class="fw-bold text-dark font-serif">
                            ₱{{ number_format($user->total_spent ?? 0, 2) }}
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light btn-sm py-1 px-2">
                                <i class="bi bi-person-lines-fill text-muted"></i> Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pt-2 d-flex justify-content-center">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
