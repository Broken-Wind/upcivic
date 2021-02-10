<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Organization;
use App\Tenant;
use App\User;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_visit_program_list()
    {
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();

        $response = $this->actingAs($user)->followingRedirects()->get('/home');

        $response->assertStatus(200);
        $this->assertEquals(url()->current(), config('app.url')."/{$tenant->slug}/admin/programs");
        $response->assertSeeText('Add program');
        $response->assertSeeText($tenant->name);
    }

    /** @test */
    public function guest_cannot_visit_home()
    {
        factory(Tenant::class)->create();

        $this->followingRedirects()->get('/home');

        $this->assertEquals(url()->current(), config('app.url').'/login');
    }

    /** @test */
    public function user_cannot_visit_dashboard_if_not_member()
    {
        $user = factory(User::class)->create();
        $tenant = factory(Tenant::class)->create();

        $response = $this->actingAs($user)->followingRedirects()->get("/{$tenant->slug}/admin/home");

        $response->assertStatus(401);
        $response->assertDontSeeText($tenant->name);
    }

    /** @test */
    public function guest_cannot_visit_dashboard()
    {
        $tenant = factory(Tenant::class)->create();

        $response = $this->followingRedirects()->get("/{$tenant->slug}/admin/home");

        $response->assertViewIs('auth.login');
        $this->assertEquals(url()->current(), config('app.url').'/login');
        $response->assertDontSeeText($tenant->name);
    }
}
