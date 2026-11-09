<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Modern Handcrafted Furniture') | Santi Furniture</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Luxury CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @stack('styles')
</head>
<body>

    <!-- Top Notice Bar -->
    <div class="bg-dark text-white py-2 text-center small fw-medium" style="background-color: var(--color-primary) !important;">
        <div class="container d-flex justify-content-between align-items-center">
            <span><i class="bi bi-truck me-2 text-warning"></i> Complimentary White-Glove Delivery on all Orders • Cash on Delivery (COD)</span>
            <div class="d-none d-md-flex gap-3">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-warning text-decoration-none fw-semibold"><i class="bi bi-shield-lock me-1"></i> Admin Dashboard</a>
                    @else
                        <a href="{{ route('user.analytics') }}" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-graph-up me-1"></i> Shopping Stats</a>
                    @endif
                @else
                    <a href="{{ route('admin.login') }}" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-shield-lock me-1"></i> Admin Portal</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-luxury sticky-top">
        <div class="container">
            <a class="brand-logo" href="{{ route('home') }}">
                <i class="bi bi-lamp-fill text-warning"></i> SANTI<span>.</span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('products.index') ? 'active fw-bold' : '' }}" href="{{ route('products.index') }}">Collections</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Categories</a>
                        <ul class="dropdown-menu shadow-sm border-0 rounded-3">
                            <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => 'living-room']) }}"><i class="bi bi-couch me-2 text-muted"></i> Living Room</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => 'dining-room']) }}"><i class="bi bi-cup-hot me-2 text-muted"></i> Dining Room</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => 'office-workspace']) }}"><i class="bi bi-briefcase me-2 text-muted"></i> Office & Workspace</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => 'bedroom']) }}"><i class="bi bi-moon-stars me-2 text-muted"></i> Bedroom</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => 'storage-shelving']) }}"><i class="bi bi-bookshelf me-2 text-muted"></i> Storage & Shelving</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('products.index', ['category' => 'entertainment']) }}"><i class="bi bi-tv me-2 text-muted"></i> Entertainment</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('home') }}#about">About Us</a>
                    </li>
                </ul>

                <!-- Search form in navbar -->
                <form action="{{ route('products.index') }}" method="GET" class="d-none d-xl-flex me-3 position-relative" style="width: 220px;">
                    <input type="text" name="search" class="form-control form-control-sm rounded-pill ps-4 bg-light border-0" placeholder="Search furniture..." value="{{ request('search') }}">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted small"></i>
                </form>

                <!-- Actions: Cart, Notifications, Auth -->
                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(Auth::user()->isUser())
                            <!-- Notification Bell -->
                            @php
                                $unreadCount = Auth::user()->unreadNotificationsCount();
                            @endphp
                            <a href="{{ route('notifications.index') }}" class="btn btn-light rounded-circle position-relative p-2" title="Notifications">
                                <i class="bi bi-bell text-dark fs-5"></i>
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- Cart Icon -->
                            @php
                                $cartCount = Auth::user()->cartItems()->sum('quantity');
                            @endphp
                            <a href="{{ route('cart.index') }}" class="btn btn-light rounded-circle position-relative p-2" title="Shopping Cart">
                                <i class="bi bi-bag text-dark fs-5"></i>
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fw-bold">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- User Dropdown Menu -->
                            <div class="dropdown">
                                <button class="btn btn-light rounded-pill dropdown-toggle d-flex align-items-center gap-2 px-3 py-1 border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle fs-5 text-secondary"></i>
                                    <span class="fw-semibold small d-none d-sm-inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                        <div class="small text-muted">{{ Auth::user()->email }}</div>
                                    </li>
                                    <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}"><i class="bi bi-box-seam me-2 text-muted"></i> My Orders</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('reviews.index') }}"><i class="bi bi-star me-2 text-muted"></i> My Reviews</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('user.analytics') }}"><i class="bi bi-graph-up-arrow me-2 text-muted"></i> Shopping Analytics</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('profile.show') }}"><i class="bi bi-person-gear me-2 text-muted"></i> Account Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Log Out</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <!-- Admin User Header Link -->
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-luxury btn-sm">Admin Panel</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-luxury-outline btn-sm">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-luxury btn-sm">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                <div>{{ session('warning') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Luxury Footer -->
    <footer class="footer-luxury">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <a class="brand-logo text-white mb-3 d-inline-block" href="{{ route('home') }}">
                        <i class="bi bi-lamp-fill text-warning"></i> SANTI<span>.</span>
                    </a>
                    <p class="text-white-50 pe-lg-4">
                        Curating timeless, architectural furniture pieces that harmonize organic minimalism with uncompromising durability. Designed to transform ordinary spaces into serene sanctuaries.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-pinterest"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="text-uppercase fw-bold text-white mb-3 letter-spacing-1">Collections</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ route('products.index', ['category' => 'living-room']) }}">Living Room</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'dining-room']) }}">Dining Tables</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'office-workspace']) }}">Ergonomic Office</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'bedroom']) }}">Bedroom Frames</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'storage-shelving']) }}">Modular Storage</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="text-uppercase fw-bold text-white mb-3 letter-spacing-1">Customer Care</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ route('orders.index') }}">Track Order</a></li>
                        <li><a href="#">Cash on Delivery Policy</a></li>
                        <li><a href="#">White Glove Delivery</a></li>
                        <li><a href="#">Warranty & Care</a></li>
                        <li><a href="{{ route('reviews.index') }}">Verified Reviews</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h6 class="text-uppercase fw-bold text-white mb-3 letter-spacing-1">Payment & Security</h6>
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-cash-stack text-warning fs-4"></i>
                            <span class="fw-bold text-white">Cash on Delivery (COD)</span>
                        </div>
                        <p class="small text-white-50 mb-0">Inspect your furniture upon arrival. Pay cash directly to the courier after you are fully satisfied with your order.</p>
                    </div>
                    <div class="small text-white-50">
                        <i class="bi bi-shield-check text-success me-1"></i> 100% Risk-Free Ordering Guaranteed.
                    </div>
                </div>
            </div>
            
            <div class="pt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
                <div>&copy; {{ date('Y') }} Santi Furniture Systems Inc. All rights reserved.</div>
                <div class="d-flex gap-3 mt-2 mt-md-0">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="{{ route('admin.login') }}">Staff Login</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
</body>
</html>
