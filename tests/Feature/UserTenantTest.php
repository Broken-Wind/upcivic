<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Organization;
use Upcivic\Tenant;
use Upcivic\User;

class UserTenantTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_without_tenant_prompted_to_create_or_join()
    {
        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->followingRedirects()->get('/home');


        $response->assertSeeText("Ask your administrator to invite you");

        $response->assertSeeText("Add your organization");

    }

    /** @test */
    public function user_without_tenant_can_create_tenant()
    {

        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->followingRedirects()->post('/tenants', [

            'name' => 'CoolOrg',

            'slug' => 'breh',

        ]);

        $response->assertSeeText("If you've been invited to propose a program to an organization using Upcivic");

    }

    /** @test */
    public function user_with_tenant_can_edit_tenant()
    {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();



        $response = $this->actingAs($user)->followingRedirects()->patch("/{$tenant->slug}/admin/settings", [

            'name' => 'Bobby Dodgekins',

        ]);

        $tenant->refresh();


        $response->assertStatus(200);

        $this->assertEquals('Bobby Dodgekins', $tenant->name);

    }

    /** @test */
    public function user_cannot_edit_tenant_if_not_member()
    {

        $user = factory(User::class)->states('hasTenant')->create();

        $organizationUserDoesNotBelongTo = factory(Organization::class)->create([

            'name' => 'Should not change.',

        ]);

        $tenantUserDoesNotBelongTo = factory(Tenant::class)->create([

            'slug' => 'nochangey',

            'organization_id' => $organizationUserDoesNotBelongTo->id,

        ]);



        $response = $this->actingAs($user)->followingRedirects()->patch("/{$tenantUserDoesNotBelongTo->slug}/admin/settings", [

            'name' => 'Bobby Dodgekins',

        ]);

        $organizationUserDoesNotBelongTo->refresh();


        $response->assertStatus(401);

        $this->assertEquals('Should not change.', $organizationUserDoesNotBelongTo->name);

    }
}
