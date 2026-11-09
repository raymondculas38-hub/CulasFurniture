<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    public function test_user_analytics_page_loads_successfully(): void
    {
        $user = User::where('email', 'santi@gmail.com')->first();
        $response = $this->actingAs($user)->get('/my-analytics');

        $response->assertStatus(200);
        $response->assertSee('My Shopping Insights');
    }

    public function test_admin_dashboard_and_analytics_pages_load_successfully(): void
    {
        $admin = User::where('email', 'admin@furniture.com')->first();
        
        $response1 = $this->actingAs($admin)->get('/admin/dashboard');
        $response1->assertStatus(200);

        $response2 = $this->actingAs($admin)->get('/admin/analytics');
        $response2->assertStatus(200);
    }
}
