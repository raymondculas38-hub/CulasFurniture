# Santi Furniture E-Commerce Platform

A production-ready, high-security e-commerce web platform engineered with **Laravel 10**, **PHP 8.2**, and modern clean software architecture principles. Features an end-to-end Cash on Delivery (COD) ordering workflow, catalog management, customer reviews, admin business analytics, and a multi-layered security suite.

---

## 🏛️ Architecture Overview

The codebase is organized following clean architectural patterns and separation of concerns:

```
app/
├── Enums/                          # Domain Enums (OrderStatus, UserRole)
│   ├── OrderStatus.php             # Typed state machine for order transitions
│   └── UserRole.php                # System access roles (admin, user)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                  # Backoffice management controllers
│   │   ├── Auth/                   # Multi-guard authentication controllers
│   │   └── User/                   # Storefront & customer dashboard controllers
│   ├── Middleware/
│   │   ├── AdminMiddleware.php     # RBAC admin gateway & violation audit logging
│   │   ├── Authenticate.php        # Context-aware unauthenticated redirector
│   │   ├── SanitizeInput.php       # Global null-byte and control character cleaner
│   │   ├── SecurityHeaders.php     # HTTP security response headers (CSP, HSTS, etc.)
│   │   └── UserMiddleware.php      # Customer-only route protector
│   └── Requests/                   # Dedicated Form Request Validation Layer
│       ├── Admin/                  # Backoffice request validation & auth rules
│       ├── Auth/                   # Login, register, admin login requests
│       └── User/                   # Cart, checkout, review, profile form requests
├── Models/                         # Eloquent domain models with mass-assignment guards
├── Providers/                      # Service providers & rate limiter registry
└── Services/                       # Business logic services (Orders, Analytics, Notifications)
```

---

## 🛡️ Security Architecture & Hardening

The application is hardened against OWASP Top 10 web vulnerabilities with comprehensive defense-in-depth measures:

### 1. Multi-Tier Rate Limiting & Anti-Abuse
Defined in `App\Providers\RouteServiceProvider` and enforced on critical routes:
- **`throttle:login`**: 5 attempts per minute per `IP + Email` combination to defeat credential stuffing and brute-force attacks.
- **`throttle:register`**: 3 registrations per minute per IP to mitigate automated bot account creation.
- **`throttle:checkout`**: 5 orders per 10 minutes per customer/IP to prevent checkout flooding and inventory lock attacks.
- **`throttle:cart`**: 30 cart actions per minute to mitigate automated scraping and cart exhaustion.
- **`throttle:reviews`**: 5 submissions per 10 minutes to protect against review bombing.
- **`throttle:admin`**: 60 requests per minute to protect the administrative surface.
- **`throttle:global`**: 120 requests per minute baseline for public storefront endpoints.

### 2. HTTP Security Response Headers
Enforced by `App\Http\Middleware\SecurityHeaders`:
- **`X-Frame-Options: SAMEORIGIN`**: Eliminates clickjacking attacks.
- **`X-Content-Type-Options: nosniff`**: Prevents MIME-type confusion attacks.
- **`X-XSS-Protection: 1; mode=block`**: Activates browser XSS reflection filters.
- **`Referrer-Policy: strict-origin-when-cross-origin`**: Stops referrer credential leakage.
- **`Permissions-Policy: camera=(), microphone=(), geolocation=()`**: Disables non-essential browser hardware APIs.
- **`Content-Security-Policy (CSP)`**: Restricts external script and style sources to vetted endpoints.
- **Header Cloaking**: Automatically strips `X-Powered-By` to prevent PHP version fingerprinting.

### 3. Insecure Direct Object Reference (IDOR) Prevention
- Every customer route (`/my-orders/{order}`, `/cart/{cartItem}`, `/notifications/{notification}`) enforces strict user ownership (`$item->user_id === Auth::id()`).
- Direct tampering returns `403 Forbidden`.

### 4. Mass Assignment & Privilege Escalation Protection
- The `role` column is excluded from `$fillable` on the `User` model.
- Customer registrations explicitly bind `role = 'user'`, preventing privilege escalation via malicious payload injection.

### 5. Input Sanitization & Type-Safe Domain Enums
- `App\Http\Middleware\SanitizeInput` recursively purges null bytes (`\0`) and invalid control characters from inputs.
- Valid order transitions and user roles are strictly governed by PHP 8.1+ Enums (`OrderStatus`, `UserRole`).

### 6. Branded Security Error Pages
Custom responsive error templates are provided in `resources/views/errors/`:
- `403.blade.php`: Access Denied / Insufficient Privileges.
- `404.blade.php`: Missing Resource / Product Not Found.
- `419.blade.php`: CSRF Token & Session Expiration with refresh CTA.
- `429.blade.php`: Rate Limit Exceeded with retry guidance.
- `500.blade.php`: Graceful Internal Server Error without leaking database credentials.

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.1 (PHP 8.2 Recommended)
- MySQL / MariaDB
- Composer

### Installation
1. Clone the repository and configure your environment:
   ```powershell
   cp .env.example .env
   php artisan key:generate
   ```
2. Run database migrations with seed data:
   ```powershell
   php artisan migrate --seed
   ```
3. Start the application:
   ```powershell
   php artisan serve --port=8001
   ```

### Default Credentials
| Portal | URL | Email | Password |
|---|---|---|---|
| Admin Dashboard | `http://127.0.0.1:8001/admin/login` | `admin@furniture.com` | `password` |
| Customer Store | `http://127.0.0.1:8001/login` | `santi@gmail.com` | `password` |

---

## 🧪 Automated Testing

Execute the automated test suite including the security verification suite:
```powershell
php artisan test
```
All feature tests, authentication policies, rate limiters, security headers, and IDOR assertions run automatically.
