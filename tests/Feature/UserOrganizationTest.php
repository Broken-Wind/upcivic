<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Organization;
use Upcivic\User;

class UserOrganizationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_without_organization_prompted_to_create_or_join()
    {
        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->followingRedirects()->get('/home');


        $response->assertSeeText("Ask your administrator to invite you");

        $response->assertSeeText("Add your organization");

    }

    /** @test */
    public function user_without_organization_can_create_organization()
    {

        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->followingRedirects()->post('/organizations', [

            'name' => 'CoolOrg',

            'slug' => 'breh',

        ]);

        $response->assertSeeText("If you've been invited to propose a program to an organization using Upcivic");

    }
}
