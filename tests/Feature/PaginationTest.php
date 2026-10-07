<?php

namespace Tests\Feature;

use App\Models\Infrastructure;
use App\Models\Maintenance;
use App\Models\User;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    private function getAdminUser(): User
    {
        return User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin']);
    }

    public function test_infrastructures_front_page_renders_clean_pagination(): void
    {
        $response = $this->get(route('front.infrastructures.index'));

        $response->assertStatus(200);
        $response->assertSee('aquasecure-pagination');
        $response->assertSee('pagination-pages');
        $response->assertSee('pagination-svg');
        // Ensure the old broken Tailwind full-width classes are not unstyled
        $response->assertDontSee('class="w-5 h-5"');
    }

    public function test_admin_maintenances_renders_clean_pagination(): void
    {
        $admin = $this->getAdminUser();
        $response = $this->actingAs($admin)->get(route('maintenances.index'));

        $response->assertStatus(200);
        $response->assertSee('aquasecure-pagination');
        $response->assertSee('pagination-pages');
        $response->assertSee('pagination-svg');
        $response->assertDontSee('class="w-5 h-5"');
    }
}
