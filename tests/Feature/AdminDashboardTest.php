<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Organization;
use Upcivic\User;

class AdminDashboardTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_visit_dashboard()
    {

        $user = factory(User::class)->states('hasOrganization')->create();

        $organization = $user->organizations()->first();


        $response = $this->actingAs($user)->followingRedirects()->get('/home');


        $response->assertStatus(200);

        $this->assertEquals(url()->current(), config('app.url') . "/{$organization->slug}/admin/home");

        $response->assertSeeText('Welcome to Upcivic!');

        $response->assertSeeText($organization->name);

    }


    /** @test */
    public function guest_cannot_visit_home()
    {
        factory(Organization::class)->create();


        $this->followingRedirects()->get('/home');


        $this->assertEquals(url()->current(), config('app.url') . "/login");

    }


    /** @test */
    public function user_cannot_visit_dashboard_if_not_member()
    {
        $user = factory(User::class)->create();

        $organization = factory(Organization::class)->create();


        $response = $this->actingAs($user)->followingRedirects()->get("/{$organization->slug}/admin/home");


        $response->assertStatus(401);

        $response->assertDontSeeText($organization->name);
    }


    /** @test */
    public function guest_cannot_visit_dashboard()
    {

        $organization = factory(Organization::class)->create();


        $response = $this->followingRedirects()->get("/{$organization->slug}/admin/home");



        $response->assertViewIs('auth.login');

        $this->assertEquals(url()->current(), config('app.url') . "/login");

        $response->assertDontSeeText($organization->name);

    }
}
