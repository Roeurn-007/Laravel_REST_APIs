<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_for_admin_user(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('products.index'))->assertOk();
        $this->get(route('categories.index'))->assertOk();
        $this->get(route('orders.index'))->assertOk();
        $this->get(route('users.index'))->assertOk();
    }
}
