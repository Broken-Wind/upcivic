<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Upcivic\Organization;
use Upcivic\Tenant;
use Upcivic\User;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_visit_dashboard()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $response = $this->actingAs($user)->followingRedirects()->get('/home');

        $response->assertStatus(200);

        $this->assertEquals(url()->current(), config('app.url')."/{$tenant->slug}/admin/home");

        $response->assertSeeText('Welcome to Upcivic!');

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
