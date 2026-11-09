<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    /**
     * Test HTTP Security Headers are present on responses.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertFalse($response->headers->has('X-Powered-By'));
    }

    /**
     * Test guest cannot access admin dashboard.
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test regular customer cannot access admin dashboard.
     */
    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $user = User::where('role', UserRole::USER->value)->first();
        if (!$user) {
            $user = User::factory()->create(['role' => UserRole::USER->value]);
        }

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test rate limiting on login endpoint.
     */
    public function test_login_rate_limiting(): void
    {
        RateLimiter::clear(md5('login_throttle_test'));

        $email = 'bruteforce_' . uniqid() . '@example.com';

        // Submit 5 invalid attempts
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'wrongpassword',
            ]);
            $response->assertSessionHasErrors('email');
        }

        // The 6th attempt should trigger rate limiting message
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errorMsg = session('errors')->first('email');
        $this->assertStringContainsString('Too many login attempts', $errorMsg);
    }

    /**
     * Test IDOR: Customer cannot view another customer's order.
     */
    public function test_customer_cannot_view_another_customer_order(): void
    {
        $userA = User::where('email', 'santi@gmail.com')->first();
        $userB = User::where('email', 'sarah.jenkins@example.com')->first();

        $this->assertNotNull($userA);
        $this->assertNotNull($userB);

        // Find or create an order for User B
        $orderB = Order::where('user_id', $userB->id)->first();
        if (!$orderB) {
            $orderB = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userB->id,
                'subtotal' => 100,
                'total' => 100,
                'status' => OrderStatus::PENDING->value,
                'payment_method' => 'COD',
                'delivery_name' => 'Sarah',
                'delivery_phone' => '123456789',
                'delivery_address' => 'Test address',
            ]);
        }

        // Acting as User A trying to view User B's order
        $response = $this->actingAs($userA)->get("/my-orders/{$orderB->id}");
        $response->assertStatus(403);
    }

    /**
     * Test user role is protected against mass assignment tampering.
     */
    public function test_user_role_cannot_be_mass_assigned(): void
    {
        $user = new User([
            'name' => 'Hacker Test',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'role' => 'admin', // Attempt privilege escalation
        ]);

        $this->assertNotEquals('admin', $user->role);
    }
}

