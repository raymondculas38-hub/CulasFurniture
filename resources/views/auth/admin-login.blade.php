<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login | Santi Furniture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body style="background: radial-gradient(circle at top right, #3A2F28, #1E1916); min-height: 100vh;" class="d-flex align-items-center justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5" style="background: rgba(255, 255, 255, 0.98);">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex p-3 rounded-circle bg-dark text-warning mb-3">
                            <i class="bi bi-shield-lock-fill fs-2"></i>
                        </div>
                        <h3 class="fw-bold mb-1 font-serif text-dark">SANTI<span class="text-warning">.</span></h3>
                        <div class="text-uppercase tracking-wider small fw-bold text-muted" style="letter-spacing: 2px;">Administrator Portal</div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger small py-2 rounded-3 border-0 mb-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Admin Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-badge text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0" required autofocus value="{{ old('email', 'admin@furniture.com') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-start-0" required value="password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold text-white mb-3" style="background-color: var(--color-primary);">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Dashboard
                        </button>

                        <div class="text-center">
                            <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Return to Main Website
                            </a>
                        </div>
                    </form>

                    <div class="mt-4 pt-3 border-top text-center">
                        <small class="text-muted d-block">Default credentials:</small>
                        <span class="badge bg-light text-dark border mt-1">
                            admin@furniture.com / password
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
